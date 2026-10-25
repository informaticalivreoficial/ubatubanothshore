<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CatPost;
use App\Models\Config;
use App\Models\Post;
use App\Models\Property;
use App\Support\Seo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $seo;

    protected $config;

    public function __construct()
    {
        $this->seo = new Seo;
        $this->config = Config::where('id', 1)->first();
    }

    public function index()
    {
        $properties = Property::available()
            ->withCount(['reviews as reviews_count' => function ($query) {
                $query->where('approved', true);
            }])
            ->withAvg(['reviews as reviews_avg_rating' => function ($query) {
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
        $posts = Post::with('categoryObject')
            ->whereIn('type', ['artigo', 'noticia'])
            ->postson()
            ->orderByDesc('created_at')
            ->paginate(21);

        $head = $this->seo->render('Blog - '.$this->config->app_name ?? env('APP_NAME'),
            'Confira nossos artigos e notícias sobre o mercado imobiliário.',
            route('web.blog.index'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.blog.index', [
            'head' => $head,
            'posts' => $posts,
            'categorias' => $this->blogCategories(),
        ]);
    }

    public function blogCategory($slug)
    {
        $category = CatPost::where('slug', $slug)->firstOrFail();

        $categoryIds = $category->children()->pluck('id')->push($category->id);

        $posts = Post::with('categoryObject')
            ->whereIn('category', $categoryIds)
            ->postson()
            ->orderByDesc('created_at')
            ->paginate(21);

        $head = $this->seo->render('Blog - '.$category->title ?? env('APP_NAME'),
            'Confira nossos artigos e notícias sobre Ubatuba na categoria '.$category->title.'.',
            route('web.blog.category', ['slug' => $category->slug]),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.blog.index', [
            'head' => $head,
            'posts' => $posts,
            'categorias' => $this->blogCategories(),
            'category' => $category,
        ]);
    }

    public function artigo(Request $request)
    {
        return $this->showPost($request, 'artigo');
    }

    public function noticia(Request $request)
    {
        return $this->showPost($request, 'noticia');
    }

    private function showPost(Request $request, string $type)
    {
        $post = Post::with('categoryObject')
            ->where('type', $type)
            ->where('slug', $request->slug)
            ->postson()
            ->firstOrFail();

        $post->increment('views');

        $categorias = $this->blogCategories([$type]);
        $postsMais = Post::where('type', $type)
            ->postson()
            ->orderByDesc('views')
            ->limit(3)
            ->get();

        $relacionados = Post::with('categoryObject')
            ->where('type', $type)
            ->postson()
            ->where('id', '!=', $post->id)
            ->when($post->category, fn ($query) => $query->where('category', $post->category))
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $head = $this->seo->render(
            ($type === 'noticia' ? 'Notícia' : 'Blog').' - '.$post->title,
            $post->title,
            route($type === 'noticia' ? 'web.blog.noticia' : 'web.blog.artigo', ['slug' => $post->slug]),
            $post->nocover() ?? $this->config->getmetaimg()
        );

        return view('web.blog.article', [
            'head' => $head,
            'post' => $post,
            'postsMais' => $postsMais,
            'relacionados' => $relacionados,
            'categorias' => $categorias,
            'todasTags' => $this->blogTags($type),
        ]);
    }

    private function blogCategories(array $types = ['artigo', 'noticia'])
    {
        return CatPost::whereIn('type', $types)
            ->where('status', 1)
            ->withCount(['posts' => fn ($query) => $query->where('status', 1)])
            ->orderBy('title')
            ->get()
            ->filter(fn ($category) => $category->posts_count > 0)
            ->values();
    }

    private function blogTags(string $type)
    {
        return Post::where('type', $type)
            ->postson()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(fn ($tags) => array_map('trim', explode(',', $tags)))
            ->filter()
            ->unique()
            ->take(20)
            ->values();
    }
}
