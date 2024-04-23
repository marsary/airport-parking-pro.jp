<?php

namespace App\Enums;

enum DealStatus: int
{
    // 1：未入庫、2：入庫済、3：出庫済、4：保留、5：予約キャンセル

    // 基本情報
    case NOT_LOADED = 1;
    case LOADED = 2;
    case UNLOADED = 3;
    case PENDING = 4;
    case CANCEL = 5;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            DealStatus::NOT_LOADED => '未入庫',
            DealStatus::LOADED => '入庫済',
            DealStatus::UNLOADED => '出庫済',
            DealStatus::PENDING => '保留',
            DealStatus::CANCEL => '予約キャンセル',
        };
    }
}
