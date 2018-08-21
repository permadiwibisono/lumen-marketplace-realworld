<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['namespace' => 'App\Http\Controllers\Dashboards', 'prefix' => 'dashs', 'middleware' => 'auth:dashboard'], function($api) use($router){
  $api->get('/', function() use($router){
    return response()->json(['message'=>'Marketplace\'s Dashboard API','status_code'=>200,'framework'=>$router->app->version()],200);
  });
});
