<?php
namespace App\Services\View;


class Receipt
{
    public static function getPaymentDetail($paymentDeails, $idx)
    {
        $pay = '';
        $detail = $paymentDeails->get($idx);
        if($detail) {
            $pay .= ($detail->total_price ? number_format($detail->total_price) . ' 円,' : '');
            $pay .= ' ' . $detail->paymentMethod->name;
        }
        return $pay;
    }
}
