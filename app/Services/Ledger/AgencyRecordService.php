<?php
namespace App\Services\Ledger;

use App\Enums\PaymentMethodType;
use App\Models\AgencyRecord;
use App\Models\Deal;
use App\Services\PriceData;
use ErrorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AgencyRecordService
{
    public $deals;

    function __construct(Collection $deals)
    {
        $this->deals = $deals;
    }

    public function saveData()
    {
        $savedCount = 0;


        DB::transaction(function () use(&$savedCount) {
            foreach ($this->deals as $deal) {
                $unloadDate = $deal->unload_date ?? $deal->unload_date_plan;

                $priceData = new PriceData($deal->load_date, $unloadDate, $deal->num_days, $deal->agency_id);

                $row = new AgencyRecord();

                $row->office_id = $deal->office_id;
                $row->agency_id = $deal->agency_id;
                $row->agency_name = $deal->agency->name;
                $row->receipt_code = $deal->receipt_code ?? '';
                $row->member_code = $deal->member->member_code;
                $row->deal_id = $deal->id;
                $row->reserve_name = $deal->name;
                $row->reserve_name_kana = $deal->kana;
                $row->load_date = $deal->load_date;
                $row->unload_date = $deal->unload_date;
                $row->unload_date_plan = $deal->unload_date_plan;
                $row->unload_time_plan = $deal->unload_time_plan;
                $row->num_days = (int) ceil($unloadDate->diffInDays($deal->load_date->subDay(), true));
                $row->num_days_plan = (int) ceil($deal->unload_date_plan->diffInDays($deal->load_date->subDay(), true));

                $row->airline_name = $deal->arrivalFlight?->airline?->name;
                $row->dep_airport_name = $deal->arrivalFlight?->depAirport?->name;
                $row->flight_name = $deal->arrivalFlight?->name;
                $row->arrive_date = $deal->arrivalFlight?->arrive_date;
                $row->arrive_time = $deal->arrivalFlight?->arrive_time;
                $row->car_name = $deal->memberCar?->car?->name;
                $row->car_maker_name = $deal->memberCar?->car?->carMaker?->name;
                $row->car_color_name = $deal->memberCar?->carColor?->name;
                $row->car_number = $deal->memberCar?->number;

                // 入庫時利用金額
                $row->price = $deal->total_price;
                // 代理店固定料金
                $row->base_price = $priceData->getBasePrice();

                [
                    'couponNames' => $couponNames,
                    'couponTotal' => $couponTotal,
                    'hasCoupon' => $hasCoupon,
                    'hasVoucher' => $hasVoucher,
                    'payNotReal' => $payNotReal,
                ] = $this->getPaymentDetails($deal);

                // 入庫時割引金額
                $row->dt_price_load = $couponTotal;
                // 値引調整金額
                $row->pay_not_real = $payNotReal;

                $row->has_voucher = $hasVoucher;
                if($hasCoupon) {
                    $row->coupon_name = implode(', ', $couponNames);
                }

                $row->margin_rate = $deal->agency->margin_rate;

                $row->save();
                $savedCount++;
            }
        });



        return $savedCount;
    }

    private function getPaymentDetails(Deal $deal)
    {
        $payment = $deal->payment;
        $couponNames = [];
        $hasCoupon = false;
        $hasVoucher = false;
        $couponTotal = 0;
        $payNotReal = 0;
        foreach ($payment->paymentDetails as $paymentDetail) {
            if($paymentDetail->coupon()->exists()) {
                $hasCoupon = true;
                $couponNames[] = $paymentDetail->coupon->name;
                $couponTotal += $paymentDetail->total_price;
                continue;
            }

            $paymentMethodType = PaymentMethodType::tryFrom($paymentDetail->paymentMethod->type);
            if (!$paymentMethodType) {
                throw new ErrorException('Invalid payment method type: ' . $paymentDetail->paymentMethod->type);
            }
            if(in_array($paymentMethodType, [
                PaymentMethodType::DISCOUNT,
                PaymentMethodType::VOUCHER,
            ])) {
                $payNotReal += $paymentDetail->total_price;
            } else if(in_array($paymentMethodType, [
                PaymentMethodType::ADJUSTMENT,
            ])) {
                $payNotReal -= $paymentDetail->total_price;
            }
            if ($paymentMethodType == PaymentMethodType::VOUCHER) {
                $hasVoucher = true;
            }
        }

        return [
            'couponNames' => $couponNames,
            'couponTotal' => $couponTotal,
            'hasCoupon' => $hasCoupon,
            'hasVoucher' => $hasVoucher,
            'payNotReal' => $payNotReal,
        ];
    }
}
