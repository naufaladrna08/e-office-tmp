<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model {
  use HasFactory;

  protected $fillable = ['type', 'type_id', 'description', 'status'];

  static function insert($type, $type_id, $description, $status) {
    $data = DB::table('logs')->insert([
      'type' => $type,
      'type_id' => $type_id,
      'description' => date('Y-m-d H:i:s') . ' - ' . $description,
      'status' => $status
    ]);

    return $data;
  }
}
