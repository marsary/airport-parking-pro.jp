<?php

declare(strict_types=1);

namespace Database\Factories\CmsModels;

use App\CmsModels\CarColorTbl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\CmsModels\CarColorTbl>
 */
final class CarColorTblFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = CarColorTbl::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'car_col_id' => fake()->numberBetween(1, 10000),
            'name' => fake()->name,
            'sort' => fake()->numberBetween(1, 10),
            'del_flg' => fake()->boolean(),
            'inserted' => fake()->datetime(),
            'modified' => fake()->datetime(),
        ];
    }
}
