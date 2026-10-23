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

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-8 mb-6">
        <form method="POST" action="{{ route('seller.cars.update', $car) }}">
            @csrf @method('PUT')

            <div class="space-y-4 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Judul Iklan</label>
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

            <div class="pt-6 border-t">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ============ FOTO MOBIL ============ --}}
    <div class="bg-white rounded-xl shadow p-8">
        <h2 class="font-bold text-lg mb-4">📸 Foto Mobil ({{ $car->images->count() }})</h2>

        {{-- FORM UPLOAD --}}
        <form method="POST" action="{{ route('seller.cars.images.store', $car) }}"
              enctype="multipart/form-data"
              class="bg-blue-50 border-2 border-dashed border-blue-300 rounded-xl p-6 mb-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Pilih Foto</label>
                    <input type="file" name="images[]" multiple accept="image/*" required
                           class="w-full border rounded-lg px-4 py-2 bg-white">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kategori</label>
                    <select name="category" class="w-full border rounded-lg px-4 py-2 bg-white">
                        <option value="exterior">🌞 Exterior</option>
                        <option value="interior">🪑 Interior</option>
                        <option value="dashboard">🎛️ Dashboard</option>
                        <option value="engine">🔧 Mesin</option>
                        <option value="wheel">⚙️ Velg</option>
                        <option value="other">📷 Lainnya</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                ⬆️ Upload Foto
            </button>
        </form>

        {{-- GALERI --}}
        @if($car->images->count())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($car->images as $img)
                    <div class="relative group bg-gray-100 rounded-lg overflow-hidden">
                        @php
                            $src = str_starts_with($img->path, 'http') 
                                    ? $img->path 
                                    : asset('storage/' . $img->path);
                        @endphp
                        <img src="{{ $src }}" class="w-full aspect-video object-cover"
                             onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22225%22><rect fill=%22%23e5e7eb%22 width=%22400%22 height=%22225%22/><text x=%2250%25%22 y=%2250%25%22 font-size=%2240%22 text-anchor=%22middle%22 dy=%22.3em%22>🚗</text></svg>';">

                        @if($img->is_primary)
                            <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                ⭐ UTAMA
                            </span>
                        @endif

                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                            @if(!$img->is_primary)
                                <form method="POST" action="{{ route('seller.cars.images.primary', $img) }}">
                                    @csrf @method('PATCH')
                                    <button class="bg-yellow-500 text-white w-10 h-10 rounded-full">⭐</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('seller.cars.images.destroy', $img) }}"
                                  onsubmit="return confirm('Hapus foto?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white w-10 h-10 rounded-full">🗑️</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <p class="text-5xl mb-3">📸</p>
                <p>Belum ada foto</p>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('seller.cars.index') }}"
           class="inline-block px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-100">
            ← Kembali ke Daftar Mobil
        </a>
    </div>
</div>

@endsection