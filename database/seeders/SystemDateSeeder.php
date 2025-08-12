<?php

namespace Database\Seeders;

use App\Models\SystemDate;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        SystemDate::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'system_date' => $today,
            ]
        );
    }
}
