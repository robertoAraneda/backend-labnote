<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('units', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('description')->unique();
      $table->integer('state_id');
      $table->integer('user_created_id');
      $table->integer('user_updated_id');
      $table->integer('user_deleted_id')->nullable();
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('state_id')->references('id')->on('states');

      $table->foreign('user_created_id')->references('id')->on('users');

      $table->foreign('user_updated_id')->references('id')->on('users');

      $table->foreign('user_deleted_id')->references('id')->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('units');
  }
}
