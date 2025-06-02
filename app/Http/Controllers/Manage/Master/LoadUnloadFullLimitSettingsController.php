<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Requests\Manage\Master\LoadUnloadFullLimitSettingDestroyByDateRequest;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\LoadUnloadFullLimitSettingStoreAllRequest;
use App\Http\Requests\Manage\Master\LoadUnloadFullLimitSettingUpdateByDateRequest;
use App\Models\ParkingLimit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoadUnloadFullLimitSettingsController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today();
        // 当年及び過去3年、未来2年
        $yearList = range($today->year - 3, $today->year + 3);


        $persistedYear = (int) session('persisted_calendar_year', $today->year);
        $defaultMonth = ($persistedYear == $today->year) ? $today->month : 1;
        $persistedMonth1 = (int) session('persisted_calendar_month1', $defaultMonth);

        return view('manage.master.load_unload_full_limit_settings', [
            'yearList' => $yearList,
            'persistedYear' => $persistedYear,
            'persistedMonth1' => $persistedMonth1,
        ]);
    }

    public function storeAll(LoadUnloadFullLimitSettingStoreAllRequest $request)
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
                'load_limit' => $validated['load_limit'],
                'unload_limit' => $validated['unload_limit'],
                'at_closing_time' => $validated['at_closing_time'],
                'per_fifteen_munites' => $validated['per_fifteen_munites']
            ];
        }
        if (empty($dataToUpsert)) {
            return redirect()->back()->with('failure', '登録対象の日付がありませんでした。');
        }
        try {
            DB::transaction(function () use($dataToUpsert) {
                // 'target_date' カラムをユニークキーとして、存在すれば更新、なければ新規作成
                ParkingLimit::upsert(
                    $dataToUpsert,
                    ['target_date'],
                    ['load_limit', 'unload_limit', 'at_closing_time', 'per_fifteen_munites']
                );

            });
            return redirect()->back()->with('success', '入出庫上限を一括登録しました。');

        } catch (\Exception $e) {
            Log::error('入出庫上限の一括登録に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '登録処理中にエラーが発生しました。');
        }
    }

    public function updateByDate(LoadUnloadFullLimitSettingUpdateByDateRequest $request)
    {
        $validated = $request->validated();
        $targetDate = $validated['edit_target_date'];

        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);


        try {
            // ParkingLimit モデルを使用して、指定された日付のデータを更新または作成
            $parkingLimit = ParkingLimit::updateOrCreate(
                ['target_date' => $targetDate, 'office_id' => config('const.commons.office_id')],
                [
                    'load_limit' => $validated['edit_load_limit'],
                    'unload_limit' => $validated['edit_unload_limit'],
                    'at_closing_time' => $validated['edit_at_closing_time'],
                    'per_fifteen_munites' => $validated['edit_per_fifteen_munites'],
                ]
            );

            if ($parkingLimit->wasRecentlyCreated) {
                return redirect()->back()->with('success', $targetDate . 'の入出庫上限を登録しました。');
            } else {
                return redirect()->back()->with('success', $targetDate . 'の入出庫上限を更新しました。');
            }
        } catch (\Exception $e) {
            Log::error($targetDate . 'の入出庫上限の更新に失敗しました。: ' . $e->getMessage());
            return redirect()->back()->with('failure', '更新処理中にエラーが発生しました。');
        }
    }

    public function destroyByDate(LoadUnloadFullLimitSettingDestroyByDateRequest $request)
    {
        $validated = $request->validated();
        $targetDate = $validated['delete_target_date'];

        session(['persisted_calendar_year' => $request->input('active_calendar_year')]);
        session(['persisted_calendar_month1' => $request->input('active_calendar_month1')]);


        try {
            ParkingLimit::where('target_date', $targetDate)
                ->where('office_id', config('const.commons.office_id'))
                ->delete();
            return redirect()->back()->with('success', $targetDate . 'の入出庫上限を削除しました。');

        } catch (\Exception $e) {
            Log::error($targetDate . 'の入出庫上限の削除に失敗しました。: ' . $e->getMessage());
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
            // $results[$date->format('Y-m-d')] = $this->generateSampleStockData($date->format('Y-m-d')); // デバッグ用
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
        $parkingLimit = ParkingLimit::where('target_date', $dateStr)->first();

        if ($parkingLimit) {
            return [
                'target_date' => $parkingLimit->target_date->format('Y-m-d'),
                'load_limit' => $parkingLimit->load_limit,
                'unload_limit' => $parkingLimit->unload_limit,
                'at_closing_time' => $parkingLimit->at_closing_time,
                'per_fifteen_munites' => $parkingLimit->per_fifteen_munites,
            ];
        }

        // データが存在しない場合は、カレンダー表示のために全てのキーを返す
        return [
            'target_date' => $dateStr,
            'load_limit' => null,
            'unload_limit' => null,
            'at_closing_time' => null,
            'per_fifteen_munites' => null,
        ];
    }

    /**
     * サンプルの在庫データを生成 (デバッグ用)
     */
    private function generateSampleStockData(string $dateStr)
    {
        return [
            'target_date' => $dateStr,
            'load_limit' => 400,
            'unload_limit' => 400,
            'at_closing_time' => 800,
            'per_fifteen_munites' => 800
        ];
    }
}
