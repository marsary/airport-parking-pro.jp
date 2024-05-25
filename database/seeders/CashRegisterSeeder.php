<?php

namespace Database\Seeders;

use App\Enums\GeneralStatus;
use App\Models\CashRegister;
use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashRegister::insert([
            [
                'status' => GeneralStatus::Enabled->value,
                'office_id' => Office::first()->id,
                'memo' => 'test register',
            ],
        ]);
    }
}
