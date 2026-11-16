<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => \App\Http\Middleware\OnlyGuestAllowedMiddleware::class,
            'api.token' => \App\Http\Middleware\ApiTokenAuth::class,
        ]);

        $middleware->redirectGuestsTo(function(Request $request) {
            if (request()->routeIs('manage.*')) {
                return $request->expectsJson() ? null : route('manage.login');
            }

            return $request->expectsJson() ? null : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 管理画面：セッション切れでCSRFトークンが一致しない（419 PAGE EXPIRED）場合は
        // エラー画面を出さずに画面へ戻し、メッセージを表示する
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson() || ! $request->is('manage', 'manage/*')) {
                return null;
            }

            $message = '一定時間操作がなかったため、画面の有効期限が切れました。もう一度入力してください。';

            // パスワードを忘れた方／パスワード再設定は同じ画面に戻す
            if ($request->routeIs('manage.password.email', 'manage.password.store')) {
                return redirect()->back()
                    ->withInput($request->except('_token', 'password', 'password_confirmation'))
                    ->with('session_expired', $message);
            }

            // それ以外（ログイン含む）はログイン画面へ
            return redirect()->route('manage.login')
                ->withInput($request->only('email'))
                ->with('session_expired', '一定時間操作がなかったため、画面の有効期限が切れました。もう一度ログインしてください。');
        });
    })->create();
