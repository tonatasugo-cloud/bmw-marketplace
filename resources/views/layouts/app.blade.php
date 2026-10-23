<!DOCTYPE html>
<html lang="id">
<head>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nicolas Select — BMW Pre-Owned Premium')</title>

    {{-- ✅ GOOGLE FONTS — Montserrat + Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
</head>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-zoom');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); // animasi sekali saja
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -100px 0px'  // trigger sebelum masuk viewport
        });

        reveals.forEach((el) => observer.observe(el));

        // Fallback
        if (!('IntersectionObserver' in window)) {
            reveals.forEach((el) => el.classList.add('active'));
        }
    });
</script>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col font-sans">

{{-- ========== NAVBAR ========== --}}
<nav class="bg-white border-b sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-2xl font-black tracking-tight">
            NICOLAS<span class="text-blue-600">SELECT</span>
        </a>

        <div class="hidden md:flex gap-6 text-sm font-medium text-gray-700">
            <a href="{{ route('cars.index') }}" class="hover:text-blue-600">Cari Mobil</a>
            <a href="{{ route('cars.index', ['certified' => 1]) }}" class="hover:text-blue-600">Certified</a>
            <a href="#" class="hover:text-blue-600">Jual Mobil</a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('wishlist.index') }}" class="text-xl hover:text-red-500" title="Wishlist">♡</a>
                <a href="{{ route('chat.index') }}" class="text-xl hover:text-blue-600" title="Chat"><img src="{{ asset('storage/chat.png') }}" alt="Dashboard" class="w-8 h-8 object-contain"></a>

                {{-- ====== DROPDOWN (Alpine.js) ====== --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button"
                            class="font-medium text-sm flex items-center gap-1 hover:text-blue-600">
                        {{ auth()->user()->name }}
                        <span class="text-xs">▾</span>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-56 border z-50 py-1"
                         style="display: none;">

                        @if(auth()->user()->hasRole('seller'))
                            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                <img src="{{ asset('storage/homeblack.png') }}" alt="Dashboard" class="w-8 h-8 object-contain">
                                Dashboard Seller
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                <img src="{{ asset('storage/homeblack.png') }}" alt="Dashboard" class="w-8 h-8 object-contain">
                                Dashboard Admin
                            </a>
                        @endif

                        <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                            <img src="{{ asset('storage/box.png') }}" alt="Dashboard" class="w-4 h-4 object-contain">Pesanan Saya
                        </a>
                        <a href="{{ route('offers.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                            <img src="{{ asset('storage/duit.png') }}" alt="Dashboard" class="w-8 h-8 object-contain">
                            Penawaran Saya
                        </a>
                        <a href="{{ route('test-drives.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                            <img src="{{ asset('storage/tgl.png') }}" alt="Dashboard" class="w-8 h-8 object-contain">
                            Test Drive
                        </a>

                        <hr class="my-1">

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                            <img src="{{ asset('storage/userblack.png') }}" alt="Dashboard" class="w-8 h-8 object-contain">
                            Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                <img src="{{ asset('storage/logout2.png') }}" alt="Dashboard" class="w-8 h-8 object-contain">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium hover:text-blue-600">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 font-medium">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ========== FLASH MESSAGES ========== --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
            <img src="{{ asset('storage/centang.png') }}" alt="Dashboard" class="w-8 h-8 object-contain"> {{ session('success') }}
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-lg">
            <img src="{{ asset('storage/warning.png') }}" alt="Dashboard" class="w-8 h-8 object-contain"> {{ session('warning') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ========== MAIN ========== --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ========== FOOTER ========== --}}
<footer class="bg-gray-900 text-white mt-20 py-12">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-8">
        <div>
            <h3 class="font-black text-xl mb-3">NICOLAS<span class="text-blue-500">SELECT</span></h3>
            <p class="text-sm text-gray-400">Marketplace BMW pre-owned terpercaya di Indonesia.</p>
        </div>
        <div>
            <h4 class="font-bold mb-3">Jelajahi</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="{{ route('cars.index') }}" class="hover:text-white">Semua Mobil</a></li>
                <li><a href="{{ route('cars.index', ['certified' => 1]) }}" class="hover:text-white">Certified Pre-Owned</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-3">Bantuan</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li>FAQ</li>
                <li>Hubungi Kami</li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-3">Kontak</h4>
            <p class="text-sm text-gray-400">support@nicolasselect.id</p>
        </div>
    </div>
    <div class="text-center text-xs text-gray-500 mt-10 pt-6 border-t border-gray-800">
        © {{ date('Y') }} Nicolas Select. All rights reserved.
    </div>
</footer>

</body>
</html>