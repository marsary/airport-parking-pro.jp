<?php
namespace App\Services\Settings;

use App\Enums\TaxType;
use App\Models\SeasonPriceSetting;
use Carbon\Carbon;

class SeasonPriceSettingService
{
    public static function getSeasonPrice(Carbon|string $loadDate, Carbon|string $unloadDate)
    {
        $taxType = TaxType::TEN_PERCENT;
        if(! $loadDate instanceof Carbon) {
            $loadDate = Carbon::parse($loadDate);
        }
        if(! $unloadDate instanceof Carbon) {
            $unloadDate = Carbon::parse($unloadDate);
        }

        $seasonPriceSum = (int) SeasonPriceSetting::where('office_id', config('const.commons.form_office_id'))
            ->whereDate('target_date', $loadDate->toDateString())
            ->sum('season_price') ?? 0;


        $seasonPriceTax = roundTax((float) $taxType->rate() * $seasonPriceSum);

        return [
            'season_price' => $seasonPriceSum,
            'season_price_tax' => $seasonPriceTax,
        ];
    }
}
