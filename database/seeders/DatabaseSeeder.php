<?php

namespace Database\Seeders;

use App\Models\FinancingApplication;
use App\Models\InstallmentBill;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\SavingsAccount;
use App\Models\SavingsTransaction;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Ramsey\Collection\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /** @var Generator $faker */
        $faker = App::make(Generator::class);

        $this->call(LedgerSeeder::class);

        $roles = collect(['Anggota', 'Admin', 'Super Admin'])
            ->mapWithKeys(function (string $role) {
                $model = Role::query()->firstOrCreate(['name' => $role]);

                return [$role => $model];
            });

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        $superAdmin->roles()->sync([$roles['Super Admin']->id]);

        $adminUser = User::factory()->create([
            'name' => 'Admin Koperasi',
            'email' => 'admin@example.com',
        ]);
        $adminUser->roles()->sync([$roles['Admin']->id]);

        $memberUser = User::factory()->create([
            'name' => 'Member Demo',
            'email' => 'member@example.com',
        ]);
        $memberUser->roles()->sync([$roles['Anggota']->id]);

        $knownMember = Member::factory()
            ->for($memberUser, 'user')
            ->state([
                'member_id_number' => 'MEM0001',
                'point' => 0,
                'status' => 'Active',
            ])
            ->create();

        $categories = ProductCategory::factory()->count(5)->create();
        $products = Product::factory()
            ->count(20)
            ->state(fn () => ['category_id' => $categories->random()->id])
            ->create();

        $members = collect([$knownMember])
            ->merge(Member::factory()->count(10)->create());

        foreach ($members as $member) {
            $member->user->roles()->syncWithoutDetaching([$roles['Anggota']->id]);

            $account = SavingsAccount::factory()
                ->state(['member_user_id' => $member->getKey()])
                ->create();

            SavingsTransaction::factory()
                ->count(3)
                ->state([
                    'savings_account_id' => $account->id,
                    'admin_user_id' => $adminUser->id,
                    'transaction_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                ])
                ->create();

            $orders = Order::factory()
                ->count(2)
                ->state(function () use ($member, $faker) {
                    return [
                        'member_user_id' => $member->getKey(),
                        'order_date' => $faker->dateTimeBetween('-6 months', 'now'),
                        'status' => $faker->randomElement(['Pending Payment', 'Processing', 'Completed']),
                        'payment_method' => $faker->randomElement(['Cash', 'Murabahah']),
                        'total_amount' => 0,
                    ];
                })
                ->create();

            foreach ($orders as $order) {
                $itemCount = $faker->numberBetween(1, 3);
                $orderItems = collect();
                $productToBuys = collect();
                for($i = 0; $i < $itemCount; $i++) {
                    $productToBuys->push($products->random());
                }
                $productToBuys = $productToBuys->unique("id");

                for ($i = 0; $i < $productToBuys->count(); $i++) {
                    /** @var Product $product */
                    $product = $productToBuys->values()->get($i);

                    $quantity = $faker->numberBetween(1, 3);
                    $price = $order->payment_method === 'Murabahah'
                        ? intdiv($product->cash_price * 110, 100)
                        : $product->cash_price;
                    $orderItems->push(
                        OrderItem::factory()
                            ->state([
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'quantity' => $quantity,
                                'price' => $price,
                            ])
                            ->create()
                    );
                }

                $totalAmount = $orderItems->reduce(
                    fn (int $carry, OrderItem $item) => $carry + ($item->price * $item->quantity),
                    0
                );

                $order->update(['total_amount' => $totalAmount]);

                if ($order->payment_method !== 'Murabahah') {
                    continue;
                }

                $costTotal = $orderItems->reduce(function (int $carry, OrderItem $item) use ($products) {
                    /** @var Product $product */
                    $product = $products->firstWhere('id', $item->product_id);

                    return $carry + ($product?->cost_price ?? 0) * $item->quantity;
                }, 0);

                $margin = max($totalAmount - $costTotal, 0);
                $tenor = $faker->randomElement([3, 6, 9, 12]);
                $monthlyInstallment = $tenor > 0 ? intdiv($totalAmount + $tenor - 1, $tenor) : 0;

                $application = FinancingApplication::factory()
                    ->state([
                        'order_id' => $order->id,
                        'member_user_id' => $member->getKey(),
                        'admin_user_id' => $adminUser->id,
                        'tenor' => $tenor,
                        'cost_price_total' => $costTotal,
                        'margin' => $margin,
                        'selling_price_total' => $totalAmount,
                        'monthly_installment' => $monthlyInstallment,
                        'status' => 'Active',
                        'application_date' => $faker->dateTimeBetween($order->order_date, 'now'),
                        'approval_date' => $faker->dateTimeBetween($order->order_date, 'now'),
                        'rejection_reason' => null,
                        'agreement_signed_at' => $faker->dateTimeBetween($order->order_date, 'now'),
                    ])
                    ->create();

                for ($bill = 1; $bill <= $tenor; $bill++) {
                    InstallmentBill::factory()
                        ->state([
                            'financing_application_id' => $application->id,
                            'bill_number' => $bill,
                            'amount' => $monthlyInstallment,
                            'due_date' => now()->addMonths($bill)->startOfMonth()->format('Y-m-d'),
                            'payment_date' => $faker->optional()->dateTimeBetween($order->order_date, 'now')?->format('Y-m-d'),
                            'status' => $faker->randomElement(['Unpaid', 'Paid', 'Overdue']),
                        ])
                        ->create();
                }
            }
        }
    }
}
