<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => \App\Models\Office::first(),
            'created_by' => \App\Models\User::first(),
            'updated_by' => \App\Models\User::first(),
            'used_num' => $this->faker->randomNumber(1),
            'tel' => fake()->phoneNumber(),
            'member_code' => fake()->randomNumber(9,true),
            'name' => fake()->name(),
            'kana' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
