<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailTokenTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_email_token', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');
      $table->string('type');
      $table->string('_token', 100);
      $table->dateTime('expired_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_email_token');
  }
}
