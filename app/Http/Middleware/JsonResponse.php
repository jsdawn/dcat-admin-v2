<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonResponse
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
        // api接口报错或异常时返回JSON格式数据
        // auth验证时使用route name为login的路由代理
        if ($request->is('api/*') && !$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
