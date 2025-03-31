<?php
namespace App\Services\Graphs;

use App\Enums\DealStatus;
use App\Enums\Graphs\InventoryType;
use App\Enums\TransactionType;
use App\Models\Deal;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryGraphs
{
    use GraphCommon;

    /**
     * 時間ごとの在庫データを取得
     *
     * @param Request $request
     * @return array<string,mixed> 取得したデータセット、ラベル、および現在の日付
     */
    public function loadDataByHour(Request $request)
    {
        $startDate = Carbon::parse($request->currentStartDate);
        $intervals = CarbonPeriod::since("00:00")->hours(1)->until("23:00", true)->toArray();
        $labels = array_map(function($interval){
            return $interval->format("H");
        }, $intervals);

        if($request->has('nextPrev')) {
            if($request->nextPrev == 'next') {
                $startDate->addDay();
            } elseif($request->nextPrev == 'prev') {
                $startDate->subDay();
            }
        }

        $data = [];
        // 入庫
        $data[InventoryType::LOAD_COLUMN->label()] = $this->countByHour($startDate, $intervals, InventoryType::LOAD_COLUMN->value, InventoryType::LOADTIME_COLUMN->value);
        // 出庫
        $data[InventoryType::UNLOAD_COLUMN->label()] = $this->countByHour($startDate, $intervals, InventoryType::UNLOAD_COLUMN->value, InventoryType::UNLOADTIME_COLUMN->value);
        // MAX在庫
        // $data[self::MAX_STOCK] = $this->countByHour($startDate, $intervals, self::MAX_STOCK);
        // おわり在庫
        // $data[self::ENDING_STOCK] = $this->countByHour($startDate, $intervals, self::ENDING_STOCK);

        return [
            'datasets' => $data,
            'labels' => $labels,
            'currentDate' => $startDate,
        ];
    }


    /**
     * 日ごとの在庫データを取得
     *
     * @param Request $request
     * @return array<string,mixed> 取得したデータセットおよびラベル
     */
    public function loadDataByDay(Request $request)
    {
        [$startDate, $endDate] = $this->getStartEndDateFromView($request);
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = array_map(function($date){
            return $date->format("Y-m-d");
        }, $period->toArray());

        $data = [];
        // 入庫
        $data[InventoryType::LOAD_COLUMN->label()] = $this->countByDay($startDate, $endDate, $period, InventoryType::LOAD_COLUMN->value);
        // 出庫
        $data[InventoryType::UNLOAD_COLUMN->label()] = $this->countByDay($startDate, $endDate, $period, InventoryType::UNLOAD_COLUMN->value);
        // MAX在庫
        // $data[self::MAX_STOCK] = $this->countByDay($startDate, $endDate, $period, self::MAX_STOCK);
        // おわり在庫
        // $data[self::ENDING_STOCK] = $this->countByDay($startDate, $endDate, $period, self::ENDING_STOCK);


        return [
            'datasets' => $data,
            'labels' => $labels,
        ];
    }

    /**
     * 時間ごとの在庫データを集計
     *
     * @param Carbon $startDate 集計対象の日付
     * @param CarbonInterface[] $intervals 集計対象の時間間隔
     * @param string $targetColumn 集計対象の日付カラム
     * @param string $timeColumn 集計対象の時間カラム
     * @return array 時間ごとの在庫データ
     */
    private function countByHour($startDate, $intervals, string $targetColumn, string $timeColumn)
    {
        foreach($intervals as $interval){
            $range[$interval->format("H")] = 0;
        }

        $countsByHour = Deal::select([
            DB::raw("LPAD(HOUR($timeColumn),2,0) as hour"),
            DB::raw('COUNT(id) AS count'),
        ])
            ->where('office_id', config('const.commons.office_id'))
            ->whereDate($targetColumn, $startDate)
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('transaction_type', TransactionType::PURCHASE_ONLY->value)
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        foreach($countsByHour as $countRow){
            $range[$countRow->hour] = $countRow->count;
        }
        return $range;
    }

    /**
     * 日ごとの在庫データを集計します。
     *
     * @param Carbon $startDate 集計の開始日
     * @param Carbon $endDate 集計の終了日
     * @param CarbonPeriod $period 集計対象の日付の期間
     * @param string $targetColumn 集計対象のカラム
     * @return array 日ごとの在庫データ
     */
    private function countByDay($startDate, $endDate, $period, string $targetColumn)
    {
        foreach($period as $date){
            $range[$date->format("Y-m-d")] = 0;
        }

        $counts = Deal::select([
            DB::raw("DATE($targetColumn) as $targetColumn"),
            DB::raw('COUNT(id) AS count'),
        ])
            ->where('office_id', config('const.commons.office_id'))
            ->whereDate($targetColumn,'>=', $startDate->toDateString())
            ->whereDate($targetColumn,'<=', $endDate->toDateString())
            ->whereNot('status', DealStatus::CANCEL->value)
            ->whereNot('transaction_type', TransactionType::PURCHASE_ONLY->value)
            ->groupBy($targetColumn)
            ->orderBy($targetColumn, 'ASC')
            ->get();

        foreach($counts as $countRow){
            $range[$countRow->{$targetColumn}->format("Y-m-d")] = $countRow->count;
        }
        return $range;
    }

}
