<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(User::class, 5)->create()->each(function ($user) {
      $user->emailToken()->create([
        '_token' => str_random(32),
        'expired_at' => \Carbon\Carbon::now()->addDays(14)
      ]);
    });;
  }
}
