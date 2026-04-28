<?php

declare(strict_types=1);

namespace Database\Factories\CmsModels;

use App\CmsModels\GoodsTbl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\CmsModels\GoodsTbl>
 */
final class GoodsTblFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = GoodsTbl::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'o_id' => fake()->numberBetween(1, 5),
            'g_id_old' => fake()->numberBetween(1, 5),
            'name' => fake()->name,
            'name2' => fake()->text,
            'o_belong' => fake()->numberBetween(1, 5),
            'price' => fake()->numberBetween(100, 10000),
            'tax_type' => fake()->numberBetween(1, 2),
            'wax_flg' => fake()->boolean(),
            'one_day_flg' => fake()->boolean(),
            'insr_flg' => fake()->boolean(),
            'sales_type' => fake()->numberBetween(1, 5),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'ml_add' => fake()->numberBetween(1, 10),
            'ml_per_yen' => fake()->numberBetween(1, 10),
            'pt_add' => fake()->numberBetween(1, 150),
            'pt_per_yen' => fake()->numberBetween(1, 10),
            'pt_flg' => fake()->boolean(),
            'lsize_rate' => fake()->numberBetween(1, 10),
            'for_rsv_server' => fake()->numberBetween(1, 10),
            'sort' => fake()->numberBetween(1, 100),
            'del_flg' => fake()->boolean(),
            'inserted' => fake()->datetime(),
            'modified' => fake()->datetime(),
        ];
    }
}
