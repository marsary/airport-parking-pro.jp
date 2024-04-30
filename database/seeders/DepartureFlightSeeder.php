<?php

namespace Database\Seeders;

use App\Models\AirportTerminal;
use App\Models\DepartureFlight;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartureFlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terminal = AirportTerminal::where('airport_id', 1)->inRandomOrder()->first();
        DepartureFlight::insert([
            [
                'flight_no' => 'NRT300',
                'name' => 'NRT300 出発便',
                'dep_airport_id' => $terminal->airport_id,
                'arr_airport_id' => 2,
                'airline_id' => $terminal->airline_id,
                'terminal_id' => $terminal->terminal_id,
                'depart_date' => '2024-05-02',
                'depart_time' => '11:00:00',
            ],
            [
                'flight_no' => 'NRT200',
                'name' => 'NRT200 出発便',
                'dep_airport_id' => $terminal->airport_id,
                'arr_airport_id' => 2,
                'airline_id' => $terminal->airline_id,
                'terminal_id' => $terminal->terminal_id,
                'depart_date' => '2024-05-01',
                'depart_time' => '10:30:00',
            ],
        ]);
    }
}
