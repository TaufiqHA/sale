<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductWholeprice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductWholeprice>
 */
class ProductWholepriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'minimum_qty' => fake()->numberBetween(2, 50),
            'wholeprice_price' => fake()->randomFloat(2, 5, 100),
        ];
    }
}
