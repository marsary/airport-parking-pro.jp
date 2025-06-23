<?php

namespace App\Http\Controllers;

use App\Models\ArrivalFlight;
use Illuminate\Http\Request;

class ArrivalFlightsController extends Controller
{
    public function getInfo(Request $request)
    {
        $arrivalFlight = ArrivalFlight::where('flight_no', $request->flight_no)
            ->where('arrive_date', $request->arrive_date)
            ->where('airline_id', $request->airline_id)
            ->first();
error_log($request->flight_no."\n",3,"../storage/logs/test.log");
error_log($request->arrive_date."\n",3,"../storage/logs/test.log");
error_log($request->airline_id."\n",3,"../storage/logs/test.log");
error_log(json_encode($arrivalFlight)."\n",3,"../storage/logs/test.log");

        return response()->json([
            'success' => isset($arrivalFlight),
            'data' => ['arrivalFlight' => [
                'airlineName' => $arrivalFlight?->airline?->name,
                'depAirportName' => $arrivalFlight?->depAirport?->name,
                'arrAirportName' => $arrivalFlight?->arrAirport?->name,
                'arriveTime' => $arrivalFlight?->arrive_time,
            ]],
         ]);
    }
}
