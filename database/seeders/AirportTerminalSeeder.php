<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportTerminalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeedFromCSV::seed('airport_terminals',
            'airport_terminals.csv',
            [
                'id',
                'airport_id',
                'airline_id',
                'terminal_id',
                'default_flg',
                'created_at',
                'updated_at'
            ]
        );
    }
}
