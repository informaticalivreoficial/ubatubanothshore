<?php

namespace Database\Seeders;

use App\Models\PropertyGb;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyGbTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyGb::factory()->count(30)->create();
    }
}
