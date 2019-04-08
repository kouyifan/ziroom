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

//获得token
$api->version('v1',
    function ($api) {
    $api->post('get_token','\App\Http\Controllers\TestController@get_token');
});

$api->version('v1', function ($api) {
    $api->get('testa','\App\Http\Controllers\TestController@test_db');
});

//$api->version('v1',
//    [
//        'prefix'=>'api',
//        'middleware'=>  ['api.auth'],
//        'namespace' =>  ['\App\Http\Controllers']
//    ],function($api){
//        $api->get('test','TestController@test_db');
//    }
//);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//$router->get('/test', 'TestController@test_db');


