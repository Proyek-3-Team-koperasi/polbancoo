<?php

namespace Database\Factories;

use App\Models\FinancingApplication;
use App\Models\InstallmentBill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstallmentBill>
 */
class InstallmentBillFactory extends Factory
{
    protected $model = InstallmentBill::class;

    public function definition(): array
    {
        $applicationFactory = FinancingApplication::factory();
        $paymentDate = fake()->optional()->dateTimeBetween('-6 months', 'now');

        return [
            'financing_application_id' => $applicationFactory,
            'bill_number' => fake()->numberBetween(1, 12),
            'amount' => fake()->numberBetween(100000, 2000000),
            'due_date' => fake()->dateTimeBetween('-6 months', '+6 months')->format('Y-m-d'),
            'payment_date' => $paymentDate?->format('Y-m-d'),
            'status' => fake()->randomElement(['Unpaid', 'Paid', 'Overdue']),
        ];
    }
}
