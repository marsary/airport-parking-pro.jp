<?php

namespace App\Enums;

enum RegiDisplayFlag: int
{
    // 0：予約時のみ表示、1：レジにも表示

    // 基本情報
    case RESERVE_ONLY = 0;
    case REGI = 1;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            RegiDisplayFlag::RESERVE_ONLY => '予約時のみ表示',
            RegiDisplayFlag::REGI => 'レジにも表示',
        };
    }
}
