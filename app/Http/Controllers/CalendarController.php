<?php

namespace App\Http\Controllers;

use App\Services\ParkingLimitDateChecker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function loadDates(Request $request)
    {
        $params = $request->query();

        $startDate = Carbon::parse($params['start']);
        $endDate = Carbon::parse($params['end']);
        $today = Carbon::today();
        $checker = new ParkingLimitDateChecker($startDate, $endDate);
        $eventData = [];
        foreach ($checker->checkLoadDate() as $date => $limitOverStatus) {
            if(Carbon::parse($date) < $today) {
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


    public function unloadDates(Request $request)
    {
        $params = $request->query();

        $startDate = Carbon::parse($params['start']);
        $endDate = Carbon::parse($params['end']);
        $today = Carbon::today();

        $checker = new ParkingLimitDateChecker($startDate, $endDate);
        $eventData = [];
        foreach ($checker->checkUnloadDate() as $date => $limitOverStatus) {
            if(Carbon::parse($date) < $today) {
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
