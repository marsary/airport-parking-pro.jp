<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgencyRecord>
 */
class AgencyRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agency = Agency::whereIn('id', [1, 6])->inRandomOrder()->first();
        $office = Office::inRandomOrder()->first();

        $deal = Deal::has('payment')->where('agency_id', $agency->id)->inRandomOrder()->first();

        return [
            'office_id'         => $office->id,
            'agency_id'         => $agency->id,
            'agency_name'       => $agency->name,
            'receipt_code'      => $deal->receipt_code ?? 'receipt_code ' . $deal->id,
            'member_code'       => $deal->member?->member_code,
            'deal_id'           => $deal->id,
            'reserve_name'      => $deal->name ?? $this->faker->name(),
            'reserve_name_kana' => $deal->kana ?? $this->faker->kanaName(),
            'load_date'         => $deal->load_date ?? $this->faker->date(),
            'unload_date'       => $deal->unload_date ?? $this->faker->date(),
            'unload_date_plan'  => $deal->unload_date_plan ?? $this->faker->date(),
            'unload_time_plan'  => $deal->unload_time_plan ?? $this->faker->time(),
            'num_days'          => $deal->num_days + $this->faker->numberBetween(1, 14),
            'num_days_plan'     => $deal->num_days ?? 1,
            'airline_name'      => $deal->arrivalFlight?->airline?->name ?? $this->faker->company() . ' Airlines',
            'dep_airport_name'  => $deal->arrivalFlight?->depAirport?->name ?? $this->faker->city() . ' Airport',
            'flight_name'       => $deal->arrivalFlight?->name ?? $this->faker->bothify('FL###'),
            'arrive_date'       => $deal->arrivalFlight?->arrive_date ?? $this->faker->date(),
            'arrive_time'       => $deal->arrivalFlight?->arrive_time ?? $this->faker->time(),
            'car_name'          => $deal->memberCar?->car?->name ?? $this->faker->word(),
            'car_maker_name'    => $deal->memberCar?->car_maker_name ?? $this->faker->company(),
            'car_color_name'    => $deal->memberCar?->carColor?->name ?? $this->faker->safeColorName(),
            'car_number'        => $deal->memberCar?->car?->number ?? $this->faker->numberBetween(1111, 9999),
            'dt_price_load'     => $this->faker->numberBetween(1000, 2000),
            'price'             => $deal->total_price,
            'base_price'        => $this->faker->numberBetween(400, 800),
            'pay_not_real'      => $this->faker->numberBetween(1000, 2000),
            'has_voucher'       => $this->faker->boolean(),
            'coupon_name'       => Coupon::inRandomOrder()->take(3)->pluck('name')->join(', '),
        ];
    }
}
