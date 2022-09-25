<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParamDivisi extends Model {
  use HasFactory;

  protected $table = 'param_divisi';
  protected $fillable = [
    'id', 'code_divisi', 'nama_divisi'
  ];
}
