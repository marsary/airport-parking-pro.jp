<?php
namespace App\Services;

use App\Enums\TaxType;
use App\Models\AgencyPrice;
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
    /** @var int */
    public $tax;
    /** @var int */
    public $total;


    function __construct()
    {
        $this->taxType = TaxType::TEN_PERCENT;
        $this->taxLabel = TaxType::TEN_PERCENT->label();
    }

    public static function getPriceTable($loadDate, $unloadDate, $agencyId = null)
    {
        $table = new self;
        if(! $loadDate instanceof Carbon) {
            $loadDate = Carbon::parse($loadDate);
        }
        if(! $unloadDate instanceof Carbon) {
            $unloadDate = Carbon::parse($unloadDate);
        }
        $table->loadDate = $loadDate;
        $table->unloadDate = $unloadDate->addDay();
        $table->numDays = (int) ceil($unloadDate->diffInDays($loadDate, true));

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
        $table->tax = roundTax($table->taxType->rate() * $table->subTotal);
        $table->total = $table->subTotal + $table->tax;

        return $table;
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
