<?php

namespace App\Http\Controllers;

use App\Models\ArrivalFlight;
use Illuminate\Http\Request;

class ArrivalFlightsController extends Controller
{
    public function getInfo(Request $request)
    {
        $arrivalFlight = ArrivalFlight::where('flight_no', $request->flight_no)
            ->where('arrive_date', $request->arrive_date)->first();

        return response()->json([
            'success' => true,
            'data' => ['arrivalFlight' => [
                'airlineName' => $arrivalFlight?->airline?->name,
                'depAirportName' => $arrivalFlight?->depAirport?->name,
                'arrAirportName' => $arrivalFlight?->arrAirport?->name,
                'arriveTime' => $arrivalFlight?->arrive_time,
            ]],
         ]);
    }
}
