<?php

namespace App\Enums;

enum TaxType: int
{
    // 税種別	tax_type	tinyInt	Yes		1：8％、2：10％、3：対象外

    // 基本情報
    case EIGHT_PERCENT = 1;
    case TEN_PERCENT = 2;
    case EXEMPT = 3;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            TaxType::EIGHT_PERCENT => '8％',
            TaxType::TEN_PERCENT => '10％',
            TaxType::EXEMPT => '対象外',
        };
    }

    public function rate(): string
    {
        return match($this)
        {
            TaxType::EIGHT_PERCENT => 0.08,
            TaxType::TEN_PERCENT => 0.1,
            TaxType::EXEMPT => 0,
        };
    }
}
