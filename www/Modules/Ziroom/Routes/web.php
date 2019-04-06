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

Route::prefix('ziroom')
    ->middleware('ziroom_web_common')
    ->namespace('\Modules\Ziroom\Http\Controllers')
    ->group(function() {

    Route::get('/home', 'HomeController@index');
    Route::get('/list', 'ListController@index');
    Route::get('/test','TestController@test');
//    登录
    Route::get('/login','User\LoginController@index')->name('ziroom_user_login');
    Route::post('/login','User\LoginController@login')->name('ziroom_user_login_post');
    Route::post('/logout','User\LoginController@logout')->name('ziroom_user_logout');
//    注册
    Route::get('/register','User\RegisterController@index')->name('ziroom_user_register');
    Route::post('/register','User\RegisterController@register')->name('ziroom_user_register_post');
});

