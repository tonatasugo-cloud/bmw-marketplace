<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarSeries;
use App\Models\Seller;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['seller', 'primaryImage', 'series']);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $cars = $query->latest()->paginate(15)->withQueryString();

        return view('admin.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        $car->load(['seller.user', 'series', 'images', 'inspections', 'histories']);
        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        $series = CarSeries::orderBy('order')->get();
        return view('admin.cars.edit', compact('car', 'series'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required|numeric|min:10000000',
            'year'        => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'mileage'     => 'required|integer|min:0',
            'description' => 'required|string',
            'status'      => 'required|in:draft,pending,available,reserved,sold,rejected,archived',
            'is_featured' => 'boolean',
            'is_certified' => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_certified'] = $request->boolean('is_certified');

        $car->update($validated);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Mobil berhasil diperbarui.');
    }

    public function approve(Car $car)
    {
        $car->update([
            'status'       => 'available',
            'published_at' => now(),
        ]);

        return back()->with('success', 'Listing disetujui & dipublikasikan.');
    }

    public function reject(Request $request, Car $car)
    {
        $car->update(['status' => 'rejected']);

        return back()->with('success', 'Listing ditolak.');
    }

    public function feature(Car $car)
    {
        $car->update(['is_featured' => !$car->is_featured]);

        return back()->with('success', 'Status featured diubah.');
    }

    public function destroy(Car $car)
    {
        if (in_array($car->status, ['reserved', 'sold'])) {
            return back()->withErrors(['msg' => 'Mobil yang direservasi/terjual tidak bisa dihapus.']);
        }

        $car->delete();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Mobil dihapus.');
    }
}