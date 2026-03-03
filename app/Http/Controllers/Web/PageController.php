<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\Seo;
use App\Models\Config;
use App\Models\Post;

class PageController extends Controller
{

    protected $seo, $config;

    public function __construct()
    {
        $this->seo = new Seo();
        $this->config = Config::where('id', 1)->first();
    }

    public function page($slug)
    {
        $page = Post::where('slug', $slug)->postson()->first();

        if(empty($page)){
            return redirect()->route('web.home');
        }

        $head = $this->seo->render($page->title . ' - ' . $this->config->app_name ?? env('APP_NAME'),
            $page->headline ?? $page->title,
            route('web.page', ['slug' => $page->slug]),
            $page->cover() ?? $this->config->getmetaimg()
        );

        return view("web.page",[
            'head' => $head,
            'page' => $page,
        ]);
    }

    public function contact()
    {
        $head = $this->seo->render('Atendimento - ' . $this->config->app_name ?? env('APP_NAME'),
            'Entre em contato conosco, teremos prazer em atendê-lo!',
            route('web.contact'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.contact', [
            'head' => $head,
        ]);
    }

    public function privacy()
    {
        $head = $this->seo->render('Política de Privacidade - ' . $this->config->app_name ?? env('APP_NAME'),
            'Leia nossa política de privacidade e saiba como protegemos seus dados.',
            route('web.privacy'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        if(empty($this->config->privacy_policy)){
            return redirect()->route('web.home');
        }

        return view("web.privacy",[
            'head' => $head,
        ]);
    }
}