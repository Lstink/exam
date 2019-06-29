<?php

namespace App\Http\Middleware;

use Closure;

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
        $nonceStr = $request -> nonceStr;
        $signature = $request -> signature;
        $timestamps = $request -> timestamps;
        $apiKey = env('APIKEY');
        //字典排序
        $arr = [$nonceStr,$timestamps,$apiKey];
        sort($arr,SORT_STRING);
        $sign = sha1(implode($arr));
        echo $sign;
        dump($signature);
        if ($sign !== $signature) {
            return response() -> json(['message'=>'signature invalid'],400);
        }
        return $next($request);
    }
}
