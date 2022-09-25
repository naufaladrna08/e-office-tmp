<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Http\Resources\ArchiveResource;
use Validator;
use Auth;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ArchiveController extends Controller {
  public function index(Request $r) {
    $data = [];

    $model = DB::table('archives')
      ->select(
        'mails.mail_number', 
        DB::raw('mails.subject AS perihal'),
        'mails.type',
        'mails.status',
        'mails.created_at'
      )
      ->leftJoin('mails', 'mails.mail_number', '=', 'archives.mid')
      ->leftJoin('users', 'users.id', '=', 'archives.uid');

    $dataall = $model->get();

    foreach ($dataall as $data) {
      $data->archived = true;
    }

    // return ArchiveResource::collection($dataall->paginate(10));
    return response()->json($this->paginates($dataall, 10, 1));
  }

  public function sendToArchive(Request $r) {
    $data = [];

    try {
      $model = new Archive();
      $model->uid = Auth::id();
      $model->mid = htmlentities($r->mail_number);

      if ($model->save()) {
        $data['code'] = 200;
        $data['status'] = 'Success';
        $data['message'] = 'Data has been saved';
        $data['data'] = $model;
      } else {
        $data['code'] = 500;
        $data['status'] = 'Failed';
        $data['message'] = 'Data not saved';
        $data['data'] = null;
      }

    } catch (Exception $e) {
      $data = [
        'status' => 'failed',
        'message' => $e->getMessage()
      ];
    }
    
    return response()->json($data);
  }

  public function delete(Request $r) {
    $data = [];

    try {
      $model = Archive::where('mid', $r->mail_number)->where('uid', Auth::id());

      if ($model->delete()) {
        $data['code'] = 200;
        $data['status'] = 'Success';
        $data['message'] = 'Data has been saved';
        $data['data'] = $model;
      } else {
        $data['code'] = 500;
        $data['status'] = 'Failed';
        $data['message'] = 'Data not saved';
        $data['data'] = null;
      }

    } catch (Exception $e) {
      $data = [
        'status' => 'failed',
        'message' => $e->getMessage()
      ];
    }
    
    return response()->json($data);
  }

  public function paginates($items, $perPage = 5, $page = null, $options = []) {
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
  }
}
