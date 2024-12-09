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
        $dynamicPricingsMap = DynamicPricing::where('office_id', config('const.commons.office_id'))
            ->orderBy('sort')->get()->mapWithKeys(function ($item) {
                return [$item->sort => $item];
            })->all();

        $dynamicPricings = [];
        for ($sort=1; $sort < 7; $sort++) {
            if(isset($dynamicPricingsMap[$sort])) {
                $dynamicPricings[] = $dynamicPricingsMap[$sort];
            } else {
                // 未作成の列は、ダミーのデータ
                $dynamicPricings[] = DynamicPricing::make([
                    'sort' => $sort,
                    'name' => 'DP料金' . $sort,
                    'p10' => 0,
                    'p20' => 0,
                    'p30' => 0,
                    'p40' => 0,
                    'p50' => 0,
                    'p60' => 0,
                    'p70' => 0,
                    'p80' => 0,
                    'p90' => 0,
                    'p100' => 0,
                    'p110' => 0,
                    'p120' => 0,
                    'p130' => 0,
                    'p131' => 0,
                ]);
            }
        }

        return view('manage.master.dynamic_pricings', [
            'dynamicPricings' => $dynamicPricings,
        ]);
    }


    public function store(DynamicPricingsRequest $request)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->input('sort');
        // dd($request->all());
        $dynamicPricing = DynamicPricing::create([
            'office_id' => config('const.commons.office_id'),
            'sort' => $request->sort,
            'name' => $request->{$recordKey}['name'],
            'p10' => $request->{$recordKey}['p10'],
            'p20' => $request->{$recordKey}['p20'],
            'p30' => $request->{$recordKey}['p30'],
            'p40' => $request->{$recordKey}['p40'],
            'p50' => $request->{$recordKey}['p50'],
            'p60' => $request->{$recordKey}['p60'],
            'p70' => $request->{$recordKey}['p70'],
            'p80' => $request->{$recordKey}['p80'],
            'p90' => $request->{$recordKey}['p90'],
            'p100' => $request->{$recordKey}['p100'],
            'p110' => $request->{$recordKey}['p110'],
            'p120' => $request->{$recordKey}['p120'],
            'p130' => $request->{$recordKey}['p130'],
            'p131' => $request->{$recordKey}['p131'],
        ]);

        return redirect()->back();
    }


    public function update(DynamicPricingsRequest $request, $id)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->input('sort');

        $dynamicPricing = DynamicPricing::findOrFail($id);
        $dynamicPricing->fill([
            'name' => $request->{$recordKey}['name'],
            'p10' => $request->{$recordKey}['p10'],
            'p20' => $request->{$recordKey}['p20'],
            'p30' => $request->{$recordKey}['p30'],
            'p40' => $request->{$recordKey}['p40'],
            'p50' => $request->{$recordKey}['p50'],
            'p60' => $request->{$recordKey}['p60'],
            'p70' => $request->{$recordKey}['p70'],
            'p80' => $request->{$recordKey}['p80'],
            'p90' => $request->{$recordKey}['p90'],
            'p100' => $request->{$recordKey}['p100'],
            'p110' => $request->{$recordKey}['p110'],
            'p120' => $request->{$recordKey}['p120'],
            'p130' => $request->{$recordKey}['p130'],
            'p131' => $request->{$recordKey}['p131'],
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
