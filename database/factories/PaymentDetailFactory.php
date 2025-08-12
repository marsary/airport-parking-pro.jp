<?php

namespace Database\Factories;

use App\Models\PaymentDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class PaymentDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $payment = \App\Models\Payment::inRandomOrder()->first();
        $paymentMethod = \App\Models\PaymentMethod::inRandomOrder()->first();
        $coupon = \App\Models\PaymentMethod::inRandomOrder()->first();
        return [
            'payment_id' => $payment->id,
            'payment_method_id' => $paymentMethod->id,
            'total_price' => $payment->demand_price / 2,
            'coupon_id' => $coupon->id,
            'discount_type' => $coupon->discount_type, // Assuming 1 and 2 are the possible values based on DiscountType enum
        ];
    }

    /**
     * Indicate that no coupon is applied.
     */
    public function noCoupon(): static
    {
        return $this->state(fn (array $attributes) => [
            'coupon_id' => null,
            'discount_type' => null,
        ]);
    }
}
