<?php

namespace App\Enums;

enum CarSize: int
{
    // 1:普通車, 2:大型車

    // 基本情報
    case MEDIUM = 1;
    case LARGE = 2;

    public const SIZE_TYPE_IDS = [1, 2];

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            CarSize::MEDIUM => '普通車',
            CarSize::LARGE => '大型車',
        };
    }
}
