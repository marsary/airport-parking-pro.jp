<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AirportTerminal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\AirportTerminal>
 */
final class AirportTerminalFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = AirportTerminal::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'airport_id' => fake()->randomNumber(),
            'airline_id' => fake()->randomNumber(),
            'terminal_id' => fake()->randomNumber(),
            'default_flg' => fake()->randomNumber(1),
        ];
    }
}
