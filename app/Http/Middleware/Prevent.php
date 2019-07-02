<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Redis;
use Closure;

class Prevent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 用户ip
        $uip = $request->server('SERVER_ADDR');
        // 访问的接口
        $path = $request->server('REQUEST_URI');
        // 加密路由
        $mPath = substr(md5($path), 0, 10);
        $redis_key = 'str:' . $mPath . ':' . $uip;
        // incr   默认+1  返回次数
        $num = Redis::incr($redis_key);  //  储存   用户+访问的路由
        Redis::expire($redis_key, 60);
        // 一分钟 5 次   上限
        if ($num > 5) {
            // 5分钟
            Redis::expire($redis_key, 60 * 5);
            return response()->json(['message' => '访问过于频繁，请5分钟后再试'], 500);
        }
        return $next($request);
    }
}
