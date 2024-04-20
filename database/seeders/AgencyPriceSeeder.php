<?php

namespace Database\Seeders;

use App\Models\AgencyPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgencyPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgencyPrice::insert([
            [
                'id' => 1,
                'office_id' => 1,
                'agency_id' => 1,
                'start_date' => '2024-01-01',
                'end_date' => '2028-01-01',
                'base_price' => 0,
                'd1' => 1200,
                'd2' => 2400,
                'd3' => 3600,
                'd4' => 4200,
                'd5' => 4800,
                'd6' => 5400,
                'd7' => 6000,
                'd8' => 6600,
                'd9' => 7200,
                'd10' => 7800,
                'd11' => 8400,
                'd12' => 9000,
                'd13' => 9600,
                'd14' => 10200,
                'd15' => 10800,
                'price_per_day' => 600,
            ],
        ]);
    }
}
