<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentGood>
 */
class PaymentGoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payment = \App\Models\Payment::inRandomOrder()->first();
        $dealGood = $payment->deal->dealGoods?->first();
        $price = $this->faker->randomNumber(5);
        $good = $dealGood?->good;
        $num = $this->faker->randomNumber(2);
        return [
            'payment_id' => $payment->id,
            'good_category_id' => $good->goodCategory->id,
            'deal_good_id' => $dealGood?->id,
            'name'  => $good->name,
            'price' => $price,
            'tax'   => roundTax($price * \App\Enums\TaxType::TEN_PERCENT->rate()),
            'tax_type' => \App\Enums\TaxType::TEN_PERCENT->value,
            'num' => $num,
            'total_price' => $price,
            'total_tax' => (int)$price * 0.1,
        ];
    }
}
