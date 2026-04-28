<?php

declare(strict_types=1);

namespace Database\Factories\CmsModels;

use App\CmsModels\CarTbl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\CmsModels\CarTbl>
 */
final class CarTblFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = CarTbl::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'car_maker_id' => fake()->numberBetween(1, 10000),
            'car_id' => fake()->numberBetween(1, 10000),
            'name' => fake()->name,
            'name_k' => fake()->text,
            'type' => fake()->numberBetween(1, 2),
            'lsize_flg' => fake()->boolean(),
            'sort' => fake()->numberBetween(1, 100),
            'del_flg' => fake()->boolean(),
            'inserted' => fake()->datetime(),
            'modified' => fake()->datetime(),
        ];
    }
}
