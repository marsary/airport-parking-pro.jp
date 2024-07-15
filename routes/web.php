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
        Route::get('/deals/search_export', [\App\Http\Controllers\Manage\DealsController::class, 'searchExport'])->name('deals.search_export');

        Route::get('deals/{deal}/entry_date', [\App\Http\Controllers\Manage\DealsController::class, 'entryDate'])->name('deals.entry_date');
        Route::put('deals/{deal}/entry_date', [\App\Http\Controllers\Manage\DealsController::class, 'putEntryDate'])->name('deals.put_entry_date');

        Route::resource('deals', \App\Http\Controllers\Manage\DealsController::class);
        Route::put('deals/{deal}/update_goods', [\App\Http\Controllers\Manage\DealsController::class, 'updateGoods'])->name('deals.update_goods');
        Route::put('deals/{deal}/update_memo', [\App\Http\Controllers\Manage\DealsController::class, 'updateMemo'])->name('deals.update_memo');
        Route::put('deals/{deal}/unload', [\App\Http\Controllers\Manage\DealsController::class, 'unload'])->name('deals.unload');
        Route::resource('registers', \App\Http\Controllers\Manage\RegistersController::class);
        Route::get('reserves/entry_date', [\App\Http\Controllers\Manage\ReservesController::class, 'entryDate'])->name('reserves.entry_date');
        Route::get('reserves/entry_info', [\App\Http\Controllers\Manage\ReservesController::class, 'entryInfo'])->name('reserves.entry_info');
        Route::post('reserves/entry_date', [\App\Http\Controllers\Manage\ReservesController::class, 'postEntryDate'])->name('reserves.post_entry_date');
        Route::post('reserves/entry_info', [\App\Http\Controllers\Manage\ReservesController::class, 'postEntryInfo'])->name('reserves.post_entry_info');
        Route::get('reserves/confirm', [\App\Http\Controllers\Manage\ReservesController::class, 'confirm'])->name('reserves.confirm');
        Route::resource('reserves', \App\Http\Controllers\Manage\ReservesController::class);

        Route::get('marketing/graph/inventory', [\App\Http\Controllers\Manage\Marketing\GraphController::class, 'inventory'])->name('marketing.graph.inventory');
        // Route::get('marketing/graph/reservation', [\App\Http\Controllers\Manage\Marketing\GraphController::class, 'reservation'])->name('marketing.graph.reservation');
        Route::get('marketing/reservation_graph', [\App\Http\Controllers\Manage\Marketing\ReservationGraphController::class, 'index'])->name('marketing.reservation_graph.index');
        Route::get('marketing/reservation_graph/chart_by_day', [\App\Http\Controllers\Manage\Marketing\ReservationGraphController::class, 'chartByDay'])->name('marketing.reservation_graph.chart_by_day');
        Route::get('marketing/reservation_graph/chart_by_hour', [\App\Http\Controllers\Manage\Marketing\ReservationGraphController::class, 'chartByHour'])->name('marketing.reservation_graph.chart_by_hour');

    });

});

// 予約フォーム用のルーティング
Route::prefix('form')->name('form.')->group(function () {
    Route::get('reserves/entry_date', [\App\Http\Controllers\Form\ReservesController::class, 'entryDate'])->name('reserves.entry_date');
    Route::get('reserves/entry_info', [\App\Http\Controllers\Form\ReservesController::class, 'entryInfo'])->name('reserves.entry_info');
    Route::get('reserves/entry_car', [\App\Http\Controllers\Form\ReservesController::class, 'entryCar'])->name('reserves.entry_car');
    Route::get('reserves/option_select', [\App\Http\Controllers\Form\ReservesController::class, 'optionSelect'])->name('reserves.option_select');
    Route::post('reserves/entry_date', [\App\Http\Controllers\Form\ReservesController::class, 'postEntryDate'])->name('reserves.post_entry_date');
    Route::post('reserves/entry_info', [\App\Http\Controllers\Form\ReservesController::class, 'postEntryInfo'])->name('reserves.post_entry_info');
    Route::post('reserves/entry_car', [\App\Http\Controllers\Form\ReservesController::class, 'postEntryCar'])->name('reserves.post_entry_car');
    Route::post('reserves/option_select', [\App\Http\Controllers\Form\ReservesController::class, 'postOptionSelect'])->name('reserves.post_option_select');
    Route::get('reserves/confirm', [\App\Http\Controllers\Form\ReservesController::class, 'confirm'])->name('reserves.confirm');
    Route::get('reserves/complete', [\App\Http\Controllers\Form\ReservesController::class, 'complete'])->name('reserves.complete');
    Route::resource('reserves', \App\Http\Controllers\Form\ReservesController::class);

});


Route::get('reserves/entry_date', [\App\Http\Controllers\Member\ReservesController::class, 'entryDate'])->name('reserves.entry_date');
Route::get('reserves/entry_info', [\App\Http\Controllers\Member\ReservesController::class, 'entryInfo'])->name('reserves.entry_info');
Route::get('reserves/entry_car', [\App\Http\Controllers\Member\ReservesController::class, 'entryCar'])->name('reserves.entry_car');
Route::get('reserves/option_select', [\App\Http\Controllers\Member\ReservesController::class, 'optionSelect'])->name('reserves.option_select');
Route::post('reserves/entry_date', [\App\Http\Controllers\Member\ReservesController::class, 'postEntryDate'])->name('reserves.post_entry_date');
Route::post('reserves/entry_info', [\App\Http\Controllers\Member\ReservesController::class, 'postEntryInfo'])->name('reserves.post_entry_info');
Route::post('reserves/entry_car', [\App\Http\Controllers\Member\ReservesController::class, 'postEntryCar'])->name('reserves.post_entry_car');
Route::post('reserves/option_select', [\App\Http\Controllers\Member\ReservesController::class, 'postOptionSelect'])->name('reserves.post_option_select');
Route::get('reserves/confirm', [\App\Http\Controllers\Member\ReservesController::class, 'confirm'])->name('reserves.confirm');
Route::get('reserves/complete', [\App\Http\Controllers\Member\ReservesController::class, 'complete'])->name('reserves.complete');
Route::resource('reserves', \App\Http\Controllers\Member\ReservesController::class);

Route::get('car_makers/{car_maker_id}/cars', [\App\Http\Controllers\CarMakersController::class, 'cars'])->name('car_makers.cars');
Route::get('arrival_flights/get_info', [\App\Http\Controllers\ArrivalFlightsController::class, 'getInfo'])->name('arrival_flights.get_info');
Route::get('prices/table', [\App\Http\Controllers\PricesController::class, 'table'])->name('prices.table');

Route::get('members/load_member', [\App\Http\Controllers\MembersController::class, 'loadMember'])->name('members.load_member');

Route::get('calendar/load_dates', [\App\Http\Controllers\CalendarController::class, 'loadDates'])->name('calendar.load_dates');
Route::get('calendar/unload_dates', [\App\Http\Controllers\CalendarController::class, 'unloadDates'])->name('calendar.unload_dates');
Route::get('calendar/load_hours', [\App\Http\Controllers\CalendarController::class, 'loadHours'])->name('calendar.load_hours');

require __DIR__.'/manage_auth.php';
require __DIR__.'/member_auth.php';
require __DIR__.'/form_auth.php';
