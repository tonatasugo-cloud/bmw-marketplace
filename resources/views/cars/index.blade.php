@extends('layouts.app')

@section('title', 'Cari BMW Pre-Owned — Bavaria Select')

@section('content')

{{-- ========== HEADER DENGAN FOTO MOBIL ========== --}}
{{-- ========== HEADER DENGAN VIDEO MOBIL ========== --}}
<div class="relative overflow-hidden bg-[#0A1628] text-white min-h-[280px] md:min-h-[320px] flex items-center">

    {{-- Background Video Mobil --}}
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline preload="auto"
               class="w-full h-full object-cover object-center">
            <source src="{{ asset('storage/bmwM8.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung video HTML5.
        </video>
        
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-r from-[#0A1628]/95 via-[#0A1628]/70 to-[#0A1628]/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0A1628] via-transparent to-transparent"></div>
    </div>

    {{-- Konten Header --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 w-full">
        <p class="text-blue-400 text-xs font-semibold tracking-widest uppercase mb-3">
            Katalog BMW
        </p>
        <h1 class="text-4xl md:text-5xl font-semibold mb-3">
            Cari BMW <span class="text-blue-400">Impian Anda</span>
        </h1>
        <p class="text-gray-300 text-base md:text-lg">
            {{ $cars->total() }} unit ditemukan
            @if(request('q')) untuk "<span class="text-white font-medium">{{ request('q') }}</span>" @endif
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- SIDEBAR FILTER --}}
        <aside class="lg:w-72 flex-shrink-0">
            <form method="GET" action="{{ route('cars.index') }}"
                  class="bg-white rounded-xl shadow p-6 sticky top-24">
                <h3 class="font-bold text-lg mb-4"> Filter</h3>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kata Kunci</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="BMW 530i, X5..."
                           class="w-full border rounded-lg text-sm px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Series</label>
                    <select name="series" class="w-full border rounded-lg text-sm px-3 py-2">
                        <option value="">Semua Series</option>
                        @foreach($series as $s)
                            <option value="{{ $s->slug }}" @selected(request('series') == $s->slug)>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Rentang Harga</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                               placeholder="Min" class="w-full border rounded-lg text-sm px-3 py-2">
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                               placeholder="Max" class="w-full border rounded-lg text-sm px-3 py-2">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Tahun</label>
                    <div class="flex gap-2">
                        <input type="number" name="year_from" value="{{ request('year_from') }}"
                               placeholder="Dari" class="w-full border rounded-lg text-sm px-3 py-2">
                        <input type="number" name="year_to" value="{{ request('year_to') }}"
                               placeholder="Sampai" class="w-full border rounded-lg text-sm px-3 py-2">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Maks. KM</label>
                    <input type="number" name="max_mileage" value="{{ request('max_mileage') }}"
                           placeholder="cth: 50000"
                           class="w-full border rounded-lg text-sm px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Transmisi</label>
                    <select name="transmission" class="w-full border rounded-lg text-sm px-3 py-2">
                        <option value="">Semua</option>
                        <option value="Automatic" @selected(request('transmission') == 'Automatic')>Automatic</option>
                        <option value="Manual" @selected(request('transmission') == 'Manual')>Manual</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Bahan Bakar</label>
                    <select name="fuel_type" class="w-full border rounded-lg text-sm px-3 py-2">
                        <option value="">Semua</option>
                        <option value="Petrol" @selected(request('fuel_type') == 'Petrol')>Petrol</option>
                        <option value="Diesel" @selected(request('fuel_type') == 'Diesel')>Diesel</option>
                        <option value="Hybrid" @selected(request('fuel_type') == 'Hybrid')>Hybrid</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="certified" value="1" @checked(request('certified'))>
                        <span class="font-medium">Hanya Certified</span>
                    </label>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700">
                        Terapkan
                    </button>
                    <a href="{{ route('cars.index') }}"
                       class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                        Reset
                    </a>
                </div>
            </form>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-600">
                    Menampilkan <b>{{ $cars->firstItem() ?? 0 }}</b>–<b>{{ $cars->lastItem() ?? 0 }}</b>
                    dari <b>{{ $cars->total() }}</b> mobil
                </p>

                <form method="GET" action="{{ route('cars.index') }}">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" onchange="this.form.submit()"
                            class="border rounded-lg text-sm px-3 py-2">
                        <option value="">Terbaru</option>
                        <option value="price_asc" @selected(request('sort') == 'price_asc')>Harga Terendah</option>
                        <option value="price_desc" @selected(request('sort') == 'price_desc')>Harga Tertinggi</option>
                        <option value="year_desc" @selected(request('sort') == 'year_desc')>Tahun Terbaru</option>
                        <option value="mileage" @selected(request('sort') == 'mileage')>KM Terendah</option>
                    </select>
                </form>
            </div>

            @if($cars->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($cars as $car)
                        <x-car-card :car="$car" />
                    @endforeach
                </div>
                <div class="mt-8">{{ $cars->links() }}</div>
            @else
                <div class="bg-white rounded-xl shadow p-12 text-center">
                    <p class="text-5xl mb-4">🔍</p>
                    <h3 class="text-xl font-bold mb-2">BMW Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-6">Coba ubah filter pencarian Anda.</p>
                    <a href="{{ route('cars.index') }}"
                       class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                        Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection