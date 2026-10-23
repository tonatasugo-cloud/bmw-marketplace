<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarSeries;
use App\Models\RecentlyViewed;
use App\Models\Seller;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::available()->with(['primaryImage', 'series', 'seller']);

        // Filter
        if ($request->filled('series')) {
            $query->whereHas('series', fn($q) => $q->where('slug', $request->series));
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }
        if ($request->filled('drivetrain')) {
            $query->where('drivetrain', $request->drivetrain);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('city')) {
            $query->where('location_city', $request->city);
        }
        if ($request->filled('max_mileage')) {
            $query->where('mileage', '<=', $request->max_mileage);
        }
        if ($request->boolean('certified')) {
            $query->where('is_certified', true);
        }

        // Sort
        match ($request->sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'year_desc'  => $query->orderByDesc('year'),
            'year_asc'   => $query->orderBy('year'),
            'mileage'    => $query->orderBy('mileage'),
            default      => $query->latest(),
        };

        $cars   = $query->paginate(12)->withQueryString();
        $series = CarSeries::orderBy('order')->get();

        return view('cars.index', compact('cars', 'series'));
    }

    public function show($slug)
    {
        $car = Car::with([
            'images', 'inspections', 'histories',
            'series', 'seller.user',
        ])->where('slug', $slug)->firstOrFail();

        // Increment view
        $car->increment('view_count');

        // Track recently viewed
        RecentlyViewed::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'session_id' => auth()->check() ? null : session()->getId(),
                'car_id'     => $car->id,
            ],
            ['viewed_at' => now()]
        );

        // Related cars (same series)
        $related = Car::available()
            ->where('car_series_id', $car->car_series_id)
            ->where('id', '!=', $car->id)
            ->with('primaryImage')
            ->take(4)
            ->get();

        // Is favorited?
        $isFavorited = auth()->check()
            && auth()->user()->favorites()->where('car_id', $car->id)->exists();

        return view('cars.show', compact('car', 'related', 'isFavorited'));
    }

    public function sellerProfile($slug)
    {
        $seller = Seller::with('user')
            ->where('slug', $slug)
            ->where('status', 'verified')
            ->firstOrFail();

        $cars = Car::available()
            ->where('seller_id', $seller->id)
            ->with('primaryImage')
            ->paginate(12);

        $reviews = $seller->reviews()->with('user')->latest()->take(5)->get();

        return view('sellers.show', compact('seller', 'cars', 'reviews'));
    }
}