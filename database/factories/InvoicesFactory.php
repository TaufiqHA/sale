<?php

namespace Database\Factories;

use App\Models\Invoices;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoices>
 */
class InvoicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_id' => Sale::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('#####'),
            'type' => 'umum',
            'printed_count' => 0,
        ];
    }
}
