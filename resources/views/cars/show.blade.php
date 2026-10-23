@extends('layouts.app')

@section('title', $car->title . ' — Nicolas Select')

@section('content')

{{-- BREADCRUMB --}}
<div class="bg-gray-100 border-b">
    <div class="max-w-7xl mx-auto px-4 py-3 text-sm">
        <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">Home</a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('cars.index') }}" class="text-gray-600 hover:text-blue-600">Mobil</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-900 font-medium">{{ $car->title }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ============ KOLOM KIRI ============ --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- GALERI FOTO --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                @if($car->images->count())
                    @php
                        $primary = $car->images->firstWhere('is_primary', true) ?? $car->images->first();
                        $primarySrc = \Illuminate\Support\Str::startsWith($primary->path, 'http')
                                        ? $primary->path
                                        : asset('storage/' . $primary->path);
                    @endphp

                    <div class="aspect-[16/10] bg-gray-100">
                        <img src="{{ $primarySrc }}"
                             alt="{{ $car->title }}"
                             class="w-full h-full object-cover"
                             id="mainImage"
                             onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22600%22><rect fill=%22%23e5e7eb%22 width=%22800%22 height=%22600%22/><text x=%2250%25%22 y=%2250%25%22 font-size=%22120%22 text-anchor=%22middle%22 dy=%22.3em%22>🚗</text></svg>';">
                    </div>

                    @if($car->images->count() > 1)
                        <div class="p-4 grid grid-cols-5 gap-2">
                            @foreach($car->images as $img)
                                @php
                                    $thumbSrc = \Illuminate\Support\Str::startsWith($img->path, 'http')
                                                    ? $img->path
                                                    : asset('storage/' . $img->path);
                                @endphp
                                <button type="button"
                                        onclick="document.getElementById('mainImage').src='{{ $thumbSrc }}'"
                                        class="aspect-square bg-gray-100 rounded overflow-hidden hover:ring-2 hover:ring-blue-500">
                                    <img src="{{ $thumbSrc }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.parentElement.style.display='none';">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-[16/10] bg-gray-100 flex items-center justify-center text-6xl text-gray-400">
                        🚗
                    </div>
                @endif
            </div>

            {{-- JUDUL + HARGA + SPESIFIKASI --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-sm text-blue-600 font-semibold uppercase tracking-wide">
                            {{ $car->series->name ?? 'BMW' }}
                        </p>
                        <h1 class="text-2xl md:text-3xl font-black mt-2 text-gray-900">
                            {{ $car->title }}
                        </h1>
                        <p class="text-gray-500 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $car->location_city }}, {{ $car->location_province }}
                        </p>
                    </div>
                    @if($car->is_certified)
                        <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-full whitespace-nowrap">
                            ✓ CERTIFIED
                        </span>
                    @endif
                </div>

                {{-- HARGA --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Harga</p>
                    <p class="text-3xl md:text-4xl font-black text-blue-600">
                        Rp {{ number_format($car->price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- SPESIFIKASI GRID --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-4">Spesifikasi</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tahun</p>
                            <p class="font-bold text-gray-900">{{ $car->year }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kilometer</p>
                            <p class="font-bold text-gray-900">{{ number_format($car->mileage, 0, ',', '.') }} KM</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Transmisi</p>
                            <p class="font-bold text-gray-900">{{ $car->transmission }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Bahan Bakar</p>
                            <p class="font-bold text-gray-900">{{ $car->fuel_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Mesin</p>
                            <p class="font-bold text-gray-900">{{ $car->engine_capacity }}L</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Drivetrain</p>
                            <p class="font-bold text-gray-900">{{ $car->drivetrain }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Warna</p>
                            <p class="font-bold text-gray-900">{{ $car->exterior_color }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kondisi</p>
                            <p class="font-bold text-gray-900">{{ ucfirst($car->condition) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DESKRIPSI --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Deskripsi</h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $car->description }}</p>
            </div>

            {{-- INSPEKSI --}}
            @if($car->inspections->count())
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">✓ Hasil Inspeksi</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($car->inspections as $insp)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <p class="text-xs text-gray-500 uppercase">{{ $insp->component }}</p>
                                <p class="font-bold text-sm mt-1
                                    @if($insp->condition === 'good') text-green-600
                                    @elseif($insp->condition === 'fair') text-yellow-600
                                    @else text-red-600 @endif">
                                    ✓ {{ strtoupper($insp->condition) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ============ KOLOM KANAN ============ --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- SELLER CARD --}}
            <div class="bg-white rounded-xl shadow p-6 sticky top-24">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center text-xl font-bold text-blue-600 flex-shrink-0">
                        {{ strtoupper(substr($car->seller->store_name ?? 'B', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold truncate">{{ $car->seller->store_name ?? 'BMW Dealer' }}</p>
                        @if($car->seller && $car->seller->status === 'verified')
                            <p class="text-xs text-blue-600">✓ Verified Seller</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 text-sm mb-4">
                    <span class="text-yellow-500">⭐ {{ number_format($car->seller->rating_avg ?? 0, 1) }}</span>
                    <span class="text-gray-500">({{ $car->seller->rating_count ?? 0 }})</span>
                </div>

                <div class="space-y-2">
                    @auth
                        <form method="POST" action="{{ route('chat.start', $car) }}">
                            @csrf
                            <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                                💬 Chat Seller
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="block text-center w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                            💬 Chat Seller
                        </a>
                    @endauth

                    @if($car->seller)
                        <a href="{{ route('sellers.show', $car->seller->slug) }}"
                           class="block text-center w-full border border-gray-300 py-3 rounded-lg font-medium hover:bg-gray-50 transition">
                            Lihat Profil Seller
                        </a>
                    @endif
                </div>
            </div>

            {{-- RESERVE BUTTON --}}
            @auth
                @if($car->status === 'available')
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        <input type="hidden" name="delivery_method" value="pickup">
                        <button class="w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition">
                            🔒 RESERVE THIS CAR
                        </button>
                    </form>
                @elseif($car->status === 'reserved')
                    <div class="bg-yellow-100 border border-yellow-300 rounded-xl p-6 text-center">
                        <p class="font-bold text-yellow-800">⚠️ Sudah Direservasi</p>
                        <p class="text-xs text-yellow-700 mt-1">Mobil ini sedang dalam proses transaksi</p>
                    </div>
                @elseif($car->status === 'sold')
                    <div class="bg-red-100 border border-red-300 rounded-xl p-6 text-center">
                        <p class="font-bold text-red-800">❌ Sudah Terjual</p>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    {{-- MOBIL TERKAIT --}}
    @if(isset($related) && $related->count())
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">BMW Serupa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $r)
                    <x-car-card :car="$r" />
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection