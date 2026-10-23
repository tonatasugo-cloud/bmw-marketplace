@extends('layouts.app')

@section('title', 'Tambah Mobil — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-2xl font-black">➕ Tambah Mobil Baru</h1>
        <p class="text-gray-300 mt-1">Lengkapi data mobil untuk listing</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('seller.cars.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- INFO DASAR --}}
            <h2 class="text-lg font-bold border-b pb-2 mb-4">📋 Info Dasar</h2>

            <div class="space-y-4 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Judul Iklan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: BMW 530i M Sport"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Series BMW <span class="text-red-500">*</span></label>
                        <select name="car_series_id" required
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Series</option>
                            @foreach($series as $s)
                                <option value="{{ $s->id }}" @selected(old('car_series_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" required
                               min="1990" max="{{ date('Y')+1 }}"
                               class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required
                              placeholder="Deskripsi lengkap kondisi mobil..."
                              class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- HARGA & KM --}}
            <h2 class="text-lg font-bold border-b pb-2 mb-4">💰 Harga & Kondisi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" required
                           min="10000000" step="1000000"
                           placeholder="Contoh: 875000000"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kilometer <span class="text-red-500">*</span></label>
                    <input type="number" name="mileage" value="{{ old('mileage') }}" required
                           min="0" placeholder="Contoh: 35000"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kondisi <span class="text-red-500">*</span></label>
                    <select name="condition" required
                            class="w-full border rounded-lg px-4 py-3">
                        <option value="excellent" @selected(old('condition') == 'excellent')>Excellent</option>
                        <option value="good" @selected(old('condition') == 'good')>Good</option>
                        <option value="fair" @selected(old('condition') == 'fair')>Fair</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm mt-6">
                        <input type="checkbox" name="is_certified" value="1" @checked(old('is_certified'))>
                        <span class="font-medium">✓ Certified Pre-Owned</span>
                    </label>
                </div>
            </div>

            {{-- SPESIFIKASI --}}
            <h2 class="text-lg font-bold border-b pb-2 mb-4">⚙️ Spesifikasi</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Transmisi <span class="text-red-500">*</span></label>
                    <select name="transmission" required class="w-full border rounded-lg px-4 py-3">
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Bahan Bakar <span class="text-red-500">*</span></label>
                    <select name="fuel_type" required class="w-full border rounded-lg px-4 py-3">
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Electric">Electric</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Drivetrain <span class="text-red-500">*</span></label>
                    <select name="drivetrain" required class="w-full border rounded-lg px-4 py-3">
                        <option value="RWD">RWD</option>
                        <option value="AWD">AWD</option>
                        <option value="FWD">FWD</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kapasitas Mesin (L) <span class="text-red-500">*</span></label>
                    <input type="number" name="engine_capacity" value="{{ old('engine_capacity', 2.0) }}"
                           step="0.1" required class="w-full border rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Body Type <span class="text-red-500">*</span></label>
                    <input type="text" name="body_type" value="{{ old('body_type', 'Sedan') }}" required
                           class="w-full border rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Warna Eksterior <span class="text-red-500">*</span></label>
                    <input type="text" name="exterior_color" value="{{ old('exterior_color') }}" required
                           placeholder="Contoh: Alpine White"
                           class="w-full border rounded-lg px-4 py-3">
                </div>
            </div>

            {{-- LOKASI --}}
            <h2 class="text-lg font-bold border-b pb-2 mb-4">📍 Lokasi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kota <span class="text-red-500">*</span></label>
                    <input type="text" name="location_city" value="{{ old('location_city') }}" required
                           class="w-full border rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" name="location_province" value="{{ old('location_province') }}" required
                           class="w-full border rounded-lg px-4 py-3">
                </div>
            </div>

            {{-- UPLOAD FOTO --}}
            <h2 class="text-lg font-bold border-b pb-2 mb-4">📸 Foto Mobil</h2>

            <div class="mb-8">
                <label class="text-sm font-medium text-gray-700 block mb-1">
                    Upload Foto (boleh pilih banyak)
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full border rounded-lg px-4 py-3">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max 5MB per foto. Foto pertama = foto utama.</p>
            </div>

            {{-- SUBMIT --}}
            <div class="pt-6 border-t flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">
                    📤 Publikasikan Mobil
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