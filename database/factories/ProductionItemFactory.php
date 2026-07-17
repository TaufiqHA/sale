<?php

namespace Database\Factories;

use App\Models\Production;
use App\Models\ProductionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductionItem>
 */
class ProductionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->randomFloat(2, 1, 100);
        $unitPrice = fake()->randomFloat(2, 10, 500);

        return [
            'production_id' => Production::factory(),
            'description' => fake()->sentence(),
            'unit_price' => $unitPrice,
            'qty' => $qty,
            'total' => $unitPrice * $qty,
        ];
    }
}
