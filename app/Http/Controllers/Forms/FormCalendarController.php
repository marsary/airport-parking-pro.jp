<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Services\ParkingLimitDateChecker;
use App\Services\ParkingLimitTimeChecker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormCalendarController extends Controller
{
    public function loadDates(Request $request)
    {
        $params = $request->query();

        $startDate = Carbon::parse($params['start']);
        $endDate = Carbon::parse($params['end']);


        $maxDate = $minDate->copy()->addMonths(config('const.commons.reserve_cal_month_periods'))->subDay();

        $checker = new ParkingLimitDateChecker($startDate, $endDate);
        $eventData = [];
        foreach ($checker->checkLoadDate() as $date => $limitOverStatus) {
            $currentDate = Carbon::parse($date);

            // 23時までは、翌日の予約を可能とします。
            // ※当日予約はできません。
            // 選択できる日付は5か月先までになります。
            if($currentDate < $minDate || $currentDate > $maxDate) {
                $eventTitle = '-';
            } else {
                $eventTitle = $limitOverStatus->label();
            }
            $eventData[] = [
                'id' => $date,
                'title' =>$eventTitle,
                'start' => $date,
                'end' => $date,
                'allDay' => true,
            ];
        }

        // dd($eventData);
        return response()->json($eventData);
    }

    public function loadHours(Request $request)
    {
        $loadDate = $request->input('load_date');

        $loadDate = Carbon::parse($loadDate);

        $checker = new ParkingLimitTimeChecker($loadDate);
        $hourlyData = [];
        foreach ($checker->checkLoadHours() as $hour => $hourResult) {
            $hourlyData[$hour] = [
                'status' => $hourResult['hourVacant']->label(),
                '00' => $hourResult['qurterResults']['00']->label(),
                '15' => $hourResult['qurterResults']['15']->label(),
                '30' => $hourResult['qurterResults']['30']->label(),
                '45' => $hourResult['qurterResults']['45']->label(),
            ];
        }

        // dd($eventData);
        return response()->json([
            'success' => true,
            'data' => ['hourlyData' => $hourlyData],
         ]);
    }

    public function unloadDates(Request $request)
    {
        $params = $request->query();

        $startDate = Carbon::parse($params['start']);
        $endDate = Carbon::parse($params['end']);


        $checker = new ParkingLimitDateChecker($startDate, $endDate);
        $eventData = [];
        foreach ($checker->checkUnloadDate() as $date => $limitOverStatus) {
            // 23時までは、翌日の予約を可能とします。
            // ※当日予約はできません。
            if(Carbon::parse($date) < $minDate) {
                $eventTitle = '-';
            } else {
                $eventTitle = $limitOverStatus->label();
            }
            $eventData[] = [
                'id' => $date,
                'title' =>$eventTitle,
                'start' => $date,
                'end' => $date,
                'allDay' => true,
            ];
        }

        return response()->json($eventData);
    }
}
