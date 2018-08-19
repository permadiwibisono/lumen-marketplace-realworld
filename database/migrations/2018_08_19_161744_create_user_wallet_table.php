<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWalletTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_wallet', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');            
      $table->foreign('user_id')->references('id')->on('user');
      $table->decimal('amount', 15,2)->default();
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
    Schema::dropIfExists('user_wallet');
  }
}
