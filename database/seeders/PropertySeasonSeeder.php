<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertySeason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = Property::all();

        foreach ($properties as $property) {

            PropertySeason::create([
                'property_id' => $property->id,
                'start_date' => now()->month(12)->startOfMonth(),
                'end_date' => now()->month(12)->endOfMonth(),
                'price_per_day' => rand(1500, 3000),
            ]);

            // Segunda temporada (janeiro)
            PropertySeason::create([
                'property_id' => $property->id,
                'start_date' => now()->addYear()->month(1)->startOfMonth(),
                'end_date' => now()->addYear()->month(1)->endOfMonth(),
                'price_per_day' => rand(1200, 2500),
            ]);
        }
    }
}
