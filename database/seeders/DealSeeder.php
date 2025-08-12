<?php

namespace Database\Seeders;

use App\Enums\DealStatus;
use App\Enums\PaymentMethodType;
use App\Enums\TaxType;
use App\Models\Deal;
use App\Models\DealGood;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PaymentGood;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'status' => DealStatus::NOT_LOADED->value,
                'load_date' => Carbon::today(),
                'unload_date_plan' => (Carbon::today())->addDay(),
                'unload_date' =>  null,
            ],
            [
                'status' => DealStatus::LOADED->value,
                'load_date' => Carbon::today(),
                'unload_date_plan' => (Carbon::today())->addDay(),
                'unload_date' =>  null,
            ],
            [
                'status' => DealStatus::NOT_LOADED->value,
                'load_date' =>  (Carbon::today())->subDays(5),
                'unload_date_plan' => Carbon::today(),
                'unload_date' =>  null,
                'agency_id' => 1, // プレミア
            ],
            [
                'status' => DealStatus::CANCEL->value,
                'load_date' =>  (Carbon::today())->subDays(5),
                'unload_date_plan' => Carbon::today(),
                'unload_date' =>  null,
            ],
            [
                'status' => DealStatus::LOADED->value,
                'load_date' =>  (Carbon::today())->subDays(10),
                'unload_date_plan' => Carbon::today(),
                'unload_date' =>  null,
            ],
            [
                'status' => DealStatus::LOADED->value,
                'load_date' =>  (Carbon::today())->subDays(10),
                'unload_date_plan' => (Carbon::today())->addDay(),
                'unload_date' =>  null,
            ],
            [
                'status' => DealStatus::UNLOADED->value,
                'load_date' =>  (Carbon::today())->subDays(10),
                'unload_date_plan' => Carbon::today(),
                'unload_date' =>  Carbon::today(),
            ],
            [
                'status' => DealStatus::PENDING->value,
                'load_date' =>  (Carbon::today())->subDays(3),
                'unload_date_plan' => Carbon::today(),
                'unload_date' =>  null,
            ],
        ];

        foreach ($data as $idx => $row) {
            $deal = null;
            if($idx == 2) {
                // プレミア
                $deal = Deal::factory(1)->premium()->create($row)->first();
            } else {
                $deal = Deal::factory(1)->create($row)->first();
            }

            $dealGood = DealGood::factory(1)->create([
                'deal_id' => $deal->id
            ])->first();
            $good = $dealGood->good;
            $payment = Payment::factory(1)->create([
                'deal_id' => $deal->id,
                'member_id' => $deal->member->id,
                'payment_date' => Carbon::now(),
                'load_date' => $deal->load_date,
                'unload_date_plan' => $deal->unload_date_plan,
                'unload_date' => $deal->unload_date,
                'days' => $deal->num_days,
                'price' => $deal->price,
                'goods_total_price' => $dealGood->total_price + $dealGood->total_tax,
                'total_price' => $deal->total_price,
                'total_tax' => $deal->total_tax,
                'demand_price' => $deal->total_price + $deal->total_tax,
                'cash_change' => 100,
                'total_pay' => $deal->total_price + $deal->total_tax + 100,
            ])->first();
            PaymentGood::factory(1)->create([
                'payment_id' => $payment->id,
                'good_category_id' => $good->goodCategory->id,
                'deal_good_id' => $dealGood->id,
                'name' => $good->name,
                'price' => $good->price,
                'tax' => $good->tax,
                'tax_type' => $good->tax_type,
                'num' => $dealGood->num,
                'total_price' => $dealGood->total_price,
                'total_tax' => $dealGood->total_tax,
            ]);

            if($idx % 2 == 0) {
                PaymentDetail::factory(1)->noCoupon()->create([
                    'payment_id' => $payment->id,
                    'payment_method_id' => PaymentMethodType::CASH->value,
                    'total_price' => $payment->total_pay / 2,
                ]);
                PaymentDetail::factory(1)->create([
                    'payment_id' => $payment->id,
                    'payment_method_id' => PaymentMethodType::COUPON->value,
                    'total_price' => $payment->total_pay / 2,
                ]);
            } elseif($idx % 2 == 1) {
                PaymentDetail::factory(1)->noCoupon()->create([
                    'payment_id' => $payment->id,
                    'payment_method_id' => PaymentMethodType::CREDIT->value,
                    'total_price' => $payment->total_pay,
                ]);

            }
        }
    }
}
