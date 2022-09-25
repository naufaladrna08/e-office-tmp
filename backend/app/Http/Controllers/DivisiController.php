<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\ParamDivisi;
use App\Models\User;
use App\Http\Resources\DivisiResource;
use App\Classes\Response;
use Illuminate\Support\Facades\DB;

class DivisiController extends Controller {
  public function create(Request $r) {
    $validator = Validator::make($r->all(), [
      'code_divisi' => 'required|string|max:8|min:4',
      'nama_divisi' => 'required|string|max:255|min:4'
    ]);

    if (!$validator->fails()) {
      $data = ParamDivisi::create([
        'code_divisi' => $r->code_divisi,
        'nama_divisi' => $r->nama_divisi
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
    $fields = ['code_divisi', 'nama_divisi'];
    $oderField = ['asc', 'desc'];

    if ($r->order == null || $r->field != null ||
       !in_array($r->order, $oderField) || !in_array($r->field, $fields)) {
      $r->order = 'asc';
      $r->field = 'code_divisi';
    }

    $query = DB::table('param_divisi')
      ->select('code_divisi', 'nama_divisi')
      ->orderBy($r->field, $r->order);

    if (!is_null($r->search)) {
      $query = $query 
        ->where('code_divisi', 'like', '%'.$r->search.'%')
        ->orWhere('nama_divisi', 'like', '%'.$r->search.'%');
    }

    return DivisiResource::collection($query->paginate(10));
  }

  public function update(Request $r) {
    $data = ParamDivisi::where('code_divisi', $r->old_code_divisi)->first();
    $data->code_divisi = $r->code_divisi != null ? $r->code_divisi : $data->code_divisi;
    $data->nama_divisi = $r->nama_divisi != null ? $r->nama_divisi : $data->nama_divisi;

    if (!$data->update()) {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been saved', $data);
  }

  public function delete(Request $r) {
    $data = [];
    $divisi = ParamDivisi::where('code_divisi', $r->code_divisi)->delete();

    if ($divisi) {
      $data = Response::pretty(200, 'Success', 'Deleted', null);
    } else {
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return $data;
  }

  public function dropdown(Request $r) {
    $fields = ['code_divisi', 'nama_divisi'];

    $query = DB::table('param_divisi')
      ->select('code_divisi', 'nama_divisi')
      ->where('nama_divisi', '<>', $r->nama_divisi);

    // if (!is_null($r->search)) {
    //   $query = $query 
    //     ->where('code_divisi', 'like', '%'.$r->search.'%')
    //     ->orWhere('nama_divisi', 'like', '%'.$r->search.'%');
    // }

    return DivisiResource::collection($query->get());
  }

  public function user(Request $r) {
    $data = User::where('id', $r->nipp)->first();
    $data->code_divisi = $r->nama_divisi;

    if (!$data->update()) {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been saved', $data);
  }
}
