<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CatPost;
use App\Models\Property;
use App\Support\Seo;
use App\Models\Config;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $seo, $config;

    public function __construct()
    {
        $this->seo = new Seo();
        $this->config = Config::where('id', 1)->first();
    }

    public function index()
    {
        $properties = Property::available()
            ->withCount(['reviews as reviews_count' => function($query) {
                $query->where('approved', true);
            }])
            ->withAvg(['reviews as reviews_avg_rating' => function($query) {
                $query->where('approved', true);
            }], 'rating')
            ->latest()
            ->take(8)
            ->get();  
            
        $head = $this->seo->render($this->config->app_name ?? env('APP_NAME'),
            $this->config->information ?? env('APP_NAME'),
            route('web.home'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.home', [
            'head' => $head,
            'properties' => $properties,
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

        return view("web.blog.index",[
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
            'Confira nossos artigos e notícias sobre Ubatuba na categoria '.$category->title.'.',
            route('web.blog.category', ['slug' => $category->slug]),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view("web.blog.index",[
            'head' => $head,
            'posts' => $posts,
            'category' => $category,
        ]);
    }

    public function artigo(Request $request)
    {
        $post = Post::where('slug', $request->slug)->postson()->first();

        $postsTags = Post::where('type', 'artigo')->postson()->limit(3)->get();
        $categorias = CatPost::orderBy('title', 'ASC')
                ->where('type', 'artigo')
                ->whereNull('id_pai')
                ->with(['children' => fn($q) => $q->where('status', 1)])
                ->available()
                ->get();
        $postsMais = Post::orderBy('views', 'DESC')->where('type', 'artigo')->limit(3)->postson()->get();
        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render('Blog - ' . $post->title ?? env('APP_NAME'),
            $post->title,
            route('web.blog.artigo', ['slug' => $post->slug]),
            $post->nocover() ?? $this->config->getmetaimg()
        );

        return view("web.blog.article", [
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

        return view("web.blog.article", [
            'head' => $head,
            'post' => $post,
            'postsMais' => $postsMais,
            'categorias' => $categorias,
            'postsTags' => $postsTags,
        ]);
    }
}