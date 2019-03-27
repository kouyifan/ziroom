<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::prefix('ziroom')->group(function() {
//    Route::get('/', 'ZiroomController@index');
//});
Route::namespace('\Modules\Ziroom\Http\Controllers')
    ->middleware('ziroom_web_common')
    ->group(function() {
//    首页
    Route::get('/', 'HomeController@index');
    Route::get('/list', 'ListController@index');
});

Route::prefix('ziroom')->namespace('\Modules\Ziroom\Http\Controllers')->group(function(){
   Route::get('/test','TestController@test');
});