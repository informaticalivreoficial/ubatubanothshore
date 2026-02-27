<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ConfigTableSeeder::class,
            TemplateTableSeeder::class,
            PropertyTableSeeder::class,
            PropertyGbTableSeeder::class,
            PropertySeasonSeeder::class,
            //PropertyBlockedDateSeeder::class,
            //CommentSeeder::class,
            PropertyReservationSeeder::class,
            CatPostSeeder::class
        ]);
    }
}
