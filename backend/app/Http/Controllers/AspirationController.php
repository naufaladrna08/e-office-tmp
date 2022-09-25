<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Classes\Response;
use Illuminate\Http\Request;
use Validator;

class AspirationController extends Controller {
  public function create(Request $r) {
    $validator = Validator::make($r->all(), [
      'content' => 'string|required|min:3'
    ]);

    if (!$validator->fails()) {
      $model = Aspiration::create([
        'content' => $r->content
      ]);
    } else {
      return response()->json($validator->errors()); 
    }

    return Response::pretty(200, 'Success', 'Data has been created', $model);
  }

  public function readAll() {
    $dataall = Aspiration::all();

    if (!empty($dataall)) {
      $data = Response::pretty(200, 'Success', 'Data available', $dataall);
    } else {
      $data = Response::pretty(404, 'Failed', 'Data not available', null);
    }

    return $data;
  }
}
