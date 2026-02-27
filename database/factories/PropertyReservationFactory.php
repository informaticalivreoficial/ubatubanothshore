<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyReservation>
 */
class PropertyReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkin = fake()->dateTimeBetween('+5 days', '+20 days');
        $checkout = (clone $checkin)->modify('+'.rand(2,7).' days');

        return [
            'check_in' => $checkin,
            'check_out' => $checkout,
            'total_value' => fake()->numberBetween(1500, 8000),
            'status' => 'confirmed',
        ];
    }
}
