<?php

namespace Database\Factories;

use App\Models\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Counter>
 */
class CounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'description' => fake()->sentence(),
            'status' => true,
        ];
    }
}
