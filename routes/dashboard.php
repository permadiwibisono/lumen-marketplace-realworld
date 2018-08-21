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
$api->version('v1', ['namespace' => 'App\Http\Controllers\Dashboards', 'prefix' => 'dashs'], function($api) use($router){
  $api->group(['prefix' => 'auth'], function($api) {
    $api->post('login', 'AuthController@login');
    $api->post('logout', 'AuthController@logout');
    $api->post('refresh', 'AuthController@refresh');
    $api->get('me', 'AuthController@me');
  });

  $api->group(['prefix' => '/', 'middleware' => 'auth:dashboard'], function($api) use($router){
    $api->get('/', function() use($router){
      return response()->json(['message'=>'Marketplace\'s Dashboard API','status_code'=>200,'framework'=>$router->app->version()],200);
    });
  });
});
