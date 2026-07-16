<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Counter;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'counter_id' => Counter::factory(),
            'sku' => fake()->unique()->bothify('SKU-#####'),
            'barcode' => fake()->unique()->ean13(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'buy_price' => fake()->randomFloat(2, 5, 100),
            'sell_price' => fake()->randomFloat(2, 10, 200),
            'stock' => fake()->numberBetween(0, 100),
            'status' => true,
        ];
    }
}
