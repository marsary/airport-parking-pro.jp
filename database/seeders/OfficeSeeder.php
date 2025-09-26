<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Office::insert([
            [
                'id' => 1,
                'name' => 'テスト事業所',
                'short_name' => 'テスト',
                'airport_id' => 1, //成田
                'receipt_issuer' => '領収書発行者名1',
                'zip' => '000-0000',
                'receipt_address' => '領収書住所1',
                'receipt_tel' => '000-0000-0000',
                'email' => 'hello@example.com',
                'max_car_num' => 100,
            ],
        ]);
    }
}
