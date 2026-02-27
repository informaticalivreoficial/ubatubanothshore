<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = Property::all();
        $users = User::all();

        foreach ($properties as $property) {

            $checkin = now()->addDays(rand(5, 20));
            $checkout = (clone $checkin)->addDays(rand(3,7));

            PropertyReservation::create([
                'property_id' => $property->id,
                'user_id' => $users->random()->id,
                'check_in' => $checkin,
                'check_out' => $checkout,
                'total_value' => rand(2000, 8000),
                'status' => 'confirmed',
            ]);
        }
    }
}
