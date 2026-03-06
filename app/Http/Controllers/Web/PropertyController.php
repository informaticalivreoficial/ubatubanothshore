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

    public function search(Request $request)
    {
        $check_in = $request->query('check_in');
        $check_out = $request->query('check_out');
        $guests = $request->query('guests', 1);

        $query = Property::available();

        if ($check_in && $check_out) {
            $query->whereDoesntHave('reservations', function ($q) use ($check_in, $check_out) {
                $q->where(function ($q2) use ($check_in, $check_out) {
                    $q2->whereBetween('start_date', [$check_in, $check_out])
                       ->orWhereBetween('end_date', [$check_in, $check_out])
                       ->orWhere(function ($q3) use ($check_in, $check_out) {
                           $q3->where('start_date', '<=', $check_in)
                              ->where('end_date', '>=', $check_out);
                       });
                });
            });

            $query->whereDoesntHave('blockedDates', function ($q) use ($check_in, $check_out) {
                $q->whereBetween('date', [$check_in, $check_out]);
            });
        }

        if ($guests) {
            $query->where('capacity', '>=', $guests);
        }

        $properties = $query
            ->withCount(['reviews as reviews_count' => fn($q) => $q->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn($q) => $q->approved()], 'rating')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $head = $this->seo->render('Pesquisar Imóveis - ' .   $this->config->app_name ?? env('APP_NAME'),
            'Pesquisar Imóveis' ?? $this->config->app_name,
            route('web.property.search'),
            $this->config->getmetaimg()
        );

        return view('web.search-property', [
            'properties' => $properties,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'guests' => $guests,
            'head' => $head
        ]);
    }

}