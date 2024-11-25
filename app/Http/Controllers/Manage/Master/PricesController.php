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
}
