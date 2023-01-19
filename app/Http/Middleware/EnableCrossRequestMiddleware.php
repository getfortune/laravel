<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// 处理跨域第一步
class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        //允许进行跨域请求的地址
        $allow_origin = [
            'http://localhost:3000', // 前端项目的地址
            'http://127.0.0.1:3000'  // localhost 和 127.0.0.1 有区别暂时还不知道为什么。
        ];
        if (in_array($origin, $allow_origin)) {
            $response->header('Access-Control-Allow-Origin',$origin);
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }
}
