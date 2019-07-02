<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //验证签名
        $data = request() -> except('signature','api_token');
        //解密
        foreach ($data as $key => $value) {
            $data[$key] = decryptCBC($value);
        }
        // dd($data);
        if (empty($data)) {
            return response() -> json(['message'=>'api_token invalid'],500);
        }
        $user = md5(Auth::user());
        $api_token = Redis::get('user_'.$user);
        if ($api_token != request('api_token')) {
            return response() -> json(['message'=>'api_token invalid'],500);
        }
        $signature = request() -> signature;
        $apiKey = env('APIKEY');
        $data['key'] = $apiKey;
        //字典排序
        sort($data,SORT_STRING);
        $sign = sha1(implode($data));
        // dd($sign);
        if ($sign !== $signature) {
            return response() -> json(['message'=>'signature invalid'],400);
        }
        // echo 5;
        return $next($request);
    }
}
