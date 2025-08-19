<?php

namespace Database\Seeders;

use App\Models\AgencyRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgencyRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgencyRecord::factory(6)->create();
    }
}
