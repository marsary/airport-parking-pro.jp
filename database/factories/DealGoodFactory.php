<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DealGood>
 */
class DealGoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->randomNumber(5);
        return [
            'deal_id' => \App\Models\Deal::inRandomOrder()->first(),
            'good_id' => \App\Models\Good::inRandomOrder()->first(),
            'num' => $this->faker->randomNumber(2),
            'total_price' => $price,
            'total_tax' => (int)$price * 0.1,
        ];
    }
}
