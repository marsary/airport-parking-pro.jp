<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarCautionMemberCar>
 */
class CarCautionMemberCarFactory extends Factory
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
            'member_car_id' => \App\Models\MemberCar::inRandomOrder()->first(),
            'car_caution_id' => \App\Models\CarCaution::inRandomOrder()->first(),
            'sort' => 0,
        ];
    }
}
