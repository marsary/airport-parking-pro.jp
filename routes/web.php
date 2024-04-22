<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// 管理用のルーティング
Route::prefix('manage')->name('manage.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Manage\TopController::class, 'index'])->name('home');
        Route::get('/deals/search', [\App\Http\Controllers\Manage\DealsController::class, 'search'])->name('deals.search');
        Route::resource('deals', \App\Http\Controllers\Manage\DealsController::class);
        Route::resource('registers', \App\Http\Controllers\Manage\RegistersController::class);
        Route::get('reserves/entry_date', [\App\Http\Controllers\Manage\ReservesController::class, 'entryDate'])->name('reserves.entry_date');
        Route::get('reserves/entry_info', [\App\Http\Controllers\Manage\ReservesController::class, 'entryInfo'])->name('reserves.entry_info');
        Route::get('reserves/confirm', [\App\Http\Controllers\Manage\ReservesController::class, 'confirm'])->name('reserves.confirm');
        Route::resource('reserves', \App\Http\Controllers\Manage\ReservesController::class);
    });

});

Route::get('reserves/entry_date', [\App\Http\Controllers\Member\ReservesController::class, 'entryDate'])->name('reserves.entry_date');
Route::get('reserves/entry_info', [\App\Http\Controllers\Member\ReservesController::class, 'entryInfo'])->name('reserves.entry_info');
Route::get('reserves/entry_car', [\App\Http\Controllers\Member\ReservesController::class, 'entryCar'])->name('reserves.entry_car');
Route::get('reserves/option_select', [\App\Http\Controllers\Member\ReservesController::class, 'optionSelect'])->name('reserves.option_select');
Route::get('reserves/confirm', [\App\Http\Controllers\Member\ReservesController::class, 'confirm'])->name('reserves.confirm');
Route::get('reserves/complete', [\App\Http\Controllers\Member\ReservesController::class, 'complete'])->name('reserves.complete');
Route::resource('reserves', \App\Http\Controllers\Member\ReservesController::class);




require __DIR__.'/manage_auth.php';
require __DIR__.'/member_auth.php';
