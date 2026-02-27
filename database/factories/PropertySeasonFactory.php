<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertySeason>
 */
class PropertySeasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(60),
            'price_per_day' => fake()->numberBetween(800, 2500),
        ];
    }
}
