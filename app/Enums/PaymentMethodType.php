<?php

namespace App\Enums;

enum PaymentMethodType: int
{
    // 支払方法マスタ区分
    // 1：現金、2：クレジット、３：電子マネー、４：QRコード、５：商品券、６：旅行支援、７：バウチャー、８：その他

    // 基本情報
    case CASH = 1;
    case CREDIT = 2;
    case E_MONEY = 3;
    case QR_CODE = 4;
    case GIFT_CERTIFICATE = 5;
    case TRAVEL_ASSIST = 6;
    case VOUCHER = 7;
    case OTHER = 8;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            PaymentMethodType::CASH => '現金',
            PaymentMethodType::CREDIT => 'クレジット',
            PaymentMethodType::E_MONEY => '電子マネー',
            PaymentMethodType::QR_CODE => 'QRコード',
            PaymentMethodType::GIFT_CERTIFICATE => '商品券',
            PaymentMethodType::TRAVEL_ASSIST => '旅行支援',
            PaymentMethodType::VOUCHER => 'バウチャー',
            PaymentMethodType::OTHER => 'その他',
        };
    }
}
