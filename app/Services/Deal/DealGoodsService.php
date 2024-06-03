<?php
namespace App\Services\Deal;

use App\Enums\TaxType;
use App\Models\Deal;

class DealGoodsService
{
    /** @var Deal */
    public $deal;

    function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }


    public function sumTotals()
    {
        $totalPrices = new TotalPrices;
        $totalPrices->tenPercentAmountExcludingTax = $this->deal->price;
        $totalPrices->tenPercentTax = $this->deal->tax;

        if($this->deal->dealGoods()->count() > 0) {
            foreach ($this->deal->dealGoods as $dealGood) {
                $good = $dealGood->good;
                $this->addToTotalPrices($totalPrices, $dealGood->total_price, $dealGood->total_tax, $good->tax_type);
            }
        }
        $totalPrices->setTotalAmount();
        return $totalPrices;
    }


    protected function addToTotalPrices(TotalPrices $totalPrices, $price, $tax, $taxType)
    {
        switch ($taxType) {
            case TaxType::EIGHT_PERCENT->value:
                $totalPrices->eightPercentAmountExcludingTax += $price;
                $totalPrices->eightPercentTax += $tax;
                break;
            case TaxType::EIGHT_PERCENT->value:
                $totalPrices->tenPercentAmountExcludingTax += $price;
                $totalPrices->tenPercentTax += $tax;
                break;
            default:
                $totalPrices->NoTaxAmount += $price;
                break;
        }
    }
}
