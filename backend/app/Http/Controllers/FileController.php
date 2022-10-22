<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Auth;

class FileController extends Controller {
  /*
   * Show all files archive for authenticated user.
   * 
   * @return json contains all files archive 
   */
  public function index() {
    $data = [];

    $files = File::where('uid', Auth::id())->get();

    if ($files) {
      $data['code'] = 200;
      $data['message'] = 'Data ditemukan';
      $data['data'] = $files;
    } else {
      $data['code'] = 404;
      $data['message'] = 'Data tidak ditemukan';
      $data['data'] = null;
    }

    return response()->json($data);
  }

  public function upload(Request $r) {
    $data = [];

    if ($r->file()) {
      /* Move file */
      // $r->validate([
      //   'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf,7z,zip|max:10240'
      // ]);
      
      $path = public_path('backend/archive/');
      $name = $r->file->getClientOriginalName();
      $r->file->move($path, $name);
   
      /* Create data in database */
      $model = new File();
      $model->uid = Auth::id();
      $model->name = $name;
      $model->path = asset('backend/archive/'. $name);
      $model->extension = $r->file->getClientOriginalExtension();

      if ($model->save()) {
        $data['code'] = 200;
        $data['status'] = 'ok';
      } else {
        $data['code'] = 500;
        $data['status'] = 'failed';
      }
    } else {
      $data['code'] = 403;
      $data['status'] = 'failed';
      $data['message'] = 'Please provide file';
    }

    return response()->json($data);
  }
}
