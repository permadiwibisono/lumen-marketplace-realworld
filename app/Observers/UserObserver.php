<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
  /**
   * Handle to the User "created" event.
   *
   * @param  \App\User  $user
   * @return void
   */
  public function created(User $user)
  {
    $user->emailToken()->create([
      '_token' => str_random(32),
      'expired_at' => \Carbon\Carbon::now()
    ]);
  }

  /**
   * Handle the User "updated" event.
   *
   * @param  \App\User  $user
   * @return void
   */
  public function updated(User $user)
  {
      //
  }

  /**
   * Handle the User "deleted" event.
   *
   * @param  \App\User  $user
   * @return void
   */
  public function deleted(User $user)
  {
      //
  }
}
