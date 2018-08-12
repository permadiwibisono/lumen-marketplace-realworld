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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function($api) use($router){
  $api->get('/', function() use($router){
    return response()->json(['message'=>'MarketplaceS\'s API','status_code'=>200,'framework'=>$router->app->version()],200);
  }); 
});
$api->version('v2', function($api) use($router){
  $api->get('/', function() use($router){
    return response()->json(['message'=>'MarketplaceS\'s API v2','status_code'=>200,'framework'=>$router->app->version()],200);
  }); 
});
