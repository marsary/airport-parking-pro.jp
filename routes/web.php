<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// 管理用のルーティング
Route::prefix('manage')->name('manage.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Manage\TopController::class, 'index'])->name('home');
        Route::get('/marketing', [\App\Http\Controllers\Manage\TopController::class, 'marketing'])->name('marketing');
        Route::get('/deals/search', [\App\Http\Controllers\Manage\DealsController::class, 'search'])->name('deals.search');
        Route::get('/deals/search_export', [\App\Http\Controllers\Manage\DealsController::class, 'searchExport'])->name('deals.search_export');

        Route::get('deals/{deal}/entry_date', [\App\Http\Controllers\Manage\DealsController::class, 'entryDate'])->name('deals.entry_date');
        Route::put('deals/{deal}/entry_date', [\App\Http\Controllers\Manage\DealsController::class, 'putEntryDate'])->name('deals.put_entry_date');

        Route::put('deals/{deal}/update_goods', [\App\Http\Controllers\Manage\DealsController::class, 'updateGoods'])->name('deals.update_goods');
        Route::put('deals/{deal}/update_memo', [\App\Http\Controllers\Manage\DealsController::class, 'updateMemo'])->name('deals.update_memo');
        Route::put('deals/{deal}/unload', [\App\Http\Controllers\Manage\DealsController::class, 'unload'])->name('deals.unload');
        Route::resource('deals', \App\Http\Controllers\Manage\DealsController::class);
        Route::resource('registers', \App\Http\Controllers\Manage\RegistersController::class);
        Route::get('reserves/entry_date', [\App\Http\Controllers\Manage\ReservesController::class, 'entryDate'])->name('reserves.entry_date');
        Route::get('reserves/entry_info', [\App\Http\Controllers\Manage\ReservesController::class, 'entryInfo'])->name('reserves.entry_info');
        Route::post('reserves/entry_date', [\App\Http\Controllers\Manage\ReservesController::class, 'postEntryDate'])->name('reserves.post_entry_date');
        Route::post('reserves/entry_info', [\App\Http\Controllers\Manage\ReservesController::class, 'postEntryInfo'])->name('reserves.post_entry_info');
        Route::get('reserves/confirm', [\App\Http\Controllers\Manage\ReservesController::class, 'confirm'])->name('reserves.confirm');
        Route::resource('reserves', \App\Http\Controllers\Manage\ReservesController::class);

        Route::get('ledger', [\App\Http\Controllers\Manage\LedgerController::class, 'index'])->name('ledger.index');
        Route::get('ledger/inventories', [\App\Http\Controllers\Manage\LedgerController::class, 'inventories'])->name('ledger.inventories');
        Route::get('ledger/agency_sales_lists', [\App\Http\Controllers\Manage\LedgerController::class, 'agencySalesLists'])->name('ledger.agency_sales_lists');
        Route::get('ledger/agency_result', [\App\Http\Controllers\Manage\LedgerController::class, 'agencyResult'])->name('ledger.agency_result');
        Route::get('ledger/bunch_issues', [\App\Http\Controllers\Manage\LedgerController::class, 'bunch_issues'])->name('ledger.bunch_issues');
        Route::put('ledger/unload_all', [\App\Http\Controllers\Manage\LedgerController::class, 'unloadAll'])->name('ledger.unload_all');

        Route::get('marketing/graph/inventory', [\App\Http\Controllers\Manage\Marketing\GraphController::class, 'inventory'])->name('marketing.graph.inventory');
        Route::get('marketing/graph/inventory/chart_by_day', [\App\Http\Controllers\Manage\Marketing\GraphController::class, 'chartByDay'])->name('marketing.graph.inventory.chart_by_day');
        Route::get('marketing/graph/inventory/chart_by_hour', [\App\Http\Controllers\Manage\Marketing\GraphController::class, 'chartByHour'])->name('marketing.graph.inventory.chart_by_hour');
        // Route::get('marketing/graph/reservation', [\App\Http\Controllers\Manage\Marketing\GraphController::class, 'reservation'])->name('marketing.graph.reservation');
        Route::get('marketing/reservation_graph', [\App\Http\Controllers\Manage\Marketing\ReservationGraphController::class, 'index'])->name('marketing.reservation_graph.index');
        Route::get('marketing/reservation_graph/chart_by_day', [\App\Http\Controllers\Manage\Marketing\ReservationGraphController::class, 'chartByDay'])->name('marketing.reservation_graph.chart_by_day');
        Route::get('marketing/reservation_graph/chart_by_hour', [\App\Http\Controllers\Manage\Marketing\ReservationGraphController::class, 'chartByHour'])->name('marketing.reservation_graph.chart_by_hour');

        Route::get('/master', [\App\Http\Controllers\Manage\MasterController::class, 'index'])->name('master');
        // Route::get('/master/agencies', [\App\Http\Controllers\Manage\Master\AgenciesController::class, 'index'])->name('agencies');
        Route::get('/master/arrival_flights', [\App\Http\Controllers\Manage\Master\ArrivalFlightsController::class, 'index'])->name('arrival_flights');
        // Route::get('/master/coupons', [\App\Http\Controllers\Manage\Master\CouponsController::class, 'index'])->name('coupons');
        Route::get('/master/departure_flights', [\App\Http\Controllers\Manage\Master\DepartureFlightsController::class, 'index'])->name('departure_flights');
        Route::prefix('master')->name('master.')->group(function () {
            Route::post('/agencies/upload', [\App\Http\Controllers\Manage\Master\AgenciesController::class, 'upload'])->name('agencies.upload');
            Route::get('/agencies/{agency}/download', [\App\Http\Controllers\Manage\Master\AgenciesController::class, 'download'])->name('agencies.download');
            Route::resource('/agencies', \App\Http\Controllers\Manage\Master\AgenciesController::class);
            Route::resource('/good_categories', \App\Http\Controllers\Manage\Master\GoodCategoriesController::class);
            Route::resource('/goods', \App\Http\Controllers\Manage\Master\GoodsController::class);
            Route::resource('/coupons', \App\Http\Controllers\Manage\Master\CouponsController::class);
            Route::resource('/agency_prices', \App\Http\Controllers\Manage\Master\AgencyPricesController::class);
            Route::post('/prices/carsize_rate', [\App\Http\Controllers\Manage\Master\PricesController::class, 'storeCarSizeRate'])->name('prices.carsize_rate');
            Route::resource('/prices', \App\Http\Controllers\Manage\Master\PricesController::class);
            Route::resource('/dynamic_pricings', \App\Http\Controllers\Manage\Master\DynamicPricingsController::class);
        });

        Route::get('/settings/daily_update', [\App\Http\Controllers\Manage\Settings\DailyUpdateController::class, 'index'])->name('daily_update');
        Route::get('/settings/monthly_sales_targets', [\App\Http\Controllers\Manage\Settings\MonthlySalesTargetsController::class, 'index'])->name('monthly_sales_targets');
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

Route::get('coupons/coupons_for_register', [\App\Http\Controllers\CouponsController::class, 'couponsForRegister'])->name('coupons.coupons_for_register');


require __DIR__.'/manage_auth.php';
require __DIR__.'/member_auth.php';
require __DIR__.'/form_auth.php';
