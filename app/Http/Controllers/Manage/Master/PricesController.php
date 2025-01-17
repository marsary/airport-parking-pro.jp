<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\CarSizeRateRequest;
use App\Http\Requests\Manage\Master\PricesRequest;
use App\Models\CarSize;
use App\Models\Price;
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
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_0";
        $price = Price::create([
            'office_id' => config('const.commons.office_id'),
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


    public function update(PricesRequest $request, $id)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->route()->parameter('price', 0);
        $price = Price::findOrFail($id);
        $price->fill([
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
        $price = Price::findOrFail($id);
        $price->delete();

        return redirect()->back();
    }


    public function storeCarSizeRate(CarSizeRateRequest $request)
    {
        foreach (Arr::get($request->validated(), 'carsize_price_rates', [])  as $carSizeName => $value) {
            $carSize = CarSize::where('office_id', config('const.commons.office_id'))
                ->where('name', $carSizeName )->first();

            if(!$carSize) {
                continue;
            }
            if($carSize->carSizePriceRate()->exists()) {
                $carSize->carSizePriceRate->fill(['rate' => $value])->save();
            } else {
                $carSize->carSizePriceRate()->create([
                    'office_id' => config('const.commons.office_id'),
                    'rate' => $value,
                ]);
            }
        }

        return redirect()->back();
    }
}
