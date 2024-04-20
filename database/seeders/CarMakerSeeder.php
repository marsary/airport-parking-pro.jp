<?php

namespace Database\Seeders;

use App\Models\CarMaker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarMakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeedFromCSV::seed('car_makers',
            'car_makers.csv',
            [
                'id',
                'name',
                'kana',
                'sort',
                'created_at',
                'updated_at',
            ]
        );
    }
}
