<?php

use App\Http\Controllers\Api\DealController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['api.token'])->group(function () {
    // 同期対象データの取得
    Route::get('/deals_for_sync', [DealController::class, 'getDealsForSync'])->name('deals_for_sync');

    // 同期完了後のフラグ更新
    Route::post('/update_after_sync', [DealController::class, 'updateAfterSync'])->name('update_after_sync');
});
