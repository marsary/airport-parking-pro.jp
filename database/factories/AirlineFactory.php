<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Airline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Airline>
 */
final class AirlineFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Airline::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'code' => fake()->optional()->word,
            'name' => fake()->name,
            'kana' => fake()->word,
            'search_keyword' => fake()->optional()->word,
            'sort' => fake()->optional()->randomNumber(),
            'memo' => fake()->optional()->text,
        ];
    }
}
