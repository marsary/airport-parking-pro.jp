<?php

namespace App\Http\Controllers\Manage\Marketing;

use App\Enums\ReservationRoute;
use App\Http\Controllers\Manage\Controller;
use App\Models\Deal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationGraphController extends Controller
{
    public function index(Request $request)
    {
        return view('manage.marketing.graph.reservation');
    }

    public function chartByHour(Request $request)
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
        // 取引　※電話予約（自）
        $data[ReservationRoute::SELF_PHONE->label()] = $this->countByHour($startDate, $intervals, ReservationRoute::SELF_PHONE);
        // 取引　※電話予約（代）
        $data[ReservationRoute::STAFF_PHONE->label()] = $this->countByHour($startDate, $intervals, ReservationRoute::STAFF_PHONE);
        // 取引　※当日受付予約
        $data[ReservationRoute::COUNTER->label()] = $this->countByHour($startDate, $intervals, ReservationRoute::COUNTER);
        // 取引　※ネット予約（自）
        $data[ReservationRoute::SELF_WEB->label()] = $this->countByHour($startDate, $intervals, ReservationRoute::SELF_WEB);
        // 取引　※ネット予約（代）
        $data[ReservationRoute::STAFF_WEB->label()] = $this->countByHour($startDate, $intervals, ReservationRoute::STAFF_WEB);

        return response()->json([
            'success' => true,
            'data' => [
                'datasets' => $data,
                'labels' => $labels,
                'currentDate' => $startDate,
            ]
         ]);
    }


    private function countByHour($startDate, $intervals, ReservationRoute $route)
    {
        foreach($intervals as $interval){
            $range[$interval->format("H")] = 0;
        }

        $countsByHour = Deal::select([
            DB::raw('HOUR(reserve_date) as hour'),
            DB::raw('COUNT(id) AS count'),
        ])
            ->where('office_id', config('const.commons.office_id'))
            ->whereDate("reserve_date", $startDate)
            ->where('reservation_route', $route->value)
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        foreach($countsByHour as $countRow){
            $range[$countRow->hour] = $countRow->count;
        }
        return $range;
    }

    public function chartByDay(Request $request)
    {
        [$startDate, $endDate] = $this->getStartEndDateFromView($request);
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = array_map(function($date){
            return $date->format("Y-m-d");
        }, $period->toArray());

        $data = [];
        // 取引　※電話予約（自）
        $data[ReservationRoute::SELF_PHONE->label()] = $this->countByDay($startDate, $endDate, $period, ReservationRoute::SELF_PHONE);
        // 取引　※電話予約（代）
        $data[ReservationRoute::STAFF_PHONE->label()] = $this->countByDay($startDate, $endDate, $period, ReservationRoute::STAFF_PHONE);
        // 取引　※当日受付予約
        $data[ReservationRoute::COUNTER->label()] = $this->countByDay($startDate, $endDate, $period, ReservationRoute::COUNTER);
        // 取引　※ネット予約（自）
        $data[ReservationRoute::SELF_WEB->label()] = $this->countByDay($startDate, $endDate, $period, ReservationRoute::SELF_WEB);
        // 取引　※ネット予約（代）
        $data[ReservationRoute::STAFF_WEB->label()] = $this->countByDay($startDate, $endDate, $period, ReservationRoute::STAFF_WEB);


        return response()->json([
            'success' => true,
            'data' => [
                'datasets' => $data,
                'labels' => $labels,
            ]
         ]);
    }

    private function countByDay($startDate, $endDate, $period, ReservationRoute $route)
    {
        foreach($period as $date){
            $range[$date->format("Y-m-d")] = 0;
        }

        $counts = Deal::select([
            DB::raw('DATE(reserve_date) as reserve_date'),
            DB::raw('COUNT(id) AS count'),
        ])
            ->where('office_id', config('const.commons.office_id'))
            ->whereDate('reserve_date','>=', $startDate->toDateString())
            ->whereDate('reserve_date','<=', $endDate->toDateString())
            ->where('reservation_route', $route->value)
            ->groupBy('reserve_date')
            ->orderBy('reserve_date', 'ASC')
            ->get();

        foreach($counts as $countRow){
            $range[$countRow->reserve_date->format("Y-m-d")] = $countRow->count;
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
