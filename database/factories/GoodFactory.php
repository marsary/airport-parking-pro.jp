<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Good>
 */
class GoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomNumber(4);
        return [
            'good_category_id' => \App\Models\GoodCategory::inRandomOrder()->first(),
            'name' => '商品',
            'price' => $price,
            'tax_type' => fake()->randomElement([1,2]) , //1：8％、2：10％、3：対象外
            'start_date' => '2024-01-01',
            'end_date' => '2026-01-01',
        ];
    }
}
