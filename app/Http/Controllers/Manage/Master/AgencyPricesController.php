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
        $agencyPrice = AgencyPrice::create([
            'office_id' => config('const.commons.office_id'),
            'agency_id' => $request->agency_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'base_price' => $request->base_price,
            'd1' => $request->d1,
            'd2' => $request->d2,
            'd3' => $request->d3,
            'd4' => $request->d4,
            'd5' => $request->d5,
            'd6' => $request->d6,
            'd7' => $request->d7,
            'd8' => $request->d8,
            'd9' => $request->d9,
            'd10' => $request->d10,
            'd11' => $request->d11,
            'd12' => $request->d12,
            'd13' => $request->d13,
            'd14' => $request->d14,
            'd15' => $request->d15,
            'price_per_day' => $request->price_per_day,
            'late_fee' => $request->late_fee,
            'memo' => $request->memo,
        ]);

        return redirect()->back();
    }


    public function update(AgencyPricesRequest $request, $id)
    {
        $agencyPrice = AgencyPrice::findOrFail($id);
        $agencyPrice->fill([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'base_price' => $request->base_price,
            'd1' => $request->d1,
            'd2' => $request->d2,
            'd3' => $request->d3,
            'd4' => $request->d4,
            'd5' => $request->d5,
            'd6' => $request->d6,
            'd7' => $request->d7,
            'd8' => $request->d8,
            'd9' => $request->d9,
            'd10' => $request->d10,
            'd11' => $request->d11,
            'd12' => $request->d12,
            'd13' => $request->d13,
            'd14' => $request->d14,
            'd15' => $request->d15,
            'price_per_day' => $request->price_per_day,
            'late_fee' => $request->late_fee,
            'memo' => $request->memo,
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
