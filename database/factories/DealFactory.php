<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $loadDate = $this->faker->date();
        $numOfDays = fake()->randomNumber(2);
        $unloadDate = Carbon::parse($loadDate)->addDays($numOfDays);
        $member = \App\Models\Member::has('memberCar')->inRandomOrder()->first();
        $price = fake()->randomNumber(5);
        return [
            'member_id' => $member,
            'office_id' => \App\Models\Office::first(),
            'reserve_code' => Str::random(10),
            'receipt_code' => Str::random(10),
            'reserve_date' => $this->faker->dateTime(),
            'load_date' => $loadDate,
            'load_time' => $this->faker->time(),
            'unload_date_plan' => $unloadDate,
            'unload_time_plan' => $this->faker->time(),
            'num_days' => $numOfDays,
            'price' => $price,
            'tax' => (int) $price * 0.1,
            'total_price' => $price,
            'total_tax' => (int) $price * 0.1,
            'name' => $member->name,
            'kana' => $member->kana,
            'member_car_id' => \App\Models\MemberCar::where('member_id', $member->id)->first(),
            'created_by' => \App\Models\User::first(),
            'updated_by' => \App\Models\User::first(),
        ];
    }
}
