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

Route::get('/', function () {
    return view('welcome');
});

Route::get('shop',function(){
    return view('shop');
});


//注册接口
Route::post('api/register','API\RegisterController@register')->name('register');
//注册页面
Route::get('register',function(){
    return view('register');
});
//发送验证码
Route::post('sendCode','API\RegisterController@sendCode')->name('sendCode');
//模拟解密
Route::get('openEncrypt','API\EncryptController@openEncrypt');
