<?php

namespace Database\Seeders;

use App\Models\ParkingLimit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParkingLimit::insert([
            [
                'id' => 1,
                'office_id' => 1,
                'target_date' => '2024-04-16',
                'load_limit' => '30',
                'unload_limit' => '30',
                'per_fifteen_munites' => '4',
            ],
        ]);
    }
}
