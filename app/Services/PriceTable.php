<?php
namespace App\Services;

use App\Enums\TaxType;
use App\Models\AgencyPrice;
use App\Models\Coupon;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PriceTable
{
    /** @var PriceRow[] */
    public $rows = [];
    public $numDays;
    public $loadDate;
    public $unloadDate;
    public $taxType;
    public $taxLabel;
    /** @var int */
    public $subTotal;
    public $discountedSubTotal;
    public $coupons = [];
    /** @var int */
    public $tax;
    /** @var int */
    public $total;


    function __construct()
    {
        $this->taxType = TaxType::TEN_PERCENT;
        $this->taxLabel = TaxType::TEN_PERCENT->label();
    }

    public static function getPriceTable($loadDate, $unloadDate, $couponIds = [], $agencyId = null)
    {
        $table = new self;
        if(! $loadDate instanceof Carbon) {
            $loadDate = Carbon::parse($loadDate);
        }
        if(! $unloadDate instanceof Carbon) {
            $unloadDate = Carbon::parse($unloadDate);
        }
        $table->loadDate = $loadDate;
        $unloadDateCloned = clone $unloadDate;
        $table->unloadDate = $unloadDateCloned->addDay();
        $table->numDays = (int) ceil($table->unloadDate->diffInDays($loadDate, true));

        $priceData = new PriceData($loadDate, $unloadDate, $table->numDays, $agencyId);

        /** @var Price|AgencyPrice $price */
        $table->subTotal = $priceData->getBasePrice();
        $rowDate = clone $table->loadDate;
        for ($i=0; $i < $table->numDays; $i++) {
            $tempDate = clone $rowDate;
            $rowPrice = $priceData->getPriceAt($rowDate, $i + 1);
            $table->subTotal += $rowPrice;
            $row = new PriceRow($tempDate, $rowPrice, $i);
            $table->rows[] = $row;

            $rowDate->addDay();
        }
        $table->discountedSubTotal = $table->subTotal;
        $table->tax = roundTax($table->taxType->rate() * $table->discountedSubTotal);
        $table->total = $table->discountedSubTotal + $table->tax;

        return $table;
    }

    public static function calcAdditionalCharge(Carbon $loadDate,Carbon $unloadDate, $pendingDays, $today, $agencyId = null)
    {
        $numDays = (int) ceil($unloadDate->diffInDays($loadDate->subDay(), true));
        $priceData = new PriceData($unloadDate, $today, $numDays, $agencyId);

        /** @var PriceData $priceData */
        $additionalCharge = 0;
        $currentDate = $unloadDate->copy();
        $currentDate->addDay(); // 出庫日の翌日から
        for ($i=$numDays; $i < $numDays + $pendingDays; $i++) {
            $dayPrice = $priceData->getPriceAt($currentDate, $i + 1);
            $additionalCharge += $dayPrice;

            // 日付を次の日に進める
            $currentDate->addDay();
        }

        return $additionalCharge;
    }

}


class PriceRow
{
    public $rowNo;
    /** @var Carbon */
    public $date;
    public $price;

    function __construct($date, $price, $rowNo)
    {
        $this->date = $date;
        $this->price = $price;
        $this->$rowNo = $rowNo;
    }
}

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
                ->orderBy('id')
                ->get();
        } else {
            $this->pricesTable = Price::where('office_id', config('const.commons.office_id'))
            ->where(function ($query) use ($loadDate, $unloadDate) {
                $query->whereDate('start_date', '<=', $unloadDate)
                      ->whereDate('end_date', '>=', $loadDate);
            })
            ->orderBy('id')
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
            ->sortByDesc('id') // id で降順に並べ替え
            ->first()?->base_price;    // 最初のレコードの base_price を取得
    }
}
