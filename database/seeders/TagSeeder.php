<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::insert([
            [
                'id' => 1,
                'office_id' => 1,
                'label_id' => 1,
                'name' => 'ゴールド',
                'sort' => 3,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 2,
                'office_id' => 1,
                'label_id' => 1,
                'name' => 'シルバー',
                'sort' => 3,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 3,
                'office_id' => 1,
                'label_id' => 2,
                'name' => 'タグ4',
                'sort' => 4,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 4,
                'office_id' => 1,
                'label_id' => 3,
                'name' => 'タグ5',
                'sort' => 5,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ]);
    }
}
