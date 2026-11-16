<?php

use App\Http\Controllers\Manage\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Manage\Auth\NewPasswordController;
use App\Http\Controllers\Manage\Auth\PasswordController;
use App\Http\Controllers\Manage\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

// 管理用のルーティング
Route::prefix('manage')->name('manage.')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        // パスワードを忘れた方
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');

        // パスワード変更
        Route::get('password', [PasswordController::class, 'edit'])
                    ->name('password.edit');

        Route::put('password', [PasswordController::class, 'update'])
                    ->name('password.update');
    });

});
