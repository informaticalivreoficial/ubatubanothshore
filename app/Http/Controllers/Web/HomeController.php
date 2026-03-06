<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Support\Seo;
use App\Models\Config;

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
}