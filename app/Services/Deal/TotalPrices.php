<?php
namespace App\Services\Deal;


class TotalPrices
{
    public $eightPercentAmountExcludingTax = 0;
    public $eightPercentTax = 0;
    public $tenPercentAmountExcludingTax = 0;
    public $tenPercentTax = 0;
    public $NoTaxAmount = 0;
    public $totalAmount = 0;

    public function setTotalAmount()
    {
        $this->totalAmount = $this->eightPercentAmountExcludingTax
            + $this->eightPercentTax
            + $this->tenPercentAmountExcludingTax
            + $this->tenPercentTax
            + $this->NoTaxAmount
            + $this->totalAmount;
    }
}
