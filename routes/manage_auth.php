<?php

use App\Http\Controllers\Manage\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// 管理用のルーティング
Route::prefix('manage')->name('manage.')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');
    });

});
