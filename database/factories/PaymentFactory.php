<?php

namespace Database\Factories;

use App\Models\CashRegister;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deal = \App\Models\Deal::inRandomOrder()->first();
        $loadDate = $this->faker->date();
        $numOfDays = fake()->randomNumber(2);
        $unloadDate = Carbon::parse($loadDate)->addDays($numOfDays);
        $price = fake()->randomNumber(5);
        $user = \App\Models\User::first();
        return [
            'payment_code' => Str::random(10),
            'payment_date' => Carbon::now(),
            'cash_register_id' => CashRegister::first()->id,
            'office_id' => \App\Models\Office::first(),
            'deal_id' => $deal->id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'member_id' => $deal->member?->id,
            'load_date' => $loadDate,
            'unload_date' => $unloadDate,
            'unload_date_plan' => $unloadDate,
            'days' => $numOfDays,
            'price' => $price,
            'total_price' => $price,
            'demand_price' => (int) $price * 1.1,
            'total_tax' => (int) $price * 0.1,
            'created_by' => \App\Models\User::first(),
            'updated_by' => \App\Models\User::first(),
        ];
    }
}
