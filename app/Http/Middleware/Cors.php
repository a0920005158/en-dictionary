<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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
        return $next($request)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization')
        ->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');

        // 設定允許訪問的domain address
        $domains = [
            env('SPA_URL_RED_EN', ""),
            env('SPA_URL_RED', ""),
            env('SPA_URL_RWD', ""),
            "localhost:4200"
        ];

        // 判斷 request 的 header 中是否包含 'ORIGIN'
        if (isset($request->server()['HTTP_ORIGIN'])) {
            $origin = $request->server()['HTTP_ORIGIN'];
            // 如果 origin 帶有 http, https 則把它濾掉
            $pattern = "";
            if (preg_match('#^https?://#', $origin)) {
                $pattern = preg_replace('#^https?://#', '', $origin);
            }

            if (in_array($pattern, $domains)) {
                //設定 response header 的信息
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization')
                    ->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
            }
        }
        return $next($request);
    }
}
