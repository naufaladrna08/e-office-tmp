<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * - type (parameter type), tipe dari parameter.
   * - code (parameter code), code dari parameter. setiap parameter 
   * mempunyai code yg berbeda (unique).
   * - name (parameter name), bisa apa aja, nama lebih diutamakan.
   * - description (parameter description), deskripsi parameter.
   * 
   * @return void
   */
  public function up() {
    Schema::create('parameter', function (Blueprint $table) {
      $table->id();
      $table->string('type', 255);
      $table->string('code', 255);
      $table->string('name', 255);
      $table->string('description', 255);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('parameter');
  }
};
