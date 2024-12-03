<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\DynamicPricingRequest;
use App\Http\Requests\Manage\Master\DynamicPricingsRequest;
use App\Models\DynamicPricing;
use Illuminate\Http\Request;

class DynamicPricingsController extends Controller
{
    //
    public function index()
    {
        $dynamicPricings = DynamicPricing::all();

        return view('manage.master.dynamic_pricings', [
            'dynamicPricings' => $dynamicPricings,
        ]);
    }


    public function store(DynamicPricingsRequest $request)
    {
        $dynamicPricing = DynamicPricing::create([
            'office_id' => config('const.commons.office_id'),
            'name' => $request->name,
            'p10' => $request->p10,
            'p20' => $request->p20,
            'p30' => $request->p30,
            'p40' => $request->p40,
            'p50' => $request->p50,
            'p60' => $request->p60,
            'p70' => $request->p70,
            'p80' => $request->p80,
            'p90' => $request->p90,
            'p100' => $request->p100,
            'p110' => $request->p110,
            'p120' => $request->p120,
            'p130' => $request->p130,
            'p131' => $request->p131,
        ]);

        return redirect()->back();
    }


    public function update(DynamicPricingsRequest $request, $id)
    {
        $dynamicPricing = DynamicPricing::findOrFail($id);
        $dynamicPricing->fill([
            'name' => $request->name,
            'p10' => $request->p10,
            'p20' => $request->p20,
            'p30' => $request->p30,
            'p40' => $request->p40,
            'p50' => $request->p50,
            'p60' => $request->p60,
            'p70' => $request->p70,
            'p80' => $request->p80,
            'p90' => $request->p90,
            'p100' => $request->p100,
            'p110' => $request->p110,
            'p120' => $request->p120,
            'p130' => $request->p130,
            'p131' => $request->p131,
        ])->save();

        return redirect()->back();
    }


    public function destroy($id)
    {
        $dynamicPricing = DynamicPricing::findOrFail($id);
        $dynamicPricing->delete();

        return redirect()->back();
    }
}
