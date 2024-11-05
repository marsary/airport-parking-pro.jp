<?php

namespace App\Enums\Coupon;

enum LimitFlg: int
{
    // 0：1回、1：複数回

    // 基本情報
    case ONCE = 0;
    case MULTIPLE = 1;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            LimitFlg::ONCE => '1回',
            LimitFlg::MULTIPLE => '複数回',
        };
    }
}
