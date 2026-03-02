<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::where('status', 1);

        if ($request->city) {
            $query->where('city', $request->city);
        }

        if ($request->min_price) {
            $query->where('rental_value', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('rental_value', '<=', $request->max_price);
        }

        if ($request->dormitories) {
            $query->where('dormitories', '>=', $request->dormitories);
        }

        $properties = $query->latest()->paginate(9);

        $cities = Property::where('status', 1)
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('web.search', compact('properties', 'cities'));
    }

    public function show($slug)
    {
        $property = Property::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return view('web.property', compact('property'));
    }

    public function checkout(Property $property)
    {
        return view('web.checkout', compact('property'));
    }
}