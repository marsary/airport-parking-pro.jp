<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodARSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            // 12：売掛
            [
                'office_id' => 1,
                'name' => '売掛',
                'type' => 12,
                'memo' => null,
                'multiple' => false,
            ],
        ]);
    }
}
