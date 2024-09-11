<?php

namespace App\Http\Controllers\Manage\Marketing;

use App\Http\Controllers\Manage\Controller;
use App\Services\Graphs\ReservationGraphs;
use Illuminate\Http\Request;

class ReservationGraphController extends Controller
{
    public function index()
    {
        return view('manage.marketing.graph.reservation');
    }

    public function chartByHour(Request $request, ReservationGraphs $graphService)
    {
        $data = $graphService->loadDataByHour($request);

        return response()->json([
            'success' => true,
            'data' => $data
         ]);
    }



    public function chartByDay(Request $request, ReservationGraphs $graphService)
    {
        $data = $graphService->loadDataByDay($request);

        return response()->json([
            'success' => true,
            'data' => $data
         ]);
    }

}
