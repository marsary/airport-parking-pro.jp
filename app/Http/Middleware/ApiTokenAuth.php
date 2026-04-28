<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    private $allowedIps = ['localhost', '127.0.0.1'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!in_array($request->ip(), $this->allowedIps)) {
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
