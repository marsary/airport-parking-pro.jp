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
        $good = \App\Models\Good::inRandomOrder()->first();

        return [
            'deal_id' => \App\Models\Deal::inRandomOrder()->first(),
            'good_id' => $good->id,
            'num' => $this->faker->randomNumber(2),
            'total_price' => $good->price,
            'total_tax' => $good->tax,
        ];
    }
}
