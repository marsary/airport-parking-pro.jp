<?php
namespace App\Services;

use App\Models\AgencyPrice;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class PriceData
{
    /** @var Collection<Price|AgencyPrice> */
    public $pricesTable;
    public $numDays;
    public $loadDate;
    public $unloadDate;
    public $agencyId;

    function __construct(Carbon $loadDate, Carbon $unloadDate, $numDays, $agencyId = null)
    {
        $this->loadDate = $loadDate;
        $this->unloadDate = $unloadDate;
        $this->numDays = $numDays;
        $this->agencyId = $agencyId;

        if(isset($agencyId) && !empty($agencyId)) {
            $this->pricesTable = AgencyPrice::where('agency_id', $agencyId)
                ->where('office_id', config('const.commons.office_id'))
                ->where(function ($query) use ($loadDate, $unloadDate) {
                    $query->where('start_date', '<=', $unloadDate)
                          ->where('end_date', '>=', $loadDate);
                })
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            $this->pricesTable = Price::where('office_id', config('const.commons.office_id'))
            ->where(function ($query) use ($loadDate, $unloadDate) {
                $query->whereDate('start_date', '<=', $unloadDate)
                      ->whereDate('end_date', '>=', $loadDate);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        }
    }


    public function getPriceAt(Carbon $currentDate, int $dayNum)
    {
        // 該当する料金表を検索
        /** @var Price|AgencyPrice */
        $matchedPriceRecord = $this->pricesTable->first(function ($price) use ($currentDate) {
            return $currentDate->between($price->start_date, $price->end_date);
        });
        if(!$matchedPriceRecord) {
            return;
        }

        return $matchedPriceRecord->getPriceAt($dayNum);
    }

    public function getBasePrice()
    {
        // 最新のベース料金を取得
        return $this->pricesTable
            ->sortByDesc('updated_at') // id で降順に並べ替え
            ->first()?->base_price;    // 最初のレコードの base_price を取得
    }
}
