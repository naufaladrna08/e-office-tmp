<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\ParamJabatan;
use App\Models\User;
use App\Http\Resources\JabatanResource;
use App\Classes\Response;
use Illuminate\Support\Facades\DB;

class JabatanController extends Controller {
  public function create(Request $r) {
    $validator = Validator::make($r->all(), [
      'code_jabatan' => 'required|string|max:8|min:4',
      'nama_jabatan' => 'required|string|max:255|min:4'
    ]);

    if (!$validator->fails()) {
      $data = ParamJabatan::create([
        'code_jabatan' => $r->code_jabatan,
        'nama_jabatan' => $r->nama_jabatan
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
    $fields = ['code_jabatan', 'nama_jabatan'];
    $oderField = ['asc', 'desc'];

    if ($r->order == null || $r->field != null ||
       !in_array($r->order, $oderField) || !in_array($r->field, $fields)) {
      $r->order = 'asc';
      $r->field = 'code_jabatan';
    }

    $query = DB::table('param_jabatan')
      ->select('code_jabatan', 'nama_jabatan')
      ->orderBy($r->field, $r->order);

    if (!is_null($r->search)) {
      $query = $query 
        ->where('code_jabatan', 'like', '%'.$r->search.'%')
        ->orWhere('nama_jabatan', 'like', '%'.$r->search.'%');
    }

    return jabatanResource::collection($query->paginate(10));
  }

  public function update(Request $r) {
    $data = ParamJabatan::where('code_jabatan', $r->old_code_jabatan)->first();
    $data->code_jabatan = $r->code_jabatan != null ? $r->code_jabatan : $data->code_jabatan;
    $data->nama_jabatan = $r->nama_jabatan != null ? $r->nama_jabatan : $data->nama_jabatan;

    if (!$data->update()) {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been saved', $data);
  }

  public function delete(Request $r) {
    $data = [];
    $jabatan = ParamJabatan::where('code_jabatan', $r->code_jabatan)->delete();

    if ($jabatan) {
      $data = Response::pretty(200, 'Success', 'Deleted', null);
    } else {
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return $data;
  }

  public function dropdown(Request $r) {
    $fields = ['code_jabatan', 'nama_jabatan'];

    $query = DB::table('param_jabatan')
      ->select('code_jabatan', 'nama_jabatan')
      ->where('nama_jabatan', '<>', $r->nama_jabatan);

    // if (!is_null($r->search)) {
    //   $query = $query 
    //     ->where('code_jabatan', 'like', '%'.$r->search.'%')
    //     ->orWhere('nama_jabatan', 'like', '%'.$r->search.'%');
    // }

    return JabatanResource::collection($query->get());
  }

  public function user(Request $r) {
    $data = User::where('id', $r->nipp)->first();
    $data->code_jabatan = $r->nama_jabatan;

    if (!$data->update()) {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been saved', $data);
  }
}
