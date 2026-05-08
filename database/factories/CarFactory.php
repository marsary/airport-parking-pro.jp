<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CarSize;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Car>
 */
final class CarFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Car::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'car_maker_id' => \App\Models\CarMaker::factory(),
            'name' => fake()->name,
            'kana' => fake()->word,
            'sort' => fake()->randomNumber(),
            'size_type' => fake()->randomElement([CarSize::MEDIUM->value, CarSize::LARGE->value]),
            'memo' => fake()->optional()->text,
        ];
    }
}
