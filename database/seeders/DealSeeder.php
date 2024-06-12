<?php

namespace Database\Seeders;

use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Models\Deal;
use App\Models\DealGood;
use App\Models\Payment;
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
            ],
            [
                'status' => DealStatus::LOADED->value,
                'load_date' => Carbon::today(),
                'unload_date_plan' => (Carbon::today())->addDay(),
            ],
            [
                'status' => DealStatus::NOT_LOADED->value,
                'unload_date_plan' => Carbon::today(),
            ],
            [
                'status' => DealStatus::CANCEL->value,
                'unload_date_plan' => Carbon::today(),
            ],
            [
                'status' => DealStatus::LOADED->value,
                'unload_date_plan' => Carbon::today(),
            ],
            [
                'status' => DealStatus::LOADED->value,
                'unload_date_plan' => (Carbon::today())->addDay(),
            ],
            [
                'status' => DealStatus::UNLOADED->value,
                'unload_date_plan' => Carbon::today(),
            ],
            [
                'status' => DealStatus::PENDING->value,
                'unload_date_plan' => Carbon::today(),
            ],
        ];

        foreach ($data as $row) {
            $deal = Deal::factory(1)->create($row)->first();
            $dealGood = DealGood::factory(1)->create([
                'deal_id' => $deal->id
            ])->first();
            $good = $dealGood->good;
            $payment = Payment::factory(1)->create([
                'deal_id' => $deal->id,
                'member_id' => $deal->member->id,
                'payment_date' => Carbon::now(),
                'demand_price' => 10000,
            ])->first();
            PaymentGood::factory(1)->create([
                'payment_id' => $payment->id,
                'good_category_id' => $good->goodCategory->id,
                'deal_good_id' => $dealGood->id,
                'name' => $good->name,
                'price' => $good->price,
                'tax' => $good->tax,
                'tax_type' => $good->tax_type,
                'num' => 2,
                'total_price' => $good->price * 2,
                'total_tax' => $good->tax * 2,
            ]);
        }
    }
}
