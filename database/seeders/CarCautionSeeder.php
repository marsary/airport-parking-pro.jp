<?php

namespace Database\Seeders;

use App\Models\CarCaution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarCautionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarCaution::insert([
            [
                'id' => 1,
                'office_id' => 1,
                'name' => 'MT車',
            ],
            [
                'id' => 2,
                'office_id' => 1,
                'name' => '土足厳禁',
            ],
        ]);
    }
}
