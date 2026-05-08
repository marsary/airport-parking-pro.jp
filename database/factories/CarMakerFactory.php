<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CarMaker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CarMaker>
 */
final class CarMakerFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = CarMaker::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'kana' => fake()->word,
            'sort' => fake()->randomNumber(),
        ];
    }
}
