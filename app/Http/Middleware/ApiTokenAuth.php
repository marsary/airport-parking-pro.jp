<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        // allowed_ips が指定されている場合のみ ipアドレスをチェックする。
        $ips = config('services.internal_api.allowed_ips');
        if (!empty($ips) && !in_array($request->ip(), $ips)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        // envで管理する場合
        if (!$token || $token !== config('services.internal_api.token')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
