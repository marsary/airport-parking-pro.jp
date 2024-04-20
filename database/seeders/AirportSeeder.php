<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Airport::insert([
            [
                'id' => 1,
                'name' => '成田'
            ],
            [
                'id' => 2,
                'name' => '羽田'
            ],
        ]);
    }
}
