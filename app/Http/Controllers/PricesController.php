<?php

namespace App\Http\Controllers;

use App\Services\PriceTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PricesController extends Controller
{
    public function table(Request $request)
    {
        $loadDate = $request->input('load_date');
        $unloadDate = $request->input('unload_date');
        $agencyCode = $request->input('agency_code');
        $agencyId = DB::table('agencies')->where('code', $agencyCode)->first()?->id;
        $table = PriceTable::getPriceTable($loadDate, $unloadDate, $agencyId);

        return response()->json([
            'success' => true,
            'data' => ['table' => $table],
         ]);
    }
}
