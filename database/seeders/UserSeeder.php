<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->create([
            'id' => 1,
            'name' => 'testuser',
            'email' => 'testuser@home.com',
        ]);
        User::factory(5)->create();
    }
}
