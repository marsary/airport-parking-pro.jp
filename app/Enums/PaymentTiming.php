<?php

namespace App\Enums;

enum PaymentTiming: int
{
    // 1: 受付時、2: 前払い、3: 後払い

    // 基本情報
    case RECEPTION = 1;
    case ADVANCE = 2;
    case LATER = 3;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            PaymentTiming::RECEPTION => '受付時',
            PaymentTiming::ADVANCE => '前払い',
            PaymentTiming::LATER => '後払い',
        };
    }
}
