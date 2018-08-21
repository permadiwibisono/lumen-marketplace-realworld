<?php

namespace App\Http\Traits;

trait AuthAdmin {
  
  protected $guard;

  protected function authAdmin()
  {
    return auth($this->guard);
  }
}
