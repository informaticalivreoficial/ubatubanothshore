<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Support\Seo;
use App\Models\Config;

class PropertyController extends Controller
{
    protected $seo, $config;

    public function __construct()
    {
        $this->seo = new Seo();
        $this->config = Config::where('id', 1)->first();
    }

    public function index(Request $request)
    { 
        $head = $this->seo->render('Pesquisar Imóveis - ' . $this->config->app_name ?? env('APP_NAME'),
            'Pesquisar Imóveis' ?? $this->config->app_name,
            route('web.properties'),
            $this->config->getmetaimg()
        );

        return view('web.search', [
            'head' => $head
        ]);
    }

    public function show($slug)
    {
        $property = Property::where('slug', $slug)
            ->available()
            ->with(['reviews' => function($query) {
                $query->approved()->latest(); // só reviews aprovadas
            }])
            ->withCount(['reviews as reviews_count' => fn($q) => $q->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn($q) => $q->approved()], 'rating')
            ->firstOrFail();

        if(empty($property)){
            return redirect()->route('web.home');
        }

        $property->increment('views');

        $head = $this->seo->render($property->title . ' - ' . $this->config->app_name ?? env('APP_NAME'),
            $property->headline ?? $property->title,
            route('web.property', ['slug' => $property->slug]),
            $property->cover() ?? $this->config->getmetaimg()
        );

        return view('web.property', [
            'head' => $head,
            'property' => $property
        ]);
    }

    public function checkout(Property $property)
    {
        $head = $this->seo->render('Reservar - ' . $property->title . ' - ' .   $this->config->app_name ?? env('APP_NAME'),
            $property->headline ?? $property->title,
            route('web.checkout', ['property' => $property->id]),
            $property->cover() ?? $this->config->getmetaimg()
        );

        return view('web.checkout', [
            'property' => $property,
            'head' => $head
        ]);
    }
}