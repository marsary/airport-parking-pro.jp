<?php
namespace App\Services\Deal;

use App\Enums\GeneralStatus;
use App\Enums\PaymentMethodType;
use App\Enums\TaxType;
use App\Models\CashRegister;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentService
{
    /** @var Deal */
    public $deal;
    private $data;


    /** @var Payment */
    private $payment;

    /** @var CashRegister */
    private $register;

    function __construct($dealId, array $data)
    {
        $this->deal = Deal::findOrFail($dealId);
        $this->data = $data;
        $this->payment = $this->deal->payment;
        $this->register = CashRegister::where('office_id', config('const.commons.office_id'))->where('status', GeneralStatus::Enabled->value)->first();
    }

    public function save()
    {
        if($this->payment) {
            $this->updatePayment();
        } else {
            $this->createPayment();
        }
    }

    private function createPayment()
    {
        $this->payment = $this->deal->payment()->create([
            'payment_code' => Str::random(10),
            'payment_date' => Carbon::now(),
            'cash_register_id' => $this->register->id,
            'office_id' => $this->deal->office_id,
            'deal_id' => $this->deal->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name,
            'member_id' => $this->deal->member_id,
            'load_date' => $this->deal->load_date,
            'unload_date_plan' => $this->deal->unload_date_plan,
            'unload_date' => $this->deal->unload_date,
            'days' => $this->deal->num_days,
            'price' => $this->deal->price,
            'goods_total_price' => $this->deal->dealGoodsTotalPrice(),
            'total_price' => $this->deal->total_price,
            'total_tax' => $this->deal->total_tax,
            // 値引き・調整は含めず？
            'demand_price' => $this->deal->total_price + $this->deal->total_tax,
            'total_pay' => $this->data['totalAmount'], // 支払合計金額
            'cash_enter' => $this->data['totalPay'], // レジ入金額
            'cash_change' => $this->data['totalChange'], // レジ釣銭額
            'cash_add' => $this->data['totalPay'] - $this->data['totalChange'], // レジ追加金額
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->createPaymentGoods();
        // 決済支払い方法
        $this->createPaymentDetails();
    }

    private function createPaymentGoods()
    {
        foreach ($this->deal->dealGoods as $dealGood) {
            $good = $dealGood->good;
            $this->payment->paymentGoods()->create([
                'payment_id' => $this->payment->id,
                'good_category_id' => $good->good_category_id,
                'deal_good_id' => $dealGood->id,
                'name' => $good->name,
                'price' => $good->price,
                'tax' => roundTax(TaxType::tryFrom($good->tax_type)?->rate() * $good->price),
                'tax_type' => $good->tax_type,
                'num' => $dealGood->num,
                'total_price' => $dealGood->total_price,
                'total_tax' => $dealGood->total_tax,
            ]);
        }

    }

    private function createPaymentDetails()
    {
        foreach (PaymentMethodType::cases() as $paymentMethodType) {
            $symbol = $paymentMethodType->symbol();
            if(isset($this->data[$symbol])) {
                $categoryData = $this->data[$symbol];
                if(is_array($categoryData)) {
                    if($symbol == PaymentMethodType::COUPON->symbol()) {
                        // 適用クーポン
                        $this->createCouponDetail($categoryData);
                    } else {
                        foreach ($categoryData as $itemName => $itemValue) {
                            $paymentMethod = PaymentMethod::getByName($itemName);
                            $this->payment->paymentDetails()->create([
                                'payment_id' => $this->payment->id,
                                'payment_method_id' => $paymentMethod->id,
                                'total_price' => (int) $itemValue,
                            ]);
                        }
                    }
                } else {
                    $paymentMethod = PaymentMethod::getByName($paymentMethodType->label());
                    $this->payment->paymentDetails()->create([
                        'payment_id' => $this->payment->id,
                        'payment_method_id' => $paymentMethod->id,
                        'total_price' => (int) $categoryData,
                    ]);
                }
            }
        }
    }

    private function createCouponDetail(array $appliedCoupons)
    {
        foreach ($appliedCoupons as $couponId => $price) {
            $paymentMethod = PaymentMethod::getByName(PaymentMethodType::COUPON->label());
            $this->payment->paymentDetails()->create([
                'payment_id' => $this->payment->id,
                'payment_method_id' => $paymentMethod->id,
                'total_price' => (int) $price,
                'coupon_id' => $couponId,
            ]);
        }
    }

    private function updatePayment()
    {
        $this->payment->fill([
            'payment_date' => Carbon::now(),
            'cash_register_id' => $this->register->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name,
            'unload_date_plan' => $this->deal->unload_date_plan,
            'unload_date' => $this->deal->unload_date,
            'days' => $this->deal->num_days,
            'price' => $this->deal->price,
            'goods_total_price' => $this->deal->dealGoodsTotalPrice(),
            'total_price' => $this->deal->total_price,
            'total_tax' => $this->deal->total_tax,
            // 値引き・調整は含めず？
            'demand_price' => $this->deal->total_price + $this->deal->total_tax,
            'total_pay' => $this->data['totalAmount'], // 支払合計金額
            'cash_enter' => $this->data['totalPay'], // レジ入金額
            'cash_change' => $this->data['totalChange'], // レジ釣銭額
            'cash_add' => $this->data['totalPay'] - $this->data['totalChange'], // レジ追加金額
            'updated_by' => Auth::id(),
        ])->save();

        $this->payment->paymentGoods()->delete();
        $this->createPaymentGoods();
        // 決済支払い方法
        $this->payment->paymentDetails()->delete();
        $this->createPaymentDetails();
    }

}
