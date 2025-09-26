<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeedFromCSV::seed('airlines',
            'airlines_test.csv',
            [
                'id',
                'code',
                'name',
                'kana',
                'memo',
                'created_at',
                'updated_at'
            ]
        );
    }
}
