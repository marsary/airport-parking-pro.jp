<?php

namespace App\Enums;

enum LimitOverStatus: int
{
    // 基本情報
    case VACANT = 1;
    case HALF_FILLED = 2;
    case FULL = 3;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            LimitOverStatus::VACANT => '〇',
            LimitOverStatus::HALF_FILLED => '△',
            LimitOverStatus::FULL => '×',
        };
    }
}
