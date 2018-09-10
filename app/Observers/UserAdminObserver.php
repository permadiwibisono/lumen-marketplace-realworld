<?php

namespace App\Observers;

use App\Models\UserAdmin;

class UserAdminObserver
{
  /**
   * Handle to the User "created" event.
   *
   * @param  \App\Models\UserAdmin  $user
   * @return void
   */
  public function created(UserAdmin $user)
  {
    $user->emailToken()->create([
      '_token' => str_random(32),
      'expired_at' => \Carbon\Carbon::now()
    ]);
  }

  /**
   * Handle the User "updated" event.
   *
   * @param  \App\Models\UserAdmin  $user
   * @return void
   */
  public function updated(UserAdmin $user)
  {
      //
  }

  /**
   * Handle the User "deleted" event.
   *
   * @param  \App\Models\UserAdmin  $user
   * @return void
   */
  public function deleted(UserAdmin $user)
  {
      //
  }
}
