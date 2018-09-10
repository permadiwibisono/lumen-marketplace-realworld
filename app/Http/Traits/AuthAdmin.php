<?php

namespace App\Http\Traits;

trait AuthAdmin {
  
  protected $guard;

  protected function authAdmin()
  {
    return auth($this->guard);
  }

  private function setGuard()
  {
    $this->guard = env('AUTH_GUARD_DASHBOARD', 'dashboard');
  }
}
