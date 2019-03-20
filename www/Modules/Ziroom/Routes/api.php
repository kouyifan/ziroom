<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/ziroom', function (Request $request) {
    return $request->user();
});
Route::prefix('es')->group(function(){
    Route::get('/create_index', 'ElasticsearchController@create_index');
    Route::get('/save_data', 'ElasticsearchController@save_data');
    Route::post('/find_data', 'ElasticsearchController@find_data');
    Route::post('/update_data', 'ElasticsearchController@update_data');
});

