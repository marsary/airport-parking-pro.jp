<?php
namespace App\Services\Goods;

use App\Enums\TaxType;
use App\Models\Good;

class GoodPrice
{
    public static function getTotalPrice(int $taxType, int $price)
    {
        $taxRate = TaxType::tryFrom($taxType);

        if(!$taxRate || $taxRate == TaxType::EXEMPT) {
            return $price;
        }
        $tax = roundTax($taxRate->rate() * $price);
        return $price + $tax;
    }
}
