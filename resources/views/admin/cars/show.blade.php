@extends('layouts.admin')

@section('title', $car->title)
@section('page-title', 'Detail Mobil')
@section('page-subtitle', $car->title)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.cars.index') }}" class="text-sm text-gray-600 hover:text-blue-600">
        ← Kembali ke Kelola Mobil
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4">
        <img src="{{ asset('storage/centang.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- KOLOM KIRI: Info Mobil --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Informasi --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-lg mb-4">Informasi Mobil</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Series</p>
                    <p class="font-medium">{{ $car->series->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tahun</p>
                    <p class="font-medium">{{ $car->year }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Harga</p>
                    <p class="font-medium text-blue-600">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Kilometer</p>
                    <p class="font-medium">{{ number_format($car->mileage, 0, ',', '.') }} KM</p>
                </div>
                <div>
                    <p class="text-gray-500">Transmisi</p>
                    <p class="font-medium">{{ $car->transmission }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Bahan Bakar</p>
                    <p class="font-medium">{{ $car->fuel_type }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Warna</p>
                    <p class="font-medium">{{ $car->exterior_color }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Kondisi</p>
                    <p class="font-medium">{{ ucfirst($car->condition) }}</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t">
                <p class="text-gray-500 text-sm">Deskripsi</p>
                <p class="mt-1 text-sm">{{ $car->description }}</p>
            </div>

            <div class="mt-4 pt-4 border-t flex items-center gap-3 text-sm">
                <span class="text-gray-500">Status:</span>
                <span class="px-3 py-1 rounded-full
                    {{ $car->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $car->status === 'sold' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $car->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ strtoupper($car->status) }}
                </span>
            </div>
        </div>

        {{-- ============ FOTO MOBIL ============ --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg"><img src="{{ asset('storage/foto.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Foto Mobil ({{ $car->images->count() }})</h2>
            </div>

            {{-- FORM UPLOAD --}}
            <form method="POST" action="{{ route('admin.cars.images.store', $car) }}"
                  enctype="multipart/form-data"
                  class="bg-blue-50 border-2 border-dashed border-blue-300 rounded-xl p-6 mb-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">
                            Pilih Foto (bisa multiple)
                        </label>
                        <input type="file" name="images[]" multiple accept="image/*" required
                               class="w-full border rounded-lg px-4 py-2 bg-white">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP. Max 5MB per foto.</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">
                            Kategori
                        </label>
                        <select name="category" class="w-full border rounded-lg px-4 py-2 bg-white">
                            <option value="exterior"> Exterior</option>
                            <option value="interior"> Interior</option>
                            <option value="dashboard"> Dashboard</option>
                            <option value="engine"> Mesin</option>
                            <option value="wheel"> Velg</option>
                            <option value="tire"> Ban</option>
                            <option value="trunk"> Bagasi</option>
                            <option value="other"> Lainnya</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                    ⬆️ Upload Foto
                </button>
            </form>

            {{-- GALERI FOTO --}}
            @if($car->images->count())
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($car->images as $img)
                        <div class="relative group bg-gray-100 rounded-lg overflow-hidden">
                            @php
                                $src = str_starts_with($img->path, 'http') 
                                        ? $img->path 
                                        : asset('storage/' . $img->path);
                            @endphp
                            <img src="{{ $src }}"
                                 class="w-full aspect-video object-cover"
                                 onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22225%22><rect fill=%22%23e5e7eb%22 width=%22400%22 height=%22225%22/><text x=%2250%25%22 y=%2250%25%22 font-size=%2240%22 text-anchor=%22middle%22 dy=%22.3em%22>🚗</text></svg>';">

                            {{-- Badge primary --}}
                            @if($img->is_primary)
                                <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                    ⭐ UTAMA
                                </span>
                            @endif

                            {{-- Badge kategori --}}
                            <span class="absolute top-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                {{ $img->category }}
                            </span>

                            {{-- Hover actions --}}
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                @if(!$img->is_primary)
                                    <form method="POST" action="{{ route('admin.cars.images.primary', $img) }}">
                                        @csrf @method('PATCH')
                                        <button title="Jadikan utama"
                                                class="bg-yellow-500 text-white w-10 h-10 rounded-full hover:bg-yellow-600">
                                            ⭐
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.cars.images.destroy', $img) }}"
                                      onsubmit="return confirm('Yakin hapus foto ini?')">
                                    @csrf @method('DELETE')
                                    <button title="Hapus"
                                            class="bg-red-500 text-white w-10 h-10 rounded-full hover:bg-red-600">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <p class="text-5xl mb-3"><img src="{{ asset('storage/foto.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></p>
                    <p>Belum ada foto. Upload foto pertama di atas.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- KOLOM KANAN: Aksi --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Aksi Mobil --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold mb-4"> Aksi</h3>

            <div class="space-y-2">
                @if($car->status === 'pending')
                    <form method="POST" action="{{ route('admin.cars.approve', $car) }}">
                        @csrf @method('PATCH')
                        <button class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700">
                            ✓ Approve Listing
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.cars.reject', $car) }}">
                        @csrf @method('PATCH')
                        <button class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-medium hover:bg-red-200">
                            ✗ Reject Listing
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.cars.feature', $car) }}">
                    @csrf @method('PATCH')
                    <button class="w-full border-2 border-yellow-500 text-yellow-600 py-3 rounded-lg font-medium hover:bg-yellow-50">
                        {{ $car->is_featured ? '⭐ Hapus dari Featured' : '☆ Jadikan Featured' }}
                    </button>
                </form>

                <a href="{{ route('cars.show', $car->slug) }}" target="_blank"
                class="flex items-center justify-center gap-2 border py-3 rounded-lg font-medium hover:bg-gray-50">
                    <img src="{{ asset('storage/aye.png') }}" 
                        alt="View" 
                        class="w-4 h-4 object-contain">
                    Lihat di Publik
                </a>
            </div>
        </div>

        {{-- Info Seller --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold mb-4"><img src="{{ asset('storage/seller.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Seller</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center font-bold text-purple-600">
                    {{ strtoupper(substr($car->seller->store_name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium">{{ $car->seller->store_name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $car->seller->city ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection