<?php

namespace Database\Seeders;

use App\Models\InitialStockQuantity;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialStockQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::today()->subYear();
        InitialStockQuantity::create([
            'start_date' => $startDate,
            'starting_quantity' => 100,
        ]);
    }
}
