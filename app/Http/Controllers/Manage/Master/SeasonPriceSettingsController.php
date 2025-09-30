<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Models\SeasonPriceSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

    public function calendar(Request $request)
    {
        $params = $request->query();

        $startDate = Carbon::parse($params['start']);
        $endDate = Carbon::parse($params['end']);
        // $today = Carbon::today();
        $period = CarbonPeriod::create($startDate, $endDate);

        $results = []; // 結果を格納する配列を初期化
        foreach ($period as $date) {
            $results[$date->format('Y-m-d')] = $this->getSeasonPriceSettingData($date->format('Y-m-d'));
        }

        $eventData = []; // イベントデータを格納する配列を初期化
        foreach ($results as $date => $stock) {
            $eventData[] = [
                'id' => $date,
                'seasonPriceSetting' =>$stock,
                'start' => $date,
                'end' => $date,
                'allDay' => true,
            ];
        }

        // dd($eventData);
        return response()->json($eventData);

    }

    /**
     * 指定された日付の在庫データを取得
     */
    private function getSeasonPriceSettingData(string $dateStr)
    {
        $seasonPriceSetting = SeasonPriceSetting::where('office_id', config('const.commons.office_id'))
            ->where('target_date', $dateStr)->first();

        if ($seasonPriceSetting) {
            return [
                'target_date' => $seasonPriceSetting->target_date->format('Y-m-d'),
                'season_price' => $seasonPriceSetting->season_price,
            ];
        }

        // データが存在しない場合は、カレンダー表示のために全てのキーを返す
        return [
            'target_date' => $dateStr,
            'season_price' => null,
        ];
    }
}
