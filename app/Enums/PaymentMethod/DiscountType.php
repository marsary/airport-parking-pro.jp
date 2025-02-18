<?php

namespace App\Enums\PaymentMethod;

enum DiscountType: int
{
    // 決済画面の値引き率 1：8％、2：10％、3：非課税

    // 基本情報
    case EIGHT_PERCENT = 1;
    case TEN_PERCENT = 2;
    case EXEMPT = 3;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            DiscountType::EIGHT_PERCENT => '8%値引き',
            DiscountType::TEN_PERCENT => '10%値引き',
            DiscountType::EXEMPT => '非課税値引き',
        };
    }

    public static function getByLabel($label): DiscountType
    {
        return match($label)
        {
            '8%値引き' => DiscountType::EIGHT_PERCENT,
            '10%値引き' => DiscountType::TEN_PERCENT,
            '非課税値引き' => DiscountType::EXEMPT,
        };
    }


    // rate:適用税率
    public function rate(): string
    {
        return match($this)
        {
            DiscountType::EIGHT_PERCENT => 0.08,
            DiscountType::TEN_PERCENT => 0.1,
            DiscountType::EXEMPT => 0,
        };
    }
}
