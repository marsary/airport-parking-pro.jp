<?php

namespace Database\Seeders;

use App\Models\Good;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxTypes = ['8%','10%'];
        $typeCounts = [1, 1];
        for ($i=0; $i < 10; $i++) {
            $idx = $i % 2;
            $taxType = $taxTypes[$idx];
            Good::factory(1)->create([
                'name' => $taxType . '商品' . $typeCounts[$idx],
            ]);
            $typeCounts[$idx]++;
        }
    }
}
