<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberCar>
 */
class MemberCarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => \App\Models\Office::first(),
            'member_id' => \App\Models\Member::inRandomOrder()->first(),
            'car_id' => \App\Models\Car::inRandomOrder()->first(),
            'car_color_id' => \App\Models\CarColor::inRandomOrder()->first(),
            'number' => $this->faker->randomNumber(4, true),

        ];
    }
}
