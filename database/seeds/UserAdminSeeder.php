<?php

use App\Models\UserAdmin;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('user_admin')->truncate();
    UserAdmin::create([
      'email' => 'pewe@company.com',
      'password' => app('hash')->make('secret')
    ]);
    UserAdmin::create([
      'email' => 'administrator@company.com',
      'password' => app('hash')->make('secret')
    ]);
    UserAdmin::create([
      'email' => 'developer@company.com',
      'password' => app('hash')->make('secret')
    ]);
    factory(UserAdmin::class, 5)->create();
  }
}
