<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatPost;

class CatPostSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 5 categorias principais
        $categories = CatPost::factory(5)->create();

        foreach ($categories as $category) {
            // Cada categoria terá 2-4 subcategorias
            CatPost::factory(rand(2, 4))->create([
                'id_pai' => $category->id,
                'type'   => $category->type, // herda o tipo do pai
            ]);
        }
    }
}
