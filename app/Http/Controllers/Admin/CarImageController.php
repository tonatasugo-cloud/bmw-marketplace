<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarImageController extends Controller
{
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'category' => 'nullable|in:exterior,interior,dashboard,engine,trunk,wheel,tire,other',
        ]);

        $category = $request->input('category', 'exterior');

        // Cek kalau ini gambar pertama
        $existingCount = CarImage::where('car_id', $car->id)->count();

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('cars', 'public');

            CarImage::create([
                'car_id'     => $car->id,
                'path'       => $path,
                'category'   => $category,
                'is_primary' => $existingCount === 0 && $index === 0,
                'order'      => $existingCount + $index,
            ]);
        }

        return back()->with('success', count($request->file('images')) . ' foto berhasil diupload.');
    }

    public function setPrimary(CarImage $image)
    {
        // Reset primary image lain
        CarImage::where('car_id', $image->car_id)
            ->update(['is_primary' => false]);

        // Set yang baru
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Foto utama berhasil diubah.');
    }

    public function updateCategory(Request $request, CarImage $image)
    {
        $validated = $request->validate([
            'category' => 'required|in:exterior,interior,dashboard,engine,trunk,wheel,tire,other',
        ]);

        $image->update($validated);

        return back()->with('success', 'Kategori foto diperbarui.');
    }

    public function destroy(CarImage $image)
    {
        $carId = $image->car_id;
        $wasPrimary = $image->is_primary;

        // Hapus file dari storage
        if ($image->path && !str_starts_with($image->path, 'http')) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        // Kalau yang dihapus adalah primary, jadikan gambar lain sebagai primary
        if ($wasPrimary) {
            $nextImage = CarImage::where('car_id', $carId)
                ->orderBy('order')
                ->first();

            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}