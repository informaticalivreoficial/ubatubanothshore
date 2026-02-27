<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('templates')->insert([
            'id' => 1,
            'name' => 'default',
            'image' => null,
            'content' => 'teste',
            'status' => 1           
        ]);
    }
}
