<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . '事業所',
            'short_name' => $this->faker->unique()->word(),
            'airport_id' => Airport::inRandomOrder()->first(),
            'receipt_issuer' => $this->faker->name(),
            'zip' => $this->faker->postcode(),
            'receipt_address' => $this->faker->address(),
            'receipt_tel' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'max_car_num' => $this->faker->numberBetween(100, 1000),
            'start_time' => "09:00",
            'end_time' => "18:00",
        ];
    }
}
