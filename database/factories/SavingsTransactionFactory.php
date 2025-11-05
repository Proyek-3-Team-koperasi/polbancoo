<?php

namespace Database\Factories;

use App\Models\SavingsAccount;
use App\Models\SavingsTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavingsTransaction>
 */
class SavingsTransactionFactory extends Factory
{
    protected $model = SavingsTransaction::class;

    public function definition(): array
    {
        return [
            'savings_account_id' => SavingsAccount::factory(),
            'admin_user_id' => User::factory(),
            'amount' => fake()->numberBetween(10000, 5000000),
            'type' => fake()->randomElement(['Principal', 'Mandatory', 'Voluntary', 'Withdrawal']),
            'description' => fake()->sentence(),
            'transaction_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
