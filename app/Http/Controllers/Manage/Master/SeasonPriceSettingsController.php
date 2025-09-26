<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class SeasonPriceSettingsController extends Controller
{
    //
    public function index()
    {
        $today = \Carbon\Carbon::today();
        // 当年及び過去3年、未来3年
        $yearList = range($today->year - 3, $today->year + 3);


        $persistedYear = (int) session('persisted_calendar_year', $today->year);
        $defaultMonth = ($persistedYear == $today->year) ? $today->month : 1;
        $persistedMonth1 = (int) session('persisted_calendar_month1', $defaultMonth);

        return view('manage.master.season_price_settings', [
            'yearList' => $yearList,
            'persistedYear' => $persistedYear,
            'persistedMonth1' => $persistedMonth1,
        ]);
    }
}
