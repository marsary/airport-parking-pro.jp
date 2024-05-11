<?php

namespace Database\Seeders;

use App\Models\GoodCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        GoodCategory::insert([
            // 1：洗車、
            [
                'id' => 1,
                'name' => '洗車',
                'type' => 1, // 1：出庫までに作業が必要、2：出庫までに作業が不要
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],
            // 2：メンテナンス、
            [
                'id' => 2,
                'name' => 'メンテナンス',
                'type' => 1,
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],
            // 3：保険、
            [
                'id' => 3,
                'name' => '保険',
                'type' => 2,
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],
            // 4：回数券、
            [
                'id' => 4,
                'name' => '回数券',
                'type' => 2,
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],
            // 5：物販、
            [
                'id' => 5,
                'name' => '物販',
                'type' => 2,
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],
            // 6：店頭での購入、
            [
                'id' => 6,
                'name' => '店頭での購入',
                'type' => 2,
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],
            // 7：その他
            [
                'id' => 7,
                'name' => 'その他',
                'type' => 2,
                'office_id' => 1,
                'regi_display_flag' => 1,
            ],

        ]);
    }
}
