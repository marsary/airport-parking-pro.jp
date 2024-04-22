<?php

namespace Database\Seeders;

use App\Models\AirportTerminal;
use App\Models\ArrivalFlight;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArrivalFlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terminal = AirportTerminal::where('airport_id', 1)->inRandomOrder()->first();
        ArrivalFlight::insert([
            [
                'flight_no' => 'NH300',
                'name' => 'NH 300便',
                'dep_airport_id' => 2,
                'arr_airport_id' => $terminal->airport_id,
                'airline_id' => $terminal->airline_id,
                'terminal_id' => $terminal->terminal_id,
                'arrive_date' => '2024-05-02',
                'arrive_time' => '11:00:00',
            ],
            [
                'flight_no' => 'JL200',
                'name' => 'JAL 200便',
                'dep_airport_id' => 2,
                'arr_airport_id' => $terminal->airport_id,
                'airline_id' => $terminal->airline_id,
                'terminal_id' => $terminal->terminal_id,
                'arrive_date' => '2024-05-01',
                'arrive_time' => '10:30:00',
            ],
        ]);
    }
}
