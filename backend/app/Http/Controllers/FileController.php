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
}
