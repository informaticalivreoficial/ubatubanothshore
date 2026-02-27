<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 2019-02-28
 * Time: 11:15
 */

namespace App\Support;

use CoffeeCode\Optimizer\Optimizer;
//use App\Models\Configuracoes;

class Seo
{
    private $optimizer;

    public function __construct()
    {
        //chama as configuracoes do site
        //$Configuracoes = Configuracoes::where('id', '1')->first();

        $this->optimizer = new Optimizer();
        $this->optimizer->openGraph(
            env('APP_NAME') ?? 'Imóveis em Ubatuba',
            'pt_BR',
            'article'
        )->publisher(
            env('CLIENT_SOCIAL_FACEBOOK_PAGE', 'default-page') ?: 'default-page',
            env('CLIENT_SOCIAL_FACEBOOK_AUTHOR', 'default-author') ?: 'default-author'
        )->facebook(
            env('CLIENT_SOCIAL_FACEBOOK_APP', '123456') ?: '123456'
        );
    }

    public function render(string $title, string $description, string $url, string $image, bool $follow = true)
    {
        return $this->optimizer->optimize($title, $description, $url, $image, $follow)->render();
    }
}