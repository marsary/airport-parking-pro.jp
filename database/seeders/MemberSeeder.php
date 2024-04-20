<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::factory(1)->create([
            'id' => 1,
            'name' => 'testmember',
            'email' => 'testmember@home.com',
        ]);
        Member::factory(5)->create();
    }
}
