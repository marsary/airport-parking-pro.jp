<?php

namespace App\Enums;

enum TransactionType: int
{
    // 1: 駐車場利用、2: 商品購入

    // 基本情報
    case PARKING = 1;
    case PURCHASE_ONLY = 2;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            TransactionType::PARKING => '駐車場利用',
            TransactionType::PURCHASE_ONLY => '商品購入',
        };
    }
}
