<?php
namespace App\Services\Graphs;

use App\Enums\DealStatus;
use App\Enums\Graphs\InventoryType;
use App\Models\Deal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryGraphs
{
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
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        foreach($countsByHour as $countRow){
            $range[$countRow->hour] = $countRow->count;
        }
        return $range;
    }

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
            ->groupBy($targetColumn)
            ->orderBy($targetColumn, 'ASC')
            ->get();

        foreach($counts as $countRow){
            $range[$countRow->{$targetColumn}->format("Y-m-d")] = $countRow->count;
        }
        return $range;
    }

    private function getStartEndDateFromView(Request $request)
    {
        switch ($request->view) {
            case 'monthly':
                $startDate = Carbon::parse($request->currentStartDate);
                if($request->has('nextPrev')) {
                    if($request->nextPrev == 'next') {
                        $startDate->addMonth();
                    } elseif($request->nextPrev == 'prev') {
                        $startDate->subMonth();
                    }
                }
                $endDate = (clone $startDate)->endOfMonth();
                break;
            case 'weekly':
                $startDate = Carbon::parse($request->currentStartDate);
                if($request->nextPrev == 'next') {
                    $startDate->addWeek();
                } elseif($request->nextPrev == 'prev') {
                    $startDate->subWeek();
                }
                $endDate = (clone $startDate)->endOfWeek();
                break;
            case 'manual':
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
                break;
        }

        return [$startDate, $endDate];
    }

}
