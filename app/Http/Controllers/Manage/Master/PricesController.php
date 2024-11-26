<?php

namespace App\Http\Controllers\Manage\Master;

use App\Enums\CarSize as EnumsCarSize;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\CarSizeRateRequest;
use App\Http\Requests\Manage\Master\PricesRequest;
use App\Models\CarSize;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PricesController extends Controller
{
    //
    public function index()
    {
        $prices = Price::where('office_id', config('const.commons.office_id'))
            ->orderBy('start_date', 'desc')->get();

        $carSizes = CarSize::where('office_id', config('const.commons.office_id'))
            ->get();

        $carSizeMap = [];
        foreach($carSizes as $carSize) {
            $carSizeMap[$carSize->name] = $carSize->carSizePriceRate?->rate;
        }

        return view('manage.master.basic_prices', [
            'prices' => $prices,
            'carSizes' => $carSizes,
        ]);
    }


    public function store(PricesRequest $request)
    {
        $price = Price::create([
            'office_id' => config('const.commons.office_id'),
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


    public function update(PricesRequest $request, $id)
    {
        $price = Price::findOrFail($id);
        $price->fill([
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
        $price = Price::findOrFail($id);
        $price->delete();

        return redirect()->back();
    }


}
