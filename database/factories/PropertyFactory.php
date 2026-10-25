<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            // 'owner' => User::factory(),
            'reference' => Str::random(4),
            'display_values' => $this->faker->boolean(),
            'rental_value' => $this->faker->numberBetween($min = 1500, $max = 6000),
            'rental_value_couple' => $this->faker->numberBetween($min = 2000, $max = 8000),
            'cleaning_fee' => $this->faker->numberBetween($min = 100, $max = 500),
            'iptu_fee' => $this->faker->numberBetween($min = 50, $max = 300),
            'value_aditional' => $this->faker->numberBetween($min = 100, $max = 400),
            'aditional_person' => $this->faker->numberBetween($min = 1, $max = 3),
            'capacity' => $this->faker->numberBetween($min = 2, $max = 6),
            'min_nights' => 1,
            'slug' => $this->faker->slug(3),
            'category' => 'Imóvel Residencial',
            'type' => 'Casa',
            'description' => $this->faker->paragraph(10),
            'dormitories' => $this->faker->numberBetween($min = 0, $max = 10),
            'status' => $this->faker->boolean(),
            'views' => $this->faker->randomNumber(3),
            'display_address' => $this->faker->boolean(),
            'zipcode' => $this->faker->randomNumber(8),
            'city' => $this->faker->city(),
            'state' => $this->faker->regionAbbr(),
            'measures' => 'm²',
            'geladeira' => $this->faker->boolean(),
            'wifi' => $this->faker->boolean(),
            'jardim' => $this->faker->boolean(),
            'ventilador_teto' => $this->faker->boolean(),
        ];
    }
}
