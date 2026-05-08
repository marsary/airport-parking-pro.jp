<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ArrivalFlight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ArrivalFlight>
 */
final class ArrivalFlightFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ArrivalFlight::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'flight_no' => fake()->optional()->word,
            'name' => fake()->optional()->name,
            'dep_airport_id' => \App\Models\Airport::factory(),
            'arr_airport_id' => \App\Models\Airport::factory(),
            'airline_id' => \App\Models\Airline::factory(),
            'terminal_id' => \App\Models\AirportTerminal::factory(),
            'arrive_date' => fake()->optional()->date(),
            'arrive_time' => fake()->optional()->time(),
            'is_delayed' => fake()->randomNumber(1),
        ];
    }
}
