<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\FullLimitSetting\FullLimitSettingDestroyByDateRequest;
use App\Http\Requests\Manage\Master\FullLimitSetting\FullLimitSettingUpdateByDateRequest;
use App\Http\Requests\Manage\Master\FullLimitSetting\FullLimitSettingStoreAllRequest;
use App\Models\FullLimitSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FullLimitSettingsController extends Controller
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

        return view('manage.master.full_limit_settings', [
            'yearList' => $yearList,
            'persistedYear' => $persistedYear,
            'persistedMonth1' => $persistedMonth1,
        ]);
    }

    public function storeAll(FullLimitSettingStoreAllRequest $request)
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
                'load_limit_symbol' => $validated['load_limit_symbol'],
                'unload_limit_symbol' => $validated['unload_limit_symbol'],
                'cross_time_symbol' => $validated['cross_time_symbol'],
            ];
        }
        if (empty($dataToUpsert)) {
            return redirect()->back()->with('failure', '登録対象の日付がありませんでした。');
        }
        try {
            DB::transaction(function () use($dataToUpsert) {
                // 'target_date' カラムをユニークキーとして、存在すれば更新、なければ新規作成
                FullLimitSetting::withoutTrashed()->upsert(
                    $dataToUpsert,
                    ['office_id', 'target_date'],
                    ['load_limit_symbol', 'unload_limit_symbol', 'cross_time_symbol']
                );

            });
            return redirect()->back()->with('success', '満車設定を一括登録しました。');

        } catch (\Exception $e) {
            Log::error('満車設定の一括登録に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '登録処理中にエラーが発生しました。');
        }
    }

    public function updateByDate(FullLimitSettingUpdateByDateRequest $request)
    {
        $validated = $request->validated();
        $targetDate = $validated['edit_target_date'];

        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);


        try {
            // FullLimitSetting モデルを使用して、指定された日付のデータを更新または作成
            $fullLimitSetting = FullLimitSetting::updateOrCreate(
                ['target_date' => $targetDate, 'office_id' => config('const.commons.office_id')],
                [
                    'load_limit_symbol' => $validated['edit_load_limit_symbol'],
                    'unload_limit_symbol' => $validated['edit_unload_limit_symbol'],
                    'cross_time_symbol' => $validated['edit_cross_time_symbol'],
                ]
            );

            if ($fullLimitSetting->wasRecentlyCreated) {
                return redirect()->back()->with('success', $targetDate . 'の満車設定を登録しました。');
            } else {
                return redirect()->back()->with('success', $targetDate . 'の満車設定を更新しました。');
            }
        } catch (\Exception $e) {
            Log::error($targetDate . 'の満車設定の更新に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '更新処理中にエラーが発生しました。');
        }
    }

    public function destroyByDate(FullLimitSettingDestroyByDateRequest $request)
    {
        $validated = $request->validated();
        $targetDate = $validated['delete_target_date'];

        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);


        try {
            FullLimitSetting::where('target_date', $targetDate)
                ->where('office_id', config('const.commons.office_id'))
                ->delete();
            return redirect()->back()->with('success', $targetDate . 'の満車設定を削除しました。');

        } catch (\Exception $e) {
            Log::error($targetDate . 'の満車設定の削除に失敗しました。: ' . $e->getMessage());
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
            // $results[$date->format('Y-m-d')] = $this->getStockData($date->format('Y-m-d')); // デバッグ用
            $results[$date->format('Y-m-d')] = $this->getStockData($date->format('Y-m-d'));
        }

        $eventData = []; // イベントデータを格納する配列を初期化
        foreach ($results as $date => $stock) {
            $eventData[] = [
                'id' => $date,
                'stock' =>$stock,
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
    private function getStockData(string $dateStr)
    {
        $fullLimitSetting = FullLimitSetting::where('office_id', config('const.commons.office_id'))
            ->where('target_date', $dateStr)->first();

        if ($fullLimitSetting) {
            /**@var FullLimitSetting $fullLimitSetting */
            return [
                'target_date' => $fullLimitSetting->target_date->format('Y-m-d'),
                'load_limit_symbol' => $fullLimitSetting->load_limit_symbol,
                'unload_limit_symbol' => $fullLimitSetting->unload_limit_symbol,
                'cross_time_symbol' => $fullLimitSetting->cross_time_symbol,
                'load_limit_symbol_label' => $fullLimitSetting->limitOverStatus(FullLimitSetting::LOAD_LIMIT_SYMBOL)->label(),
                'unload_limit_symbol_label' => $fullLimitSetting->limitOverStatus(FullLimitSetting::UNLOAD_LIMIT_SYMBOL)->label(),
                'cross_time_symbol_label' => $fullLimitSetting->limitOverStatus(FullLimitSetting::CROSS_TIME_SYMBOL)->label(),
            ];
        }

        // データが存在しない場合は、カレンダー表示のために全てのキーを返す
        return [
            'target_date' => $dateStr,
            'load_limit_symbol' => null,
            'unload_limit_symbol' => null,
            'cross_time_symbol' => null,
            'load_limit_symbol_label' => null,
            'unload_limit_symbol_label' => null,
            'cross_time_symbol_label' => null,
        ];
    }
}
