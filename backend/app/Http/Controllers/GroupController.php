<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\DB;
use App\Classes\Response;

class GroupController extends Controller {
  public function index(Request $r) {
    $sortField = [
      'id',
      'name',
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, $sortField) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'name';
    }

    $query = DB::table(DB::raw('groups aa'))
      ->select(
        'aa.*', 
        DB::raw('(SELECT COUNT(*) FROM group_members WHERE group_id=aa.id) AS COUNT')
      );
    
    // if (!is_null($r->search)) {
    //   $query = $query
    //     ->where('name', 'like', '%'.$r->search.'%')
    //     ->orWhere('username', 'like', '%'.$r->search.'%');
    // }

    return GroupResource::collection($query->paginate(10));
  }

  public function create(Request $r) {
    $data = [];

    $model = Group::create([
      'name' => $r->name,
      'is_active' => true
    ]);

    if ($model) {
      $data = Response::pretty(200, 'Success', 'Data berhasil dibuat', $model);
    } else {
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return response()->json($data);
  }

  public function outside(Request $r) {
    $sortField = [
      'id',
      'name',
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, $sortField) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'name';
    }

    $filtered['gid'] = htmlentities($r->gid);

    $query = DB::table(DB::raw('users'))
      ->select('*')
      ->whereNOTIn('id', GroupMember::where('group_id', $filtered['gid'])->get('user_id'))
      ->get();

    return response()->json($query);
  }

  public function inside(Request $r) {
    $sortField = [
      'id',
      'name',
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, $sortField) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'name';
    }

    $filtered['gid'] = htmlentities($r->gid);

    $query = DB::table(DB::raw('users'))
      ->select('*')
      ->wherein('id', GroupMember::where('group_id', $filtered['gid'])->get('user_id'))
      ->get();

    return response()->json($query);
  }

  public function assign(Request $r) {
    $data = [];
    $model = GroupMember::create([
      'group_id' => htmlentities($r->gid),
      'user_id' => htmlentities($r->uid) 
    ]);

    if ($model) {
      $data = Response::pretty(200, 'Success', 'Data berhasil dibuat', $model);
    } else {
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return response()->json($data);
  }

  public function kick(Request $r) {
    $data = [];
    $model = GroupMember::where([
      'group_id' => htmlentities($r->gid),
      'user_id' => htmlentities($r->uid) 
    ]);

    if ($model->delete()) {
      $data = Response::pretty(200, 'Success', 'Data berhasil dihapus', $model);
    } else {
      $data = Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return response()->json($data);
  }

  public function dropdown(Request $r) {
    $data = [];

    $query = DB::table('groups')
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
}
