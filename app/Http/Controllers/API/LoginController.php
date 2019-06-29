<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * @content 验证用户登录
     */
    public function checkLogin()
    {
        $username = request() -> username;
        $password = request() -> password;
        if (Auth::attempt(['username'=>$username,'password'=>$password])) {
            //获取token
            $token = Str::random(60);
            request()->user()->forceFill([
                'api_token' => hash('sha256',$token),
            ])->save();
            return response() -> json(['token'=>$token],200);
        }else{
            return response() -> json(['message'=>'error'],407);
        }
    }
}
