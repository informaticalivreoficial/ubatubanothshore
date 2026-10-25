<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Post;
use App\Support\LegalContent;
use App\Support\Seo;

class PageController extends Controller
{
    protected $seo;

    protected $config;

    public function __construct()
    {
        $this->seo = new Seo;
        $this->config = Config::where('id', 1)->first();
    }

    public function page($slug)
    {
        $page = Post::where('slug', $slug)->postson()->first();

        if (empty($page)) {
            return redirect()->route('web.home');
        }

        $page->increment('views');

        $head = $this->seo->render($page->title.' - '.$this->config->app_name ?? env('APP_NAME'),
            $page->title ?? $this->config->app_name,
            route('web.page', ['slug' => $page->slug]),
            $page->cover() ?? $this->config->getmetaimg()
        );

        return view('web.page', [
            'head' => $head,
            'page' => $page,
        ]);
    }

    public function contact()
    {
        $head = $this->seo->render('Atendimento - '.$this->config->app_name ?? env('APP_NAME'),
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
        $head = $this->seo->render('Política de Privacidade - '.$this->config->app_name ?? env('APP_NAME'),
            'Leia nossa política de privacidade e saiba como protegemos seus dados.',
            route('web.privacy'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        if (empty($this->config->privacy_policy)) {
            return redirect()->route('web.home');
        }

        return view('web.privacy', [
            'head' => $head,
            'legal' => LegalContent::format($this->config->privacy_policy),
        ]);
    }

    public function terms()
    {
        $head = $this->seo->render('Termos e Condições - '.$this->config->app_name ?? env('APP_NAME'),
            'Leia nossos termos e condições e saiba como seus direitos sejam respeitados.',
            route('web.terms'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        if (empty($this->config->terms_condicions)) {
            return redirect()->route('web.home');
        }

        return view('web.terms-conditions', [
            'head' => $head,
            'legal' => LegalContent::format($this->config->terms_condicions),
        ]);
    }

    public function review($token)
    {
        $head = $this->seo->render('Avaliação - '.$this->config->app_name ?? env('APP_NAME'),
            'Deixe sua avaliação e ajude-nos a melhorar.',
            route('web.review', ['token' => $token]),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.review', [
            'token' => $token,
            'head' => $head,
        ]);
    }
}
