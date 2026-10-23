@extends('layouts.app')

@section('title', 'Edit Mobil — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-2xl font-black">✏️ Edit Mobil</h1>
        <p class="text-gray-300 mt-1">{{ $car->title }}</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('seller.cars.update', $car) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="space-y-4 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Judul Iklan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $car->title) }}" required
                           class="w-full border rounded-lg px-4 py-3">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Series BMW</label>
                        <select name="car_series_id" required class="w-full border rounded-lg px-4 py-3">
                            @foreach($series as $s)
                                <option value="{{ $s->id }}" @selected($car->car_series_id == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Tahun</label>
                        <input type="number" name="year" value="{{ old('year', $car->year) }}" required
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Deskripsi</label>
                    <textarea name="description" rows="4" required
                              class="w-full border rounded-lg px-4 py-3">{{ old('description', $car->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $car->price) }}" required
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Kilometer</label>
                        <input type="number" name="mileage" value="{{ old('mileage', $car->mileage) }}" required
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">
                    💾 Simpan Perubahan
                </button>
                <a href="{{ route('seller.cars.index') }}"
                   class="px-8 py-4 border rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection