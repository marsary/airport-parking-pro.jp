<?php

namespace Database\Seeders;

use App\Models\MonthlySalesTarget;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonthlySalesTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MonthlySalesTarget::insert([
            // 月間総売り上げ
            [
                'office_id' => Office::first()->id,
                'target_month' => (Carbon::today())->format('Ym'),
                'order' => 1,
                'good_category_id' => null,
                'sales_target' => 1050000,
            ],
            // 駐車料金
            [
                'office_id' => Office::first()->id,
                'target_month' => (Carbon::today())->format('Ym'),
                'order' => 2,
                'good_category_id' => null,
                'sales_target' => 600000,
            ],
            // 洗車
            [
                'office_id' => Office::first()->id,
                'target_month' => (Carbon::today())->format('Ym'),
                'order' => 3,
                'good_category_id' => 1,
                'sales_target' => 200000,
            ],
            // 保険
            [
                'office_id' => Office::first()->id,
                'target_month' => (Carbon::today())->format('Ym'),
                'order' => 4,
                'good_category_id' => 3,
                'sales_target' => 100000,
            ],
            // 物販
            [
                'office_id' => Office::first()->id,
                'target_month' => (Carbon::today())->format('Ym'),
                'order' => 5,
                'good_category_id' => 5,
                'sales_target' => 150000,
            ],
        ]);
    }
}
