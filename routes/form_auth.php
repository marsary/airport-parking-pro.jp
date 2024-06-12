<?php

use App\Http\Controllers\Form\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// 顧客用のルーティング
Route::prefix('form')->name('form.')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth:members')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');
    });
});
