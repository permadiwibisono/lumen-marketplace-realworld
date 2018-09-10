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
    UserAdmin::where('email', 'pewe@company.com')->firstOrCreate([
      'email' => 'pewe@company.com',
      'password' => app('hash')->make('secret')
    ]);
    UserAdmin::where('email', 'administrator@company.com')->firstOrCreate([
      'email' => 'administrator@company.com',
      'password' => app('hash')->make('secret')
    ]);
    UserAdmin::where('email', 'developer@company.com')->firstOrCreate([
      'email' => 'developer@company.com',
      'password' => app('hash')->make('secret')
    ]);
    UserAdmin::where('email', 'user_test@company.com')->firstOrCreate([
      'email' => 'user_test@company.com',
      'password' => app('hash')->make('secret')
    ]);
    factory(UserAdmin::class, 5)->create();
  }
}
