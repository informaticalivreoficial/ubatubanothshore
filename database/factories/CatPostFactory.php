<?php

namespace Database\Factories;

use App\Models\CatPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatPostFactory extends Factory
{
    protected $model = CatPost::class;

    public function definition()
    {
        return [
            'title'       => $this->faker->words(2, true),
            'slug'        => $this->faker->slug(),
            'content'     => $this->faker->sentence(),
            'status'      => $this->faker->boolean(),
            'type'        => $this->faker->randomElement(['artigo', 'noticia', 'pagina']),
            'id_pai'      => null, // raiz por padrão
        ];
    }
}
