<?php
namespace App\Services\Printers;

use App\Models\Deal;
use App\Models\Payment;
use Carbon\Carbon;

class ReceiptPrintable extends AbstractPrintable
{
    public $deal;
    public $payment;
    protected array $defaultConfig = [
        'format' => 'A4',
        // 'format' => [210, 297], // 210×297mm
        // ... 領収書用のマージンなど ...
        // 'margin_top' => 0,
        // 'margin_bottom' => 0,
        // 'margin_left' => 5,
        // 'margin_right' => 5,
    ];

    public function __construct(Deal $deal, Payment $payment)
    {
        $this->deal = $deal;
        $this->payment = $payment;
        // 領収書用のA4設定など
        $config = $this->defaultConfig;
        parent::__construct($config, 'manage.receipt.receipt'); // 領収書用のビューを指定
    }

    // 領収書印刷に必要なデータ取得ロジック
    public function getData(): array
    {
        $couponDetails = [];
        $paymentDetails = collect([]);
        $couponTotal = null;
        foreach ($this->payment->paymentDetails as $paymentDetail) {
            if($paymentDetail->coupon()->exists()) {
                $description = $paymentDetail->coupon->name . $paymentDetail->total_price . '円';

                $couponDetails[] = $description;
                $couponTotal += $paymentDetail->total_price;
            } else {
                $paymentDetails->push($paymentDetail);
            }
        }


        // 領収書に必要なデータ
        return [
            'receiptTime' => Carbon::now(),
            'deal' => $this->deal,
            'payment' => $this->payment,
            'office' => $this->payment->office,
            'memberCar' => $this->deal->memberCar,
            'member' => $this->payment->member,
            'paymentDetails' => $paymentDetails,
            'couponDetails' => $couponDetails,
            'couponTotal' => $couponTotal,
        ];
    }
}
