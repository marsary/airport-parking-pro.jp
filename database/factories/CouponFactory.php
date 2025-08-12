<?php

namespace Database\Factories;

use App\Models\GoodCategory;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountType = $this->faker->randomElement([1, 2]); // 1:円, 2:%

        return [
            'office_id' => Office::first() ?? Office::factory(),
            'name' => $this->faker->realText(20) . 'クーポン',
            'code' => Str::upper(Str::random(8)),
            'discount_amount' => $discountType === 1 ? $this->faker->randomElement([500, 1000, 1500, 2000]) : $this->faker->numberBetween(5, 20),
            'discount_type' => $discountType,
            'good_category_id' => GoodCategory::inRandomOrder()->first()->id,
            'limit_flg' => $this->faker->randomElement([0, 1]), // 0: 1回, 1: 複数回
            'combination_flg' => $this->faker->randomElement([0, 1]), // 0: 不可, 1: 可
            'start_date' => Carbon::now()->subDays($this->faker->numberBetween(1, 30)),
            'end_date' => Carbon::now()->addDays($this->faker->numberBetween(30, 90)),
            'memo' => $this->faker->optional()->sentence,
        ];
    }
}
