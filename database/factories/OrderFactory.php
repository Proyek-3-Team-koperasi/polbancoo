<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'member_user_id' => Member::factory(),
            'order_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => fake()->randomElement(['Pending Payment', 'Processing', 'Shipped', 'Completed', 'Cancelled']),
            'total_amount' => fake()->numberBetween(100000, 5000000),
            'payment_method' => fake()->randomElement(['Cash', 'Murabahah']),
        ];
    }
}
