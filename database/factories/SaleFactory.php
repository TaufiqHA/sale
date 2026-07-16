<?php

namespace Database\Factories;

use App\Models\Counter;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
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
            'customer_id' => null,
            'expedition_id' => null,
            'barcode' => fake()->ean13(),
            'type' => 'umum',
            'marketplace_id' => null,
            'courier_id' => null,
            'date' => now(),
            'subtotal' => 10000.00,
            'discount' => 0.00,
            'grand_total' => 10000.00,
            'payment_method' => 'tunai',
        ];
    }
}
