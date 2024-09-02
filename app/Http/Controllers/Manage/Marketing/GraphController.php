<?php

namespace App\Http\Controllers\Manage\Marketing;

use App\Http\Controllers\Manage\Controller;
use App\Services\Graphs\InventoryGraphs;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function inventory()
    {
        return view('manage.marketing.graph.inventory');
    }


    public function chartByHour(Request $request, InventoryGraphs $graphService)
    {
        $data = $graphService->loadDataByHour($request);

        return response()->json([
            'success' => true,
            'data' => $data
         ]);
    }



    public function chartByDay(Request $request, InventoryGraphs $graphService)
    {
        $data = $graphService->loadDataByDay($request);

        return response()->json([
            'success' => true,
            'data' => $data
         ]);
    }


    public function reservation()
    {
        return view('manage.marketing.graph.reservation');
    }
}
