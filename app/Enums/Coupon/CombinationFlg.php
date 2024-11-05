<?php

namespace App\Enums\Coupon;

enum CombinationFlg: int
{
    // 併用可否フラグ
    // 0：不可、1：可

    // 基本情報
    case DISABLED = 0;
    case ENABLED = 1;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            CombinationFlg::DISABLED => '不可',
            CombinationFlg::ENABLED => '可',
        };
    }
}
