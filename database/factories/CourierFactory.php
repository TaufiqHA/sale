<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['JNE', 'J&T', 'Sicepat', 'SPX', 'Ninja Xpress', 'Wahana', 'Anteraja']),
            'type' => fake()->randomElement(['umum', 'marketplace', 'keduanya']),
        ];
    }
}
