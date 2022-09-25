<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mail;
use App\Models\Relation;
use App\Models\Log;
use App\Models\GroupMember;
use App\Models\Attachment;
use App\Classes\Response;
use Validator;
use App\Http\Resources\SentResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use File;
use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class MailController extends Controller {
  public function create(Request $r) {
    $data = [];

    DB::beginTransaction(); 

    try {
      $mail = Mail::create([
        'mail_number' => $r->options['mailNumber'],
        'subject' => $r->options['subject'],
        'description' => $r->description,
        'is_active' => true,
        'status' => MAIL::TERKIRIM,
        'priority' => $r->options['prioritas'],
        'type' => $r->options['jenisSurat'],
        'klasifikasi' => $r->options['klasifikasi'],
        't_to' => $r->options['t_to'],
        't_tembusan' => $r->options['t_tembusan'],
        't_lampiran' => $r->options['t_lampiran'],
        'created_by' => auth()->user()->id,
      ]);

      $log = Log::insert(
        'mail', 
        $mail->id,
        auth()->user()->id . ' membuat surat ' . $mail->mail_number,
        'CREATED'
      );

      $data = $mail;

      /* Groups */
      foreach ($r->options['group'] as $each) {
        $relation = new Relation();
        $relation->type = 'M_GROUP'; // Mail Group
        $relation->item = $r->options['mailNumber'];
        $relation->from = auth()->user()->id;
        $relation->to   = $each['gid'];
        $relation->save();
      }

      /* Attachment */
      $files = $r->attachments;

      if (count($files) > 0) {
        foreach ($files as $file) {
          $ext = $file->getClientOriginalExtension();
          $name = time().'-'.".".$ext;
          $upload_path = 'backend/files/';
          $upload_url = $upload_path . $name;
          $file->move(public_path($upload_path), $upload_url);

          $attachment = new Attachment;
          $attachment->parent = 'MAIL';
          $attachment->parent_id = $mail->id;
          $attachment->url = $upload_url;
          $attachment->save();
        }
      }

      DB::commit();
    } catch (Exception $e) {
      DB::rollback();
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been created', $data);
  }

  public function read(Request $r) {
    $mail = DB::table('mails')
      ->select(
        'mails.id', 
        'mails.mail_number', 
        'mails.description', 
        'mails.type', 
        'mails.created_at', 
        'mails.updated_at', 
        'mails.subject', 
        'mails.t_to', 
        'mails.t_tembusan', 
        'mails.t_lampiran', 
        DB::raw('pr.name AS PRIORITAS'),
        DB::raw('kl.name AS KLASIFIKASI')
      )
      ->leftJoin('parameter AS pr', function ($join) {
        $join->on("pr.type", '=', DB::raw("'prioritas'"))->on('pr.code', 'mails.priority');
      })
      ->leftJoin('parameter AS kl', function ($join) {
        $join->on("kl.type", '=', DB::raw("'klasifikasi'"))->on('kl.code', 'mails.klasifikasi');
      })
      ->where('mails.mail_number', $r->id)
      ->first();

    return Response::pretty(200, 'Success', 'Data available', $mail);
  }

  public function readAll(Request $r) {
    $sortField = [
      'no_surat' => 'mail_number',
      'kepada' => 'uid',
      'perihal' => 'perihal',
      'tanggal' => 'created_at',
      'jenis_surat' => 'type',
      'status' => 'status',
      'keterangan' => 'description'
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, array_values($sortField)) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'mail_number';
    }

    $query = DB::table('mails')
      ->select(
        'mails.mail_number', 
        'mails.uid', 
        DB::raw('mails.subject AS perihal'),
        'mails.created_at',
        'mails.type',
        'mails.status',
        DB::raw("(case when mails.status = 'TERKIRIM' THEN CONCAT('Surat telah diterima oleh ', users.name) ELSE 'NO COMMENT' END) AS description")
      )
      ->where('created_by', auth()->user()->id)
      ->where('is_active', true)
      ->where('uid', '<>', auth()->user()->id)
      ->leftJoin('users', 'users.id', '=', 'mails.uid')
      ->orderBy($r->field, $r->order);
    
    if (!is_null($r->search)) {
      $query = $query
        ->where('mail_number', 'like', '%'.$r->search.'%')
        ->orWhere('subject', 'like', '%'.$r->search.'%');
    }

    $dataall = $query->get();

    foreach ($dataall as $each) {
      $model = DB::table('archives')
        ->select('*')
        ->where('mid', $each->mail_number)
        ->where('uid', Auth::id())
        ->first();

      if ($model) {
        $each->archived = true;
      } else {
        $each->archived = false;
      }
    }

    // return SentResource::collection($query->paginate(10));
    return response()->json($this->paginates($dataall, 10, 1));
  }

  public function readInbox(Request $r) {
    $sortField = [
      'no_surat' => 'mail_number',
      'kepada' => 'uid',
      'perihal' => 'perihal',
      'tanggal' => 'created_at',
      'jenis_surat' => 'type',
      'status' => 'status',
      'keterangan' => 'description'
    ];

    $orderField = ['asc', 'desc'];

    $ids = null;
    /* Get groups of the user */
    $groups = GroupMember::where('user_id', auth()->user()->id)->get();

    /* Get mail from relation */
    foreach ($groups as $group) {
      $relation = Relation::where('to', $group->group_id)->where('type', 'M_GROUP')->get();

      if ($relation != false) {
        foreach ($relation as $each) {
          $ids[] = $each['item'];
        }
      }
    }

    $query = DB::table('mails')
      ->select(
        'mails.mail_number',
        DB::raw('mails.subject AS perihal'),
        'mails.type',
        'mails.status',
        'mails.description',
        'mails.created_at',
      )
      ->wherein('mail_number', $ids)
      ->where('is_active', true)
      ->orderBy('created_at', 'desc');

    if ($r->order == null || $r->field == null || 
        !in_array($r->field, array_values($sortField)) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'mail_number';

      $query->orderBy($r->field, $r->order);
    }

    if (!is_null($r->search)) {
      $query = $query
        ->where('mail_number', 'like', '%'.$r->search.'%')
        ->orWhere('subject', 'like', '%'.$r->search.'%');
    }

    $dataall = $query->get();

    foreach ($dataall as $each) {
      $model = DB::table('archives')
        ->select('*')
        ->where('mid', $each->mail_number)
        ->where('uid', Auth::id())
        ->first();

      if ($model) {
        $each->archived = true;
      } else {
        $each->archived = false;
      }
    }

    // return SentResource::collection($query->paginate(10));
    return response()->json($this->paginates($dataall, 10, 1));
  }

  public function update(Request $r) {
    $mail = Mail::where('id', $r->id)
      ->where('is_active', true)
      ->firstOrFail();

    $mail->subject = $r->subject;
    $mail->description = $r->description;
    
    if ($mail->save()) {
      return Response::pretty(200, 'Success', 'Data has been updated', $mail);
    } else {
      return Response::pretty(500, 'Failed', 'Data available', $mail);
    }
  }

  public function delete(Request $r) {
    $mail = Mail::where('id', $r->id)
      ->where('is_active', true)
      ->firstOrFail();

    $mail->is_active = false;
    
    if ($mail->save()) {
      return Response::pretty(200, 'Success', 'Data has been updated', null);
    } else {
      return Response::pretty(500, 'Failed', 'Data available', null);
    }
  }

  public function readTemplate(Request $r) {
    $data = [];

    $file  = public_path() . '/templates/template_' . $r->id . '.html';
    $image = asset('/templates/template_' . $r->id . '.png');
    $data = file_get_contents($file);

    return response()->json([
      'data' => $data,
      'image' => $image
    ]);
  }

  public function dropdownUsers(Request $r) {
    $fields = ['code', 'name'];
    $data = [];

    $query = DB::table('users')
      ->select('id', 'name');

    if (!is_null($r->search)) {
      $query = $query->where('name', 'like', '%'.$r->search.'%');
    }

    $dataall = $query->get();

    foreach ($dataall as $val) {
      $data[] = [
        'text' => $val->name,
        'id' => $val->id
      ];
    }

    return response()->json($data);
  }

  public function getReceiverAndCc(Request $r) {
    $datareceiver = DB::table('relations')
      ->where('item', $r->item)
      ->where('type','RECEIVER')
      ->leftJoin('users', 'relations.to', '=', 'users.id')
      ->get();

    $datacc = DB::table('relations')
      ->where('item', $r->item)
      ->where('type','CC')
      ->leftJoin('users', 'relations.to', '=', 'users.id')
      ->get();

    return response()->json([
      'data' => [
        'receiver' => $datareceiver,
        'cc' => $datacc
      ]
    ]);
  }

  public function getLog(Request $r) {
    $data = DB
      ::table('logs')
      ->select('*')
      ->where('type', 'mail')
      ->where('type_id', $r->id)
      ->get();

    return response()->json($data);
  }

  public function paginates($items, $perPage = 5, $page = null, $options = []) {
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
  }
}
