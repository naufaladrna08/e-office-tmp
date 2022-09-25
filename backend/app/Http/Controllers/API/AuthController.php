<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use \Illuminate\Validation\Rule;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Roles;
use App\Models\ModelHasRoles;
use App\Classes\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\RoleResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class AuthController extends Controller {
  public function index(Request $r) {
    /*
     * field: field to sort
     * order: ascending or descending
     * page: page number
     */

    $sortField = [
      'id',
      'name',
      'username',
      'email',
      'created_at'
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, $sortField) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'name';
    }

    $query = User::orderBy($r->field, $r->order);
    
    if (!is_null($r->search)) {
      $query = $query
        ->where('name', 'like', '%'.$r->search.'%')
        ->orWhere('username', 'like', '%'.$r->search.'%');
    }

    return UserResource::collection($query->paginate(10));
  }

  public function register(Request $r) {
    $validator = Validator::make($r->all(), [
      'name' => 'required|string|max:255|min:3',
      'email' => 'required|email|string|max:255|unique:users',
      'username' => 'required|min:3|string|max:255|unique:users',
      'password' => 'required|string|max:255|min:3'
    ]);

    if (!$validator->fails()) {
      $user = User::create([
        'id' => $r->nipp,
        'name' => $r->name,
        'password' => Hash::make($r->password),
        'email' => $r->email,
        'username' => $r->username,
        'code_divisi' => 'JST-CRTD',
        'code_jabatan' => 'KRYWN',
        'last_action' => 'REGISTERED',
        'profile_photo_id' => 1,
        'cover_photo_id' => 2,
        'password_changed' => false
      ]);

      $user->assignRole('user');

    } else {
      return response()->json($validator->errors()); 
    }

    return Response::pretty(200, 'Success', 'Data has been created', $user);
  }

  public function update(Request $r) {
    $validator = Validator::make($r->all(), [
      'name' => 'required|string|max:255|min:3',
      'email' => [
        'required', 'email', Rule::unique('users')->ignore($r->old_nipp)
      ],
      'username' => [
        'required', 'string', Rule::unique('users')->ignore($r->old_nipp)
      ]
    ]);

    if (!$validator->fails()) {
      $user = User::where('id', $r->old_nipp)->first();
      
      if (!$user) {
        return Response::pretty(404, 'Failed', 'User is not found', null);
      }
      
      $user->id = $r->nipp;
      $user->name = $r->name;
      $user->username = $r->username;
      $user->email = $r->email;
      $user->last_action = 'UPDATE';
      $user->save();

    } else {
      return response()->json($validator->errors()); 
    }

    return Response::pretty(200, 'Success', 'Data has been created', $user);
  }

  public function delete(Request $r) {
    if ($r->old_nipp == Auth::id()) {
      return Response::pretty(404, 'Failed', 'Cannot edit yourself', 'SELF_DELETE');
    }

    $model = User::where('id', $r->old_nipp)->delete();
    $role  = Role::where('model_id', $r->old_nipp)->delete();

    return Response::pretty(200, 'Success', 'Data has been deleted', null);
  }

  public function login(Request $r) {
    $data = [];
    $credentials = null;

    if (is_numeric($r->username)) {
      $credentials = [
        'id' => $r->username,
        'password' => $r->password
      ];

      $user = User::where('id', $r->username)->first();
    } else {
      $credentials = $r->only('username', 'password');
      $user = User::where('username', $r->username)->first();
    }

    if (!Auth::attempt($credentials)) {
      return Response::pretty(404, 'Failed', 'Data tidak ditemukan', null);
    }
    
    if ($user) {
      $token = $user->createToken('auth_token')->plainTextToken;
      
      $data = response()->json([
        'code' => 200,
        'access_token' => $token,
        'token_type' => 'Bearer',
      ]);
    } else {
      $data = Response::pretty(404, 'Failed', 'Data tidak ditemukan', null);
    }

    return $data;
  }

  public function validate_login(Request $r) {
    $data = [];
    $credentials = null;
    \DB::enableQueryLog();

    if (is_numeric($r->username)) {
      $credentials = [
        'id' => $r->username,
        'password' => $r->password
      ];

      $user = User::where('id', $r->username)->first();
    } else {
      $credentials = $r->only('username', 'password');
      $user = User::where('username', $r->username)->first();
    }

    if ($user && Auth::attempt($credentials)) {
      $data = response()->json([
        'code' => 200
      ]);
    } else {
      $data = Response::pretty(404, 'Failed', 'Data tidak ditemukan', null);
    }

    return $data;
  }

  public function logout(Request $r) {
    if ($r->user()) {
      $r->user()->tokens()->delete();
    }

    return Response::pretty(200, 'Success', 'You are logged out');
  }

  public function userdata(Request $r) {
    $data = [];
    $role = ['role' => $r->user()->getRoleNames()[0]];

    $query = DB::table('users')
      ->select([
        'users.id AS uid',
        'users.*',
        'param_divisi.*',
        'param_jabatan.*',
        'profile.path AS profile_path',
        'cover.path AS cover_path',
      ])
      ->where('users.id', Auth::id())
      ->leftJoin('param_divisi', 'param_divisi.code_divisi', '=', 'users.code_divisi')
      ->leftJoin('param_jabatan', 'param_jabatan.code_jabatan', '=', 'users.code_jabatan')
      ->leftJoin('photos as profile', 'profile.id', '=', 'users.profile_photo_id')
      ->leftJoin('photos as cover', 'cover.id', '=', 'users.cover_photo_id')
      ->first();

    $query->profile_path = app('url')->asset('storage/' . $query->profile_path);
    $query->cover_path = app('url')->asset('storage/' . $query->cover_path);

    $arr = array_merge((array) $query, (array) $role);

    if ($query) {
      $data = Response::pretty(200, 'Success', 'Data found', $arr);
    } else {
      $data = Response::pretty(404, 'Failed', 'Data not found', null);
    }

    return $data;
  }

  public function user_divisi(Request $r) {
    /*
     * field: field to sort
     * order: ascending or descending
     * page: page number
     */

    $sortField = [
      'username',
      'nama_divisi'
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, $sortField) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'name';
    }

    $query = User::orderBy($r->field, $r->order);

    $query = DB::table('users')
      ->select([
        'users.id AS nipp',
        'users.username',
        'param_divisi.nama_divisi',
      ])
      ->leftJoin('param_divisi', 'param_divisi.code_divisi', '=', 'users.code_divisi');
      
    if (!is_null($r->search)) {
      $query = $query
        ->where('name', 'like', '%'.$r->search.'%')
        ->orWhere('username', 'like', '%'.$r->search.'%');
    }

    return $query->paginate($r->show == null ? 10 : $r->show);
  }

  public function user_jabatan(Request $r) {
    /*
     * field: field to sort
     * order: ascending or descending
     * page: page number
     */

    $sortField = [
      'username',
      'nama_jabatan'
    ];

    $orderField = ['asc', 'desc'];
    
    if ($r->order == null || $r->field == null || 
        !in_array($r->field, $sortField) || !in_array($r->order, $orderField)) {
      $r->order = 'asc';
      $r->field = 'name';
    }

    $query = User::orderBy($r->field, $r->order);

    $query = DB::table('users')
      ->select([
        'users.id AS nipp',
        'users.username',
        'param_jabatan.nama_jabatan',
      ])
      ->leftJoin('param_jabatan', 'param_jabatan.code_jabatan', '=', 'users.code_jabatan');
      
    if (!is_null($r->search)) {
      $query = $query
        ->where('name', 'like', '%'.$r->search.'%')
        ->orWhere('username', 'like', '%'.$r->search.'%');
    }

    return $query->paginate($r->show == null ? 10 : $r->show);
  }

  public function upload(Request $r) {
    $data = [];
    $file = Excel::import(new UserImport, $r->file('file')->store('files'));
    
    if ($file) {
      $data = [
        'code' => 200,
        'status' => 'Success',
        'message' => 'Data berhasil disimpan',
        'data' => null
      ];
    } else {
      $data = [
        'code' => 500,
        'status' => 'Failed',
        'message' => 'Data gagal disimpan',
        'data' => null
      ];
    }

    return response()->json($data);
  }

  public function update_password(Request $r) {
    $data = [];

    $user = User::where('id', Auth::id())->first();
    $user->password = Hash::make($r->password);
    $user->password_changed = true;

    if ($user->save()) {
      $data = [
        'code' => 200,
        'status' => 'Success',
        'message' => 'Data berhasil disimpan',
        'data' => null
      ];
    } else {
      $data = [
        'code' => 500,
        'status' => 'Failed',
        'message' => 'Data gagal disimpan',
        'data' => null
      ];
    }

    return response()->json($data);
  }

  public static function dd($data) {
    return response()->json($data); die;
  }

  public function dropdown_role(Request $r) {
    $data = [];
    
    $fields = ['id', 'name'];

    $query = DB::table('roles')
      ->select(['id AS nipp', 'name AS role', 'name AS username'])
      ->where('name', '<>', $r->existing);

    // if (!is_null($r->search)) {
    //   $query = $query 
    //     ->where('code_divisi', 'like', '%'.$r->search.'%')
    //     ->orWhere('nama_divisi', 'like', '%'.$r->search.'%');
    // }

    return RoleResource::collection($query->get());

    return response()->json($data);
  }

  public function get_data_role(Request $r) {
    $fields = ['rid', 'name', 'username', 'nipp'];
    $oderField = ['asc', 'desc'];

    if ($r->order == null || $r->field != null ||
       !in_array($r->order, $oderField) || !in_array($r->field, $fields)) {
      $r->order = 'asc';
      $r->field = 'code_divisi';
    }

    $query = DB::table('model_has_roles')
      ->select([
        'roles.id AS rid', 
        'roles.name AS role',
        'users.username',
        'users.id AS nipp'
      ])
      ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
      ->leftJoin('users', 'model_has_roles.model_id', '=', 'users.id')
      ->orderBy($r->field, $r->order);

    if (!is_null($r->search)) {
      $query = $query 
        ->where('users.username', 'like', '%'.$r->search.'%')
        ->orWhere('users.id', 'like', '%'.$r->search.'%');
    }

    return RoleResource::collection($query->paginate(10));
  }

  public function update_role(Request $r) {
    $role = Roles::where('name', $r->role)->first();

    $data = ModelHasRoles::where('model_id', $r->nipp)->first();
    $data->role_id = $role->id;
    $data->timestamps = false;

    if (!$data->save()) {
      return Response::pretty(500, 'Failed', 'Internal Server Error', null);
    }

    return Response::pretty(200, 'Success', 'Data has been saved', $data);
  }
}
