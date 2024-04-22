<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => \App\Http\Middleware\OnlyGuestAllowedMiddleware::class
        ]);

        $middleware->redirectGuestsTo(function(Request $request) {
            if (request()->routeIs('manage.*')) {
                return $request->expectsJson() ? null : route('manage.login');
            }

            return $request->expectsJson() ? null : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
