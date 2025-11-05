<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\SavingsAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavingsAccount>
 */
class SavingsAccountFactory extends Factory
{
    protected $model = SavingsAccount::class;

    public function definition(): array
    {
        return [
            'member_user_id' => Member::factory(),
            'principal_saving' => fake()->numberBetween(0, 5000000),
            'mandatory_saving' => fake()->numberBetween(0, 5000000),
            'voluntary_saving' => fake()->numberBetween(0, 5000000),
        ];
    }
}
