<?php

namespace Database\Seeders;

use App\Enums\DealStatus;
use App\Enums\TaxType;
use App\Enums\TransactionType;
use App\Models\Deal;
use App\Models\DealGood;
use App\Models\Payment;
use App\Models\PaymentGood;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DealForRenewalInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 7日前１件：未入庫
            // 7日前１件：入庫済み
            [
                'status' => DealStatus::NOT_LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(7),
                'unload_date' => null
            ],
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(7),
                'unload_date' => null,
            ],
            // 6日前2件：入庫済み、うち１件本日出庫済み
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(6),
                'unload_date' => null,
            ],
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(6),
                'unload_date' => Carbon::today(),
            ],
            // 5日前は入出庫なし
            // 4日前1件：当日出庫済み
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(4),
                'unload_date' => Carbon::today()->subDays(4)
            ],
            // 3日前1件：出庫済み
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(3),
                'unload_date' => Carbon::today()->subDays(2)
            ],
            // 1,2日前は入出庫なし
            // １日前 商品購入あり
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PURCHASE_ONLY->value,
                'load_date' => null,
                'unload_date' => null,
                'created_at' => Carbon::today()->subDays(1),
                'updated_at' => Carbon::today()->subDays(1),
            ],
            // 本日1件：入庫済み
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today(),
                'unload_date' => null
            ],
        ];

        foreach ($data as $row) {
            $deal = Deal::factory(1)->create($row)->first();
        }
    }
}
