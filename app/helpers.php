<?php
// use Illuminate\Support\Facades\Auth;

if (! function_exists('auth')) {
  /**
   * Get auth instances.
   *
   * 
   * @return mixed|Illuminate\Support\Facades\Auth
   */
  function auth($guard = null)
  {
    if(is_null($guard))
      $guard = env('AUTH_GUARD', 'api');
    return app('auth')->guard($guard);
  }
}