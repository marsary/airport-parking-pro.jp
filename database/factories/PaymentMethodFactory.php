<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Office;
use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => Office::first()->id,
            'name' => $this->faker->unique()->word() . '決済方法',
            'type' => $this->faker->randomElement(PaymentMethodType::cases())->value,
            'memo' => $this->faker->sentence(),
            'multiple' => false,
        ];
    }
}
