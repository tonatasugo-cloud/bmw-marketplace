@extends('layouts.app')

@section('title', 'Nicolas Select — BMW Pre-Owned Premium')

@section('content')

{{-- ========== HERO SECTION (VIDEO) ========== --}}
<section class="relative overflow-hidden bg-[#0A1628] text-white">

    {{-- Background Video + Content --}}
    <div class="relative min-h-[600px] md:min-h-[700px] lg:min-h-[800px] flex items-center justify-center">

        {{-- Video Background --}}
        <video
            autoplay muted loop playsinline preload="auto"
            class="absolute inset-0 w-full h-full object-cover object-center z-0">
            <source src="{{ asset('storage/bmwM8.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung video HTML5.
        </video>

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#0A1628]/70 via-[#0A1628]/60 to-[#0A1628]/95 z-10"></div>
        <div class="absolute inset-0 bg-blue-900/10 z-10"></div>

        {{-- ========== HERO CONTENT (Animasi Fade-In) ========== --}}
        <div class="relative z-20 max-w-7xl mx-auto px-6 py-20 text-center w-full">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full mb-8 animate-fade-in-up">
                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                <span class="text-xs font-medium tracking-widest uppercase text-white/90">
                    Premium BMW Pre-Owned
                </span>
            </div>

            {{-- Heading --}}
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-semibold leading-[1.05] tracking-tight mb-6 animate-fade-in-up delay-200">
                Temukan BMW<br>
                <span class="bg-gradient-to-r from-blue-300 via-blue-400 to-blue-500 bg-clip-text text-transparent">
                    Impian Anda
                </span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-base md:text-lg lg:text-xl text-white/70 max-w-2xl mx-auto mb-8 font-light leading-relaxed animate-fade-in-up delay-300">
                Ratusan unit BMW berkualitas, tersertifikasi, dan bergaransi.
                Dari Seri 1 hingga M Series.
            </p>

            {{-- Trust Badges --}}
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm text-white/70 animate-fade-in-up delay-500">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Certified Pre-Owned
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Garansi Mesin
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Inspeksi 175 Titik
                </span>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 animate-bounce">
            <div class="w-6 h-10 border-2 border-white/40 rounded-full flex items-start justify-center p-1.5">
                <div class="w-1 h-2 bg-white/60 rounded-full"></div>
            </div>
        </div>
    </div>

    {{-- ========== SEARCH BAR (DI BAWAH VIDEO) ========== --}}
    <div class="relative z-30 bg-gradient-to-b from-[#0A1628] to-[#0F1F35] py-10 md:py-12">
        <div class="max-w-5xl mx-auto px-6">

            <p class="text-center text-white/50 text-xs uppercase tracking-widest mb-4">
                Mulai Pencarian Anda
            </p>

            <form action="{{ route('cars.index') }}" method="GET" class="w-full">
                <div class="relative flex items-center bg-white rounded-full shadow-2xl shadow-black/40 overflow-hidden border border-white/40">

                    {{-- Icon Search Kiri --}}
                    <div class="pl-6 pr-3">
                        <img src="{{ asset('storage/search.png') }}"
                             alt="search"
                             class="w-5 h-5 opacity-50">
                    </div>

                    {{-- Input --}}
                    <input type="text" name="q"
                           placeholder="Cari BMW 530i, X5, M4..."
                           class="flex-1 py-4 pr-4 bg-transparent text-gray-800 placeholder-gray-400 outline-none text-base font-medium">

                    {{-- Fade Kanan --}}
                    <div class="absolute inset-y-0 right-24 w-32 bg-gradient-to-l from-white via-white/80 to-transparent pointer-events-none"></div>

                    {{-- Tombol Cari --}}
                    <button type="submit"
                            class="relative bg-transparent text-blue-600 hover:text-blue-700 px-6 py-4 transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </button>
                </div>

                {{-- Quick Tags --}}
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs">
                    <span class="text-white/40">Populer:</span>
                    <a href="{{ route('cars.index', ['series' => 'm-series']) }}"
                       class="px-3 py-1 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white/80 transition">
                        M Series
                    </a>
                    <a href="{{ route('cars.index', ['series' => '5-series']) }}"
                       class="px-3 py-1 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white/80 transition">
                        5 Series
                    </a>
                    <a href="{{ route('cars.index', ['series' => 'x5']) }}"
                       class="px-3 py-1 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white/80 transition">
                        X5
                    </a>
                    <a href="{{ route('cars.index', ['certified' => 1]) }}"
                       class="px-3 py-1 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white/80 transition">
                        Certified
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ========== FEATURED CARS ========== --}}
@if(isset($featured) && $featured->count())
<section class="max-w-7xl mx-auto px-4 py-16 reveal">
    <x-section-title
        title="BMW Pilihan"
        subtitle="Unit unggulan dengan kualitas terbaik"
        :link="route('cars.index')"
    />
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featured as $car)
            <x-car-card :car="$car" />
        @endforeach
    </div>
</section>
@endif

{{-- ========== BMW SERIES ========== --}}
@if(isset($series) && $series->count())
<section class="bg-white py-16 reveal">
    <div class="max-w-7xl mx-auto px-4">
        <x-section-title title="Jelajahi BMW Series" subtitle="Temukan seri yang sesuai dengan gaya Anda" />
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($series as $s)
                <a href="{{ route('cars.index', ['series' => $s->slug]) }}"
                   class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 text-center hover:from-blue-50 hover:to-blue-100 transition group">
                    <p class="text-2xl font-black text-gray-900 group-hover:text-blue-600">{{ $s->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $s->category }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ========== NEW ARRIVALS ========== --}}
@if(isset($newArrivals) && $newArrivals->count())
<section class="max-w-7xl mx-auto px-4 py-16 reveal">
    <x-section-title
        title="Baru Masuk"
        subtitle="BMW terbaru yang baru dipublikasikan"
        :link="route('cars.index')"
    />
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($newArrivals as $car)
            <x-car-card :car="$car" />
        @endforeach
    </div>
</section>
@endif

{{-- ========== CTA ========== --}}
<section class="bg-blue-600 text-white py-16 reveal">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-black mb-4">Punya BMW untuk Dijual?</h2>
        <p class="text-blue-100 mb-8 text-lg">Daftarkan showroom Anda dan jangkau ribuan pembeli potensial.</p>
        <a href="{{ route('register') }}"
           class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100">
            Mulai Jual Sekarang →
        </a>
    </div>
</section>

@endsection