<?php

namespace App\Enums;

enum GeneralStatus: int
{
    // 1:有効、2:無効

    // 基本情報
    case Enabled = 1;
    case Disabled = 2;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            GeneralStatus::Enabled => '有効',
            GeneralStatus::Disabled => '無効',
        };
    }
}
