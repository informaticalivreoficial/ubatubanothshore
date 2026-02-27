<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::factory()->count(30)->create();
    }
}
