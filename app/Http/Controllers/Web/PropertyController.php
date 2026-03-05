<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    { 
        return view('web.search');
    }

    public function show($slug)
    {
        $property = Property::where('slug', $slug)
            ->available()
            ->firstOrFail();

        if(empty($property)){
            return redirect()->route('web.home');
        }

        $property->views = $property->views + 1;
        $property->save();

        return view('web.property', compact('property'));
    }

    public function checkout(Property $property)
    {
        return view('web.checkout', compact('property'));
    }
}