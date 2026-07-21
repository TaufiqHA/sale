<?php

namespace Database\Factories;

use App\Models\Recipts;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipts>
 */
class ReciptsFactory extends Factory
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
            'receipt_number' => 'RCP-'.fake()->unique()->numerify('#####'),
            'type' => 'umum',
            'printed_count' => 0,
        ];
    }
}
