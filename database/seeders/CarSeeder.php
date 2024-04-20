<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Car::insert([
            [
                'car_maker_id' => 1,
                'name' => 'BMW普通1',
                'kana' => 'BMW普通1',
                'size_type' => 1, //1:普通車, 2:大型車
            ],
            [
                'car_maker_id' => 28,
                'name' => 'トヨタ普通1',
                'kana' => 'トヨタ普通1',
                'size_type' => 1, //1:普通車, 2:大型車
            ],
            [
                'car_maker_id' => 28,
                'name' => 'トヨタ大型1',
                'kana' => 'トヨタ大型1',
                'size_type' => 2, //1:普通車, 2:大型車
            ],
            [
                'car_maker_id' => 21,
                'name' => 'スバル大型1',
                'kana' => 'スバル大型1',
                'size_type' => 2, //1:普通車, 2:大型車
            ],

        ]);
    }
}
