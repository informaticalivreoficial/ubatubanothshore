<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $properties = Property::where('status', 1)
            //->where('highlight', 1) // opcional
            ->latest()
            ->take(8)
            ->get();        

        return view('web.home', compact('properties'));
    }
}