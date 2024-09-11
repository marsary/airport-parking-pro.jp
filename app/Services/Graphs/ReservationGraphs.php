<?php
namespace App\Services\Graphs;

use App\Enums\ReservationRoute;
use App\Models\Deal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationGraphs
{
    use GraphCommon;

    /**
     * 時間ごとの予約データを取得
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

        return [
            'datasets' => $data,
            'labels' => $labels,
            'currentDate' => $startDate,
        ];
    }


    /**
     * 日ごとの予約データを取得
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


        return [
            'datasets' => $data,
            'labels' => $labels,
        ];
    }

    /**
     * 時間ごとの予約件数を集計
     *
     * @param Carbon $startDate 集計対象の日付
     * @param array $intervals 集計対象の時間間隔
     * @param ReservationRoute $route 予約ルート
     * @return array 時間ごとの予約件数
     */
    private function countByHour($startDate, $intervals, ReservationRoute $route)
    {
        foreach($intervals as $interval){
            $range[$interval->format("H")] = 0;
        }

        $countsByHour = Deal::select([
            DB::raw('LPAD(HOUR(reserve_date),2,0) as hour'),
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

    /**
     * 日ごとの予約件数を集計
     *
     * @param Carbon $startDate 集計の開始日
     * @param Carbon $endDate 集計の終了日
     * @param CarbonPeriod $period 集計対象の日付の期間
     * @param ReservationRoute $route 予約ルート
     * @return array 日ごとの予約件数
     */
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

}
