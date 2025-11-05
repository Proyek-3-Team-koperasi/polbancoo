<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $costPrice = fake()->numberBetween(100000, 2000000);

        return [
            'category_id' => ProductCategory::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numerify('###'),
            'description' => fake()->paragraph(),
            'image_url' => fake()->optional()->imageUrl(),
            'cost_price' => $costPrice,
            'cash_price' => $costPrice + fake()->numberBetween(50000, 500000),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
