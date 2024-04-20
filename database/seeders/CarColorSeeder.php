<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeedFromCSV::seed('car_colors',
            'car_colors.csv',
            [
                'id',
                'name',
                'created_at',
                'updated_at'
            ]
        );
    }
}
