<?php

namespace Database\Seeders;

use App\Models\CarCautionMemberCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarCautionMemberCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarCautionMemberCar::factory(3)->create();
    }
}
