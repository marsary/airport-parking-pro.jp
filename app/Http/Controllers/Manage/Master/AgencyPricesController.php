<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\AgencyPricesRequest;
use App\Models\Agency;
use App\Models\AgencyPrice;
use Illuminate\Http\Request;

class AgencyPricesController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Agency::where('office_id', config('const.commons.office_id'))
            ->when($request->input('company_name'), function($query, $search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('search_keywords'), function($query, $search){
                $query->where('keyword', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('tel'), function($query, $search){
                $query->where('tel', $search);
            })
            ->when($request->input('agency_code'), function($query, $search){
                $query->where('code', $search);
            })
            ;

        $agencies = $query->orderBy('id', 'asc')->get();

        $agencyPrices = AgencyPrice::whereIn('agency_id', $agencies->pluck('id')->toArray())
            ->orderBy('start_date', 'desc')->get();

        return view('manage.master.agency_prices', [
            'agencies' => $agencies,
            'agencyPrices' => $agencyPrices,
        ]);
    }


    public function store(AgencyPricesRequest $request)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_0";
        $agencyPrice = AgencyPrice::create([
            'office_id' => config('const.commons.office_id'),
            'agency_id' => $request->{$recordKey}['agency_id'],
            'start_date' => $request->{$recordKey}['start_date'],
            'end_date' => $request->{$recordKey}['end_date'],
            'base_price' => $request->{$recordKey}['base_price'],
            'd1' => $request->{$recordKey}['d1'],
            'd2' => $request->{$recordKey}['d2'],
            'd3' => $request->{$recordKey}['d3'],
            'd4' => $request->{$recordKey}['d4'],
            'd5' => $request->{$recordKey}['d5'],
            'd6' => $request->{$recordKey}['d6'],
            'd7' => $request->{$recordKey}['d7'],
            'd8' => $request->{$recordKey}['d8'],
            'd9' => $request->{$recordKey}['d9'],
            'd10' => $request->{$recordKey}['d10'],
            'd11' => $request->{$recordKey}['d11'],
            'd12' => $request->{$recordKey}['d12'],
            'd13' => $request->{$recordKey}['d13'],
            'd14' => $request->{$recordKey}['d14'],
            'd15' => $request->{$recordKey}['d15'],
            'price_per_day' => $request->{$recordKey}['price_per_day'],
            'late_fee' => $request->{$recordKey}['late_fee'],
            'memo' => $request->{$recordKey}['memo'],
        ]);

        return redirect()->back();
    }


    public function update(AgencyPricesRequest $request, $id)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->route()->parameter('agency_price', 0);
        $agencyPrice = AgencyPrice::findOrFail($id);
        $agencyPrice->fill([
            'start_date' => $request->{$recordKey}['start_date'],
            'end_date' => $request->{$recordKey}['end_date'],
            'base_price' => $request->{$recordKey}['base_price'],
            'd1' => $request->{$recordKey}['d1'],
            'd2' => $request->{$recordKey}['d2'],
            'd3' => $request->{$recordKey}['d3'],
            'd4' => $request->{$recordKey}['d4'],
            'd5' => $request->{$recordKey}['d5'],
            'd6' => $request->{$recordKey}['d6'],
            'd7' => $request->{$recordKey}['d7'],
            'd8' => $request->{$recordKey}['d8'],
            'd9' => $request->{$recordKey}['d9'],
            'd10' => $request->{$recordKey}['d10'],
            'd11' => $request->{$recordKey}['d11'],
            'd12' => $request->{$recordKey}['d12'],
            'd13' => $request->{$recordKey}['d13'],
            'd14' => $request->{$recordKey}['d14'],
            'd15' => $request->{$recordKey}['d15'],
            'price_per_day' => $request->{$recordKey}['price_per_day'],
            'late_fee' => $request->{$recordKey}['late_fee'],
            'memo' => $request->{$recordKey}['memo'],
        ])->save();

        return redirect()->back();
    }


    public function destroy($id)
    {
        $agencyPrice = AgencyPrice::findOrFail($id);
        $agencyPrice->delete();

        return redirect()->back();
    }
}
