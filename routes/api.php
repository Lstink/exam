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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login','API\LoginController@checkLogin')->middleware('apiSign');

Route::any('unAuth',function(){
    return response() -> json(['message'=>'api_token invalid'],204);
})->name('unAuth');

Route::apiResource('user','API\ResponseController')->middleware('auth:api');

