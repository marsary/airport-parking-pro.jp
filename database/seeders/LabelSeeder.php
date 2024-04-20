<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::insert([
            [
                'id' => 1,
                'office_id' => 1,
                'name' => '会員ランク',
                'sort' => 3,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 2,
                'office_id' => 1,
                'name' => 'ラベル4',
                'sort' => 4,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 3,
                'office_id' => 1,
                'name' => 'ラベル5',
                'sort' => 5,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ]);
    }
}
