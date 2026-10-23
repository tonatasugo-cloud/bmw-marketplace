<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarHistory;
use App\Models\CarImage;
use App\Models\CarInspection;
use App\Models\CarSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarController extends Controller
{
    private function getSeller()
    {
        $seller = auth()->user()->seller;
        if (!$seller) {
            abort(403, 'Anda belum terdaftar sebagai seller.');
        }
        return $seller;
    }

    public function index()
    {
        $seller = $this->getSeller();
        $cars = Car::where('seller_id', $seller->id)
            ->with('primaryImage')
            ->latest()
            ->paginate(10);

        return view('seller.cars.index', compact('cars'));
    }

    public function create()
    {
        $series = CarSeries::orderBy('order')->get();
        return view('seller.cars.create', compact('series'));
    }

    public function store(Request $request)
    {
        $seller = $this->getSeller();

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'car_series_id'     => 'required|exists:car_series,id',
            'year'              => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'price'             => 'required|numeric|min:10000000',
            'mileage'           => 'required|integer|min:0',
            'transmission'      => 'required|string',
            'fuel_type'         => 'required|string',
            'engine_capacity'   => 'required|numeric',
            'drivetrain'        => 'required|string',
            'exterior_color'    => 'required|string',
            'interior_color'    => 'nullable|string',
            'body_type'         => 'required|string',
            'condition'         => 'required|in:excellent,good,fair',
            'is_certified'      => 'boolean',
            'location_city'     => 'required|string',
            'location_province' => 'required|string',
            'plate_number'      => 'nullable|string',
            'vin'               => 'nullable|string',
            'seat_capacity'     => 'nullable|integer',
            'door_count'        => 'nullable|integer',
            'description'       => 'required|string',
            'features'          => 'nullable|array',
            'images.*'          => 'image|max:5120',
        ]);

        $validated['seller_id'] = $seller->id;
        $validated['slug'] = Str::slug($validated['title'] . '-' . uniqid());
        $validated['feature_list'] = $request->features ?? [];
        $validated['status'] = 'pending';
        $validated['is_certified'] = $request->boolean('is_certified');

        $car = Car::create($validated);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('cars', 'public');
                CarImage::create([
                    'car_id'     => $car->id,
                    'path'       => $path,
                    'category'   => 'exterior',
                    'is_primary' => $i === 0,
                    'order'      => $i,
                ]);
            }
        }

        return redirect()
            ->route('seller.cars.index')
            ->with('success', 'Mobil berhasil ditambahkan. Menunggu approval admin.');
    }

    public function show(Car $car)
    {
        $this->authorizeCar($car);
        return redirect()->route('seller.cars.edit', $car);
    }

    public function edit(Car $car)
    {
        $this->authorizeCar($car);
        $series = CarSeries::orderBy('order')->get();
        return view('seller.cars.edit', compact('car', 'series'));
    }

    public function update(Request $request, Car $car)
    {
        $this->authorizeCar($car);

        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'price'  => 'required|numeric|min:10000000',
            'description' => 'required|string',
        ]);

        $car->update($validated);

        return redirect()
            ->route('seller.cars.index')
            ->with('success', 'Mobil diperbarui.');
    }

    public function destroy(Car $car)
    {
        $this->authorizeCar($car);

        if ($car->status === 'sold') {
            return back()->withErrors(['msg' => 'Mobil yang sudah terjual tidak bisa dihapus.']);
        }

        $car->delete();

        return redirect()
            ->route('seller.cars.index')
            ->with('success', 'Mobil dihapus.');
    }

    private function authorizeCar(Car $car)
    {
        if ($car->seller_id !== $this->getSeller()->id) {
            abort(403);
        }
    }
}