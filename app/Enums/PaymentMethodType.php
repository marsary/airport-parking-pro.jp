<?php

namespace App\Enums;

enum PaymentMethodType: int
{
    // 支払方法マスタ区分
    // 1：現金、2：クレジット、３：電子マネー、４：QRコード、５：商品券、
    // ６：旅行支援、７：バウチャー、８：その他、９：値引き、１０：調整

    // 基本情報
    case CASH = 1;
    case CREDIT = 2;
    case E_MONEY = 3;
    case QR_CODE = 4;
    case GIFT_CERTIFICATE = 5;
    case TRAVEL_ASSIST = 6;
    case VOUCHER = 7;
    case OTHER = 8;
    case DISCOUNT = 9;
    case ADJUSTMENT = 10;

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
            PaymentMethodType::DISCOUNT => '値引き',
            PaymentMethodType::ADJUSTMENT => '調整',
        };
    }

    public function symbol(): string
    {
        return match($this)
        {
            PaymentMethodType::CASH => 'cash',
            PaymentMethodType::CREDIT => 'credit',
            PaymentMethodType::E_MONEY => 'electronicMoney',
            PaymentMethodType::QR_CODE => 'qrCode',
            PaymentMethodType::GIFT_CERTIFICATE => 'giftCertificates',
            PaymentMethodType::TRAVEL_ASSIST => 'travelAssistance',
            PaymentMethodType::VOUCHER => 'voucher',
            PaymentMethodType::OTHER => 'others',
            PaymentMethodType::DISCOUNT => 'discount',
            PaymentMethodType::ADJUSTMENT => 'adjustment',
        };
    }

    public function symbolToId(): int
    {
        return match($this)
        {
            'cash' => PaymentMethodType::CASH->value,
            'credit' => PaymentMethodType::CREDIT->value,
            'electronicMoney' => PaymentMethodType::E_MONEY->value,
            'qrCode' => PaymentMethodType::QR_CODE->value,
            'giftCertificates' => PaymentMethodType::GIFT_CERTIFICATE->value,
            'travelAssistance' => PaymentMethodType::TRAVEL_ASSIST->value,
            'voucher' => PaymentMethodType::VOUCHER->value,
            'others' => PaymentMethodType::OTHER->value,
            'discount' => PaymentMethodType::DISCOUNT->value,
            'adjustment' => PaymentMethodType::ADJUSTMENT->value,
        };
    }
}
