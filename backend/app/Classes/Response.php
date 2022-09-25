<?php

namespace App\Classes;
use Illuminate\Support\Str;

class Response {
  public static function pretty($code = 200, $status = '', $message = '', $data = null) {
    $data = [
      'code' => $code,
      'status' => $status,
      'message' => $message,
      'data' => $data
    ];

    return response()->json($data);
  }

  public static function debugSql($query = null) {
    return response()->json(
      Str::replaceArray('?', $query->getBindings(), $query->toSql())
    );
  }
};