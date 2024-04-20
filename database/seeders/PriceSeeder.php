<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Price::insert([
            [
                'id' => 1,
                'office_id' => 1,
                'start_date' => '2024-01-01',
                'end_date' => '2028-01-01',
                'base_price' => 0,
                'd1' => 1430,
                'd2' => 2860,
                'd3' => 4290,
                'd4' => 5000,
                'd5' => 5720,
                'd6' => 6430,
                'd7' => 7150,
                'd8' => 7860,
                'd9' => 8580,
                'd10' => 8800,
                'd11' => 9020,
                'd12' => 9240,
                'd13' => 9460,
                'd14' => 9680,
                'd15' => 9900,
                'price_per_day' => 600,
            ],
        ]);
    }
}
