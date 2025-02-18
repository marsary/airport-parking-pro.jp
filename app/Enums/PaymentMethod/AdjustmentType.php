<?php

namespace App\Enums\PaymentMethod;

enum AdjustmentType: int
{
    // 決済画面の調整率 1：8％、2：10％、3：非課税

    // 基本情報
    case EIGHT_PERCENT = 1;
    case TEN_PERCENT = 2;
    case EXEMPT = 3;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            AdjustmentType::EIGHT_PERCENT => '8%調整',
            AdjustmentType::TEN_PERCENT => '10%調整',
            AdjustmentType::EXEMPT => '非課税調整',
        };
    }

    public static function getByLabel($label): AdjustmentType
    {
        return match($label)
        {
            '8%調整' => AdjustmentType::EIGHT_PERCENT,
            '10%調整' => AdjustmentType::TEN_PERCENT,
            '非課税調整' => AdjustmentType::EXEMPT,
        };
    }


    // rate:適用税率
    public function rate(): string
    {
        return match($this)
        {
            AdjustmentType::EIGHT_PERCENT => 0.08,
            AdjustmentType::TEN_PERCENT => 0.1,
            AdjustmentType::EXEMPT => 0,
        };
    }
}
