<?php

namespace App\Enums;

enum GoodCategoryType: int
{
    // 1：出庫までに作業が必要、2：出庫までに作業が不要

    // 基本情報
    case WORK_BEFORE_UNLOAD = 1;
    case NO_WORK_BEFORE_UNLOAD = 2;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            GoodCategoryType::WORK_BEFORE_UNLOAD => '出庫までに作業が必要',
            GoodCategoryType::NO_WORK_BEFORE_UNLOAD => '出庫までに作業が不要',
        };
    }
}
