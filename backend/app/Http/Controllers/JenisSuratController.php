<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Parameter;
use App\Models\User;
use App\Http\Resources\JenisSuratResource;
use App\Classes\Response;
use Illuminate\Support\Facades\DB;

class JenisSuratController extends Controller {
  public function create(Request $r) {
    $validator = Validator::make($r->all(), [
      'name' => 'required|string|max:255|min:4'
    ]);

    if (!$validator->fails()) {
      $code = str_replace(' ', '_', strtolower(strtok($r->name, '-')));

      $data = Parameter::create([
        'type' => 'jenis_surat',
        'code' => $code,
        'name' => $r->name,
        'description' => ''
      ]);

      if (!$data) {
        return Response::pretty(500, 'Failed', 'Internal Server Error', null);
      }
    } else {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been created', $data);
  }

  public function read(Request $r) {
    $fields = ['code', 'name'];
    $oderField = ['asc', 'desc'];

    if ($r->order == null || $r->field != null ||
       !in_array($r->order, $oderField) || !in_array($r->field, $fields)) {
      $r->code = 'asc';
      $r->field = 'code';
    }

    $query = DB::table('parameter')
      ->select('code', 'name')
      ->where('type', 'jenis_surat')
      ->orderBy($r->field, $r->order);

    if (!is_null($r->search)) {
      $query = $query 
        ->where('code', 'like', '%'.$r->search.'%')
        ->orWhere('name', 'like', '%'.$r->search.'%');
    }

    return JenisSuratResource::collection($query->paginate(10));
  }

  public function update(Request $r) {
    $data = Parameter
      ::where('type', 'jenis_surat')
      ->where('code', $r->old_code)
      ->first();
    
    $data->name = $r->name;

    if (!$data->update()) {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been saved', $data);
  }

  public function delete(Request $r) {
    $data = [];
    $param = Parameter
      ::where('type', 'jenis_surat')
      ->where('code', $r->code)
      ->delete();

    if ($param) {
      $data = Response::pretty(200, 'Success', 'Deleted', null);
    } else {
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return $data;
  }

  public function dropdown(Request $r) {
    $fields = ['code', 'name'];
    $data = [];

    $query = DB::table('parameter')
      ->select('code', 'name')
      ->where('type', 'jenis_surat');

    if (!is_null($r->search)) {
      $query = $query->where('name', 'like', '%'.$r->search.'%');
    }

    $dataall = $query->get();

    foreach ($dataall as $val) {
      $data[] = [
        'text' => $val->name,
        'id' => $val->code
      ];
    }

    return response()->json($data);
  }

  // public function user(Request $r) {
  //   $data = User::where('id', $r->nipp)->first();
  //   $data->code_jabatan = $r->nama_jabatan;

  //   if (!$data->update()) {
  //     return Response::pretty(500, 'Failed', 'Internal Server Error', null);
  //   }

  //   return Response::pretty(200, 'Success', 'Data has been saved', $data);
  // }
}
