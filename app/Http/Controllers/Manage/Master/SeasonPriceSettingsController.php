<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\SeasonPriceSetting\SeasonPriceSettingDestroyByDateRequest;
use App\Http\Requests\Manage\Master\SeasonPriceSetting\SeasonPriceSettingStoreAllRequest;
use App\Http\Requests\Manage\Master\SeasonPriceSetting\SeasonPriceSettingUpdateByDateRequest;
use App\Models\SeasonPriceSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function storeAll(SeasonPriceSettingStoreAllRequest $request)
    {
        $validated = $request->validated();
        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $dataToUpsert = [];

        // 開始日から終了日までの期間を生成
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dataToUpsert[] = [
                'office_id' => config('const.commons.office_id'),
                'target_date' => $date,
                'season_price' => $validated['season_price'],
            ];
        }
        if (empty($dataToUpsert)) {
            return redirect()->back()->with('failure', '登録対象の日付がありませんでした。');
        }
        try {
            DB::transaction(function () use($dataToUpsert) {
                // 'target_date' カラムをユニークキーとして、存在すれば更新、なければ新規作成
                SeasonPriceSetting::withoutTrashed()->upsert(
                    $dataToUpsert,
                    ['office_id', 'target_date'],
                    ['season_price']
                );

            });
            return redirect()->back()->with('success', 'シーズン料金を一括登録しました。');

        } catch (\Exception $e) {
            Log::error('シーズン料金の一括登録に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '登録処理中にエラーが発生しました。');
        }
    }

    public function updateByDate(SeasonPriceSettingUpdateByDateRequest $request)
    {
        $validated = $request->validated();
        $targetDate = $validated['edit_target_date'];

        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);


        try {
            // SeasonPriceSetting モデルを使用して、指定された日付のデータを更新または作成
            $seasonPriceSetting = SeasonPriceSetting::updateOrCreate(
                ['target_date' => $targetDate, 'office_id' => config('const.commons.office_id')],
                [
                    'season_price' => $validated['edit_season_price'],
                ]
            );

            if ($seasonPriceSetting->wasRecentlyCreated) {
                return redirect()->back()->with('success', $targetDate . 'のシーズン料金を登録しました。');
            } else {
                return redirect()->back()->with('success', $targetDate . 'のシーズン料金を更新しました。');
            }
        } catch (\Exception $e) {
            Log::error($targetDate . 'のシーズン料金の更新に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '更新処理中にエラーが発生しました。');
        }
    }

    public function destroyByDate(SeasonPriceSettingDestroyByDateRequest $request)
    {
        $validated = $request->validated();
        $targetDate = $validated['delete_target_date'];

        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);


        try {
            SeasonPriceSetting::where('target_date', $targetDate)
                ->where('office_id', config('const.commons.office_id'))
                ->delete();
            return redirect()->back()->with('success', $targetDate . 'のシーズン料金を削除しました。');

        } catch (\Exception $e) {
            Log::error($targetDate . 'のシーズン料金の削除に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '削除処理中にエラーが発生しました。');
        }
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
