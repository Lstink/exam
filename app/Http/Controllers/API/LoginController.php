<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    /**
     * @content 验证用户登录
     */
    public function checkLogin()
    {
        $phone = decryptCBC(request() -> get('phone'));
        $password = decryptCBC(request() -> get('password'));
        if (Auth::attempt(['phone'=>$phone,'password'=>$password])) {
            //获取token
            $token = Str::random(60);
            request()->user()->forceFill([
                'api_token' => hash('sha256',$token),
            ])->save();
            //获取认证对象，ｍｄ５加密存入redis
            $user = md5(Auth::user());
            Redis::setex('user_'.$user,7200,$token);
            return response() -> json(['token'=>$token],200);
        }else{
            return response() -> json(['message'=>'error'],407);
        }
    }
}
