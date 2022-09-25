<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParamJabatan extends Model {
  use HasFactory;

  protected $table = 'param_jabatan';
  protected $fillable = [
    'id', 'code_jabatan', 'nama_jabatan'
  ];
}
