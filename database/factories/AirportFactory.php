<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Airport>
 */
final class AirportFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Airport::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'code' => fake()->word,
            'name' => fake()->name,
            'name_kana' => fake()->optional()->word,
            'name_full' => fake()->optional()->word,
            'location' => fake()->optional()->word,
        ];
    }
}
