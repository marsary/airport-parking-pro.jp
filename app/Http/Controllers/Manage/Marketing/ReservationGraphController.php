<?php

namespace App\Http\Controllers\Manage\Marketing;

use App\Http\Controllers\Manage\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

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

        return response()->json([
            'success' => true,
            'data' => [
                'datasets' => $data,
                'labels' => $labels,
            ]
         ]);
    }

}
