<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExampleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(env('APP_ENV') === 'testing' && array_key_exists('HTTP_AUTHORIZATION', $request->server())){
        JWTAuth::setRequest($request);
      }
      return $next($request);
    }
}
