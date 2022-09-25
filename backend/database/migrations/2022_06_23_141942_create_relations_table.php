<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   * 
   * For example: If user (ID: 10) has created mail (ID: 2) to other user (ID: 11),
   * there will be: 1, 'RECEIVER', 2, 10, 11. 
   *
   * @return void
   */
  public function up() {
    Schema::create('relations', function (Blueprint $table) {
      $table->id();
      $table->string('type', 8);
      $table->integer('item');
      $table->integer('from');
      $table->integer('to');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('relations');
  }
};
