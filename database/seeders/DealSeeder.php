<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\DealGood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = Deal::factory(3)->create();
        foreach ($deals as $deal) {
            DealGood::factory(1)->create([
                'deal_id' => $deal->id
            ]);
        }
    }
}
