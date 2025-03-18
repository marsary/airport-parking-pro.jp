<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appliedCoupons' => 'nullable|array',
            'subtotal' => 'int',
            'discounts' => 'nullable|array',
            'adjustments' => 'nullable|array',
            'tax' => 'int',
            'totalAmount' => 'int',
            'totalChange' => 'int',
            'totalPay' => 'int',
            'cash' => 'nullable|int',
            'credit' => 'nullable|array',
            'electronicMoney' => 'nullable|array',
            'qrCode' => 'nullable|array',
            'giftCertificates' => 'nullable|int',
            'travelAssistance' => 'nullable|array',
            'voucher' => 'nullable|array',
            'others' => 'nullable|array',
        ];
    }


    public function attributes()
    {
        return [
            'appliedCoupons' => '割引クーポン',
            'subtotal' => '小計',
            'discounts' => '値引き',
            'adjustments' => '調整',
            'tax' => '消費税',
            'totalAmount' => 'お支払い合計（税込）',
            'totalChange' => 'お釣り',
            'totalPay' => '実際の支払額合計',
            'cash' => '現金',
            'credit' => 'クレジット',
            'electronicMoney' => '電子マネー',
            'qrCode' => 'QRコード',
            'giftCertificates' => '商品券',
            'travelAssistance' => '旅行支援',
            'voucher' => 'バウチャー',
            'others' => 'その他',
        ];
    }
}
