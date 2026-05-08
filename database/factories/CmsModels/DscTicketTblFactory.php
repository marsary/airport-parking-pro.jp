<?php

declare(strict_types=1);

namespace Database\Factories\CmsModels;

use App\CmsModels\DscTicketTbl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\CmsModels\DscTicketTbl>
 */
final class DscTicketTblFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = DscTicketTbl::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'price' => fake()->numberBetween(100, 10000),
            'days' => fake()->numberBetween(1, 10),
            'sort' => fake()->numberBetween(1, 10),
            'del_flg' => fake()->boolean(),
            'inserted' => fake()->datetime(),
            'modified' => fake()->datetime(),
            'name_s' => fake()->text,
            'dt_for_mem' => fake()->numberBetween(1, 10),
        ];
    }
}
