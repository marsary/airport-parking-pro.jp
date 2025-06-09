<?php

namespace App\Http\Controllers\Manage\Ledger;

use App\Http\Controllers\Controller;
use App\Models\LoadUnloadInventoryPerformance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReservationResultController extends Controller
{
    public function calendar(Request $request)
    {
        $params = $request->query();

        $startDate = Carbon::parse($params['start']);
        $endDate = Carbon::parse($params['end']);
        $period = CarbonPeriod::create($startDate, $endDate);

        $performances = LoadUnloadInventoryPerformance::whereBetween('target_date', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return $item->target_date->format('Y-m-d');
            });

        $results = []; // 結果を格納する配列を初期化
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $results[$dateStr] = $this->getStockDataForDate($dateStr, $performances->get($dateStr));
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


    private function getStockDataForDate(string $dateStr, LoadUnloadInventoryPerformance $performance)
    {
        if ($performance) {
            return [
                'target_date' => $performance->target_date->format('Y-m-d'),
                'load_quantity' => $performance->load_quantity,
                'unload_quantity' => $performance->unload_quantity,
                'stock_quantity' => $performance->stock_quantity,
                'no_data' => false,
            ];
        }
    }

}
