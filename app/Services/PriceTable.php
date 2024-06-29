<?php
namespace App\Services;

use App\Enums\TaxType;
use App\Models\AgencyPrice;
use App\Models\Coupon;
use App\Models\Price;
use Carbon\Carbon;

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

        if(isset($agencyId) && !empty($agencyId)) {
            $price = AgencyPrice::where('agency_id', $agencyId)
                ->where('office_id', config('const.commons.office_id'))->first();
        } else {
            $price = Price::where('office_id', config('const.commons.office_id'))->first();
        }
        /** @var Price|AgencyPrice $price */
        $table->subTotal = $price->base_price;
        $rowDate = clone $table->loadDate;
        for ($i=0; $i < $table->numDays; $i++) {
            $tempDate = clone $rowDate;
            $rowPrice = $price->getPriceAt($i + 1);
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

    public static function calcAdditionalCharge(Carbon $loadDate,Carbon $unloadDate, $pendingDays, $agencyId = null)
    {
        if(isset($agencyId)) {
            $price = AgencyPrice::where('agency_id', $agencyId)
                ->where('office_id', config('const.commons.office_id'))->first();
        } else {
            $price = Price::where('office_id', config('const.commons.office_id'))->first();
        }

        $numDays = (int) ceil($unloadDate->diffInDays($loadDate->subDay(), true));

        /** @var Price|AgencyPrice $price */
        $additionalCharge = 0;

        for ($i=$numDays; $i < $numDays + $pendingDays; $i++) {
            $dayPrice = $price->getPriceAt($i + 1);
            $additionalCharge += $dayPrice;
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
