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

class DealForDailyInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 1日前3件：入庫済み、うち１件本日出庫済み
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(1),
                'unload_date' => null
            ],
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(1),
                'unload_date' => null,
            ],
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(1),
                'unload_date' => Carbon::today(),
            ],
            // 1日前2件：出庫済み
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(3),
                'unload_date' => Carbon::today()->subDays(1)
            ],
            [
                'status' => DealStatus::UNLOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->subDays(4),
                'unload_date' => Carbon::today()->subDays(1)
            ],
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
            // 1日後入庫、2日後出庫予定
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->addDays(1),
                'unload_date' => Carbon::today()->addDays(2),
            ],
            // 1日後入庫、3日後出庫予定
            [
                'status' => DealStatus::LOADED->value,
                'transaction_type' => TransactionType::PARKING->value,
                'load_date' => Carbon::today()->addDays(1),
                'unload_date' => Carbon::today()->addDays(3),
            ],
        ];

        foreach ($data as $row) {
            $deal = Deal::factory(1)->create($row)->first();
        }
    }
}
