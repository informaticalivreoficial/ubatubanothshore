<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CatPost;
use App\Models\Config;
use App\Models\Post;
use App\Models\Property;
use App\Models\Slide;
use App\Support\Seo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Webcontroller extends Controller
{
    protected $seo, $config;

    public function __construct()
    {
        $this->seo = new Seo();
        $this->config = Config::where('id', 1)->first();
    }

    public function home()
    {
        $propertiesHighlights = Property::orderBy('created_at', 'DESC')
                                ->available()
                                ->where('highlight', 1)
                                ->limit(3)
                                ->get();                            
        $propertiesViews = Property::orderBy('created_at', 'DESC')
                                ->available()
                                ->limit(8)
                                ->get();                            
        $slides = Slide::orderBy('created_at', 'DESC')
                            ->available()
                            ->whereDate('expired_at', '>=', Carbon::today())
                            ->get(); 
        
        $artigos = Post::orderBy('created_at', 'DESC')
                            ->where('type', 'artigo')
                            ->orWhere('type', 'noticia')
                            ->postson()
                            ->limit(3)
                            ->get();
        
        // Buscar os 4 bairros com maior número de imóveis
        $bairros = Property::select('neighborhood')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('neighborhood')
            ->orderByDesc('total')
            ->available()
            ->limit(4)
            ->get();

        // Buscar uma imagem de um imóvel para cada bairro
        $bairros->transform(function ($b) {

            // pegar um imóvel aleatório do bairro
            $property = Property::where('neighborhood', $b->neighborhood)
                ->available()
                ->inRandomOrder()
                ->first();

            // imagem aleatória do imóvel ou fallback
            $b->img = $property
                ? $property->nocover()
                : asset('theme/images/image.jpg');

            return $b;
        });
        
        $head = $this->seo->render($this->config->app_name ?? env('APP_NAME'),
            $this->config->information ?? env('APP_NAME'),
            route('web.home'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.'.$this->config->template.'.home',[
            'propertiesHighlights' => $propertiesHighlights,
            'propertiesViews' => $propertiesViews,
            'artigos' => $artigos,
            'head' => $head,
            'slides' => $slides,    
            'bairros' => $bairros,        
        ]);
    }

    public function Properties()
    {
        $head = $this->seo->render('Últimos imóveis cadastrados - ' . $this->config->app_name ?? env('APP_NAME'),
            'Confira nossos imóveis disponíveis para venda e locação.',
            route('web.properties'),
            $this->config->getMetaImg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.'.$this->config->template.'.properties.properties',[
            'head' => $head,
            'title' => 'Últimos imóveis cadastrados',
        ]);        
    }

    public function propertyNeighborhood($neighborhood)
    {
        $head = $this->seo->render('Imóveis no bairro de ' . ucwords(str_replace('-', ' ', $neighborhood)) . ' - ' . $this->config->app_name ?? env('APP_NAME'),
            'Confira os imóveis disponíveis para venda e locação no bairro de ' . ucwords(str_replace('-', ' ', $neighborhood)) . '.',
            route('web.properties.neighborhood', ['neighborhood' => $neighborhood]),
            $this->config->getMetaImg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.'.$this->config->template.'.properties.properties',[
            'head' => $head,
            'neighborhood' => $neighborhood,
            'title' => 'Imóveis no bairro de ' . ucwords(str_replace('-', ' ', $neighborhood)),
        ]);
    }

    public function propertyList($type)
    {
        $head = $this->seo->render('Imóveis para ' . $type . $this->config->app_name ?? env('APP_NAME'),
            'Confira os imóveis para '.$type.' temos ótimas oportunidades de negócio.',
            route('web.propertylist', $type),
            $this->config->getMetaImg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.'.$this->config->template.'.properties.properties',[
            'head' => $head,
            'type' => $type,
            'title' => ($type === 'venda' ? 'Imóveis para Venda' : 'Imóveis para Locação'),
        ]);
    }

    public function property($slug)
    {
        $property = Property::where('slug', $slug)->available()->first();
        $propertiesrelated = Property::where('id', '!=', $property->id)
                            ->available()
                            ->limit(4)
                            ->get();

        if(!empty($property)){
            $property->views = $property->views + 1;
            $property->save();

            $head = $this->seo->render($property->title ?? env('APP_NAME'),
                $property->headline ?? $property->title,
                route('web.property', ['slug' => $property->slug]),
                $property->nocover() ?? $this->config->getMetaImg()
            );

            return view('web.'.$this->config->template.'.properties.property', [
                'head' => $head,
                'property' => $property,
                'propertiesrelated' => $propertiesrelated,
            ]);
        }
    }

    public function pesquisaImoveis(Request $request)
    {
        $head = $this->seo->render('Pesquisa de Imóveis - ' . $this->config->app_name ?? env('APP_NAME'),
            'Resultados da sua pesquisa de imóveis.',
            route('web.pesquisar-imoveis'),
            $this->config->getMetaImg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.'.$this->config->template.'.properties.search-property',[
            'head' => $head
        ]);
    }

    public function PropertyHighliths()
    {
        $head = $this->seo->render('Lançamentos - ' . $this->config->app_name ?? env('APP_NAME'),
            'Confira nossos lançamentos imobiliários.',
            route('web.highliths'),
            $this->config->getMetaImg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.'.$this->config->template.'.properties.properties',[
            'head' => $head,
            'title' => 'Lançamentos',
            'highlighted' => true,
        ]);
    }

    public function blog()
    {
        $posts = Post::where(function ($query) {
                    $query->where('type', 'artigo')
                          ->orWhere('type', 'noticia');
                })
                ->postson()
                ->orderBy('created_at', 'DESC')
                ->paginate(21);

        $head = $this->seo->render('Blog - ' . $this->config->app_name ?? env('APP_NAME'),
            'Confira nossos artigos e notícias sobre o mercado imobiliário.',
            route('web.blog.index'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view("web.{$this->config->template}.blog.index",[
            'head' => $head,
            'posts' => $posts,
        ]);
    }

    public function blogCategory($slug)
    {
        $category = CatPost::where('slug', $slug)->first();

        $posts = Post::where('category', $category->id)
                ->postson()
                ->orderBy('created_at', 'DESC')
                ->paginate(21);

        $head = $this->seo->render('Blog - ' . $category->title ?? env('APP_NAME'),
            'Confira nossos artigos e notícias sobre o mercado imobiliário na categoria '.$category->title.'.',
            route('web.blog.category', ['slug' => $category->slug]),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view("web.{$this->config->template}.blog.index",[
            'head' => $head,
            'posts' => $posts,
            'category' => $category,
        ]);
    }

    public function artigo(Request $request)
    {
        $post = Post::where('slug', $request->slug)->postson()->first();

        $postsTags = Post::where('type', 'artigo')->postson()->limit(3)->get();
        $categorias = CatPost::orderBy('title', 'ASC')->where('type', 'artigo')->get();
        $postsMais = Post::orderBy('views', 'DESC')->where('type', 'artigo')->limit(3)->postson()->get();
        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render('Blog - ' . $post->title ?? env('APP_NAME'),
            $post->title,
            route('web.blog.artigo', ['slug' => $post->slug]),
            $post->nocover() ?? $this->config->getmetaimg()
        );

        return view("web.{$this->config->template}.blog.article", [
            'head' => $head,
            'post' => $post,
            'postsMais' => $postsMais,
            'categorias' => $categorias,
            'postsTags' => $postsTags,
        ]);
    }

    public function noticia(Request $request)
    {
        $post = Post::where('slug', $request->slug)->postson()->first();

        $postsTags = Post::where('type', 'noticia')->postson()->limit(3)->get();
        $categorias = CatPost::orderBy('title', 'ASC')->where('type', 'noticia')->get();
        $postsMais = Post::orderBy('views', 'DESC')->where('type', 'noticia')->limit(3)->postson()->get();
        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render('Blog - ' . $post->title ?? env('APP_NAME'),
            $post->title,
            route('web.blog.noticia', ['slug' => $post->slug]),
            $post->cover() ?? $this->config->getmetaimg()
        );

        return view("web.{$this->config->template}.blog.article", [
            'head' => $head,
            'post' => $post,
            'postsMais' => $postsMais,
            'categorias' => $categorias,
            'postsTags' => $postsTags,
        ]);
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

        return view("web.{$this->config->template}.page",[
            'head' => $head,
            'page' => $page,
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

        return view("web.{$this->config->template}.privacy",[
            'head' => $head,
        ]);
    }

    public function contact()
    {
        $head = $this->seo->render('Atendimento - ' . $this->config->app_name ?? env('APP_NAME'),
            'Entre em contato conosco, teremos prazer em atendê-lo!',
            route('web.contact'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view("web.{$this->config->template}.contact", [
            'head' => $head
        ]);
    }

    public function creditSimulator()
    {
        $head = $this->seo->render('Simulador de Crédito Imobiliário - ' . $this->config->app_name ?? env('APP_NAME'),
            'Simule seu crédito imobiliário conosco!',
            route('web.simulator'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view("web.{$this->config->template}.properties.simulator", [
            'head' => $head
        ]);
    }
}
