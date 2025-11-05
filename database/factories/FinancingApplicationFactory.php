<?php

namespace Database\Factories;

use App\Models\FinancingApplication;
use App\Models\Member;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinancingApplication>
 */
class FinancingApplicationFactory extends Factory
{
    protected $model = FinancingApplication::class;

    public function definition(): array
    {
        $memberFactory = Member::factory();
        $tenor = fake()->randomElement([3, 6, 9, 12]);
        $costPrice = fake()->numberBetween(500000, 10000000);
        $margin = fake()->numberBetween(50000, 1000000);
        $sellingPrice = $costPrice + $margin;
        $monthlyInstallment = intdiv($sellingPrice + $tenor - 1, $tenor);

        return [
            'order_id' => Order::factory()
                ->for($memberFactory, 'member')
                ->state([
                    'payment_method' => 'Murabahah',
                    'status' => 'Pending Payment',
                    'total_amount' => $sellingPrice,
                ]),
            'member_user_id' => $memberFactory,
            'admin_user_id' => User::factory(),
            'tenor' => $tenor,
            'cost_price_total' => $costPrice,
            'margin' => $margin,
            'selling_price_total' => $sellingPrice,
            'monthly_installment' => $monthlyInstallment,
            'status' => fake()->randomElement(['Pending', 'Approved', 'Rejected', 'Active', 'Fully Paid']),
            'application_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'approval_date' => fake()->optional()->dateTimeBetween('-11 months', 'now'),
            'rejection_reason' => fake()->optional(0.2)->sentence(),
            'agreement_signed_at' => fake()->optional()->dateTimeBetween('-11 months', 'now'),
        ];
    }
}
