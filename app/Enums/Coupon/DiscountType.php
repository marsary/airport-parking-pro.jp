<?php

namespace App\Enums\Coupon;

enum DiscountType: int
{
    // 1：円、2：%

    // 基本情報
    case YEN = 1;
    case PERCENT = 2;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            DiscountType::YEN => '円',
            DiscountType::PERCENT => '%',
        };
    }
}
