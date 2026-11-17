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
        // セッション切れでCSRFトークンが一致しない（419 PAGE EXPIRED）場合は
        // エラー画面を出さずに各画面へ戻し、メッセージを表示する
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            $except = ['_token', 'password', 'password_confirmation', 'current_password'];

            // 管理画面（/manage 配下）
            if ($request->is('manage', 'manage/*')) {
                // パスワードを忘れた方／パスワード再設定は同じ画面に戻す
                if ($request->routeIs('manage.password.email', 'manage.password.store')) {
                    return redirect()->back()
                        ->withInput($request->except($except))
                        ->with('session_expired', '一定時間操作がなかったため、画面の有効期限が切れました。もう一度入力してください。');
                }

                // それ以外（ログイン含む）は管理画面のログイン画面へ
                return redirect()->route('manage.login')
                    ->withInput($request->only('email'))
                    ->with('session_expired', '一定時間操作がなかったため、画面の有効期限が切れました。もう一度ログインしてください。');
            }

            // 予約フォーム（/form 配下）・会員側の画面にはログイン画面へは遷移させない。
            // 入力途中の予約内容はセッションと一緒に消えているため、予約の最初の画面へ戻す
            $message = '一定時間操作がなかったため、画面の有効期限が切れました。お手数ですが、最初から入力してください。';

            if ($request->is('form', 'form/*')) {
                return redirect()->route('form.reserves.entry_date')->with('failure', $message);
            }

            if ($request->is('reserves', 'reserves/*')) {
                return redirect()->route('reserves.entry_date')->with('failure', $message);
            }

            // その他の会員側の画面は元の画面へ戻す
            $message = '一定時間操作がなかったため、画面の有効期限が切れました。もう一度操作してください。';
            $response = redirect()->back()
                ->withInput($request->except($except))
                ->with('failure', $message);

            // 会員ログイン画面はエラー表示が name 項目のみのため、そちらにも出す
            return $request->is('login') ? $response->withErrors(['name' => $message]) : $response;
        });
    })->create();
