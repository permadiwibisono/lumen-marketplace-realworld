<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdminTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_admin', function (Blueprint $table) {
      $table->increments('id');
      $table->string('email', 80)->unique();
      $table->string('full_name', 100)->nullable();
      $table->string('password', 255)->nullable();
      $table->enum('gender', ['M','F'])->default('M');
      $table->dateTime('birth_at')->nullable();
      $table->boolean('is_verified_email')->default(false);
      $table->boolean('is_supended')->default(false);
      $table->softDeletes();
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
    Schema::dropIfExists('user_admin');
  }
}
