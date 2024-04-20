<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TagMember>
 */
class TagMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => \App\Models\Office::inRandomOrder()->first(),
            'member_id' => \App\Models\Member::inRandomOrder()->first(),
            'label_id' => \App\Models\Label::inRandomOrder()->first(),
            'tag_id' => \App\Models\Tag::inRandomOrder()->first(),
        ];
    }
}
