<?php

namespace Database\Factories;

use App\Models\Counter;
use App\Models\Product;
use App\Models\Production;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Production>
 */
class ProductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'counter_id' => Counter::factory(),
            'product_id' => Product::factory(),
            'production_date' => fake()->date(),
            'total_cost' => fake()->randomFloat(2, 50, 1000),
            'total_result' => fake()->numberBetween(1, 100),
            'hpp' => fake()->randomFloat(2, 5, 100),
            'selling_price' => fake()->randomFloat(2, 10, 200),
            'estimated_profit' => fake()->randomFloat(2, 5, 100),
            'notes' => fake()->sentence(),
            'status' => fake()->randomElement(['draft', 'completed', 'cancelled']),
        ];
    }
}
