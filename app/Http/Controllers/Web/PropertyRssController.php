<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Property;
use Illuminate\Support\Facades\Cache;

class PropertyRssController extends Controller
{
    protected $config;
    public function __construct()
    {
        $this->config = Config::where('id', 1)->first();
    }

    public function index()
    {
        $properties = Cache::remember('rss_properties', 300, function () {
            return Property::available()->latest()->limit(20)->get();
        });

        return response()
            ->view('web.'.$this->config->template.'.rss.properties', compact('properties'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
