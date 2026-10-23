<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarSeries;
use App\Models\Seller;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Car::available()
            ->featured()
            ->with(['primaryImage', 'series', 'seller'])
            ->take(6)
            ->get();

        $newArrivals = Car::available()
            ->with(['primaryImage', 'series'])
            ->latest()
            ->take(8)
            ->get();

        $series = CarSeries::orderBy('order')->get();

        $topSellers = Seller::where('status', 'verified')
            ->orderByDesc('rating_avg')
            ->take(6)
            ->get();

        $recommended = Car::available()
            ->with(['primaryImage', 'series'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact(
            'featured', 'newArrivals', 'series', 'topSellers', 'recommended'
        ));
    }
}