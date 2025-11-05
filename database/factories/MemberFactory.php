<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'member_id_number' => strtoupper(Str::random(3)) . fake()->unique()->numerify('####'),
            'point' => fake()->numberBetween(0, 1000),
            'status' => fake()->randomElement(['Pending', 'Active', 'Inactive']),
        ];
    }
}
