<?php

namespace Database\Factories;

use App\Models\GoodCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GoodCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => \App\Models\Office::first(),
            'name' => $this->faker->word(),
            'type' => $this->faker->numberBetween(1, 2),
            'regi_display_flag' => $this->faker->boolean(),
            'memo' => $this->faker->sentence(),
        ];
    }
}
