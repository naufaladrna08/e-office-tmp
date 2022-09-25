<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Classes\Response;
use App\Http\Resources\ParameterResource;
use Illuminate\Support\Facades\DB;

class ParameterController extends Controller {
  public function storeOrUpdate(Request $r) {
    $data = [];
    
    $model = Parameter::updateOrCreate([
      'type' => $r->type,
      'code' => $r->code
    ], [
      'name' => $r->name == null ? 'v1.0' : $r->name,
      'description' => $r->description
    ]);

    return Response::pretty(200, 'Success', 'Data has been saved', $model);
  }
  
  public function fetch(Request $r) {
    $data = [];
    
    $model = Parameter::where('type', $r->type)->firstOrFail();

    return Response::pretty(200, 'Success', 'Data has been saved', $model);
  }

  public function read(Request $r) {
    $fields = ['type', 'code', 'name', 'description'];
    $oderField = ['asc', 'desc'];

    if ($r->order == null || $r->field != null ||
       !in_array($r->order, $oderField) || !in_array($r->field, $fields)) {
      $r->code = 'asc';
      $r->field = 'code';
    }

    $query = DB::table('parameter')
      ->select('code', 'name', 'description', 'type')
      ->orderBy($r->field, $r->order);

    if (!is_null($r->search)) {
      $query = $query 
        ->where('type', 'like', '%'.$r->search.'%')
        ->orWhere('code', 'like', '%'.$r->search.'%')
        ->orWhere('name', 'like', '%'.$r->search.'%')
        ->orWhere('description', 'like', '%'.$r->search.'%');
    }

    return ParameterResource::collection($query->paginate(10));
  }

  public function delete(Request $r) {
    $data = [];
    $param = Parameter
      ::where('type', $r->type)
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
      ->where('type', $r->type);

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
}
