<?php

namespace Database\Factories;

use App\Models\Deal;
use App\Models\Payment;
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


    public function fromDealPayment(Payment $payment): static
    {
        $deal = $payment->deal;
        $dealGood = $deal->dealGood->first();
        $good = $dealGood->good;

        return $this->state(fn (array $attributes) => [
            'payment_id' => $payment->id,
            'good_category_id' => $good->goodCategory->id,
            'deal_good_id' => $dealGood?->id,
            'name'  => $good->name,
            'price' => $good->price,
            'tax'   => $good->tax,
            'tax_type' => $good->tax_type,
            'num' => $dealGood->num,
            'total_price' => $dealGood->total_price,
            'total_tax' => $dealGood->total_price,
        ]);
    }
}
