<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — Nicolas Select')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

    {{-- ========== SIDEBAR ========== --}}
    <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden lg:block">
        <div class="p-6 border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-black">
                NICOLAS<span class="text-blue-400">SELECT</span>
            </a>
            <p class="text-xs text-gray-500 mt-1">Admin Panel</p>
        </div>

        <nav class="p-4 space-y-1">
            @php
                $menu = [
                    ['route' => 'admin.dashboard', 'icon' => 'dashboard.png', 'label' => 'Dashboard'],
                    ['route' => 'admin.users.index', 'icon' => 'user.png', 'label' => 'Users'],
                    ['route' => 'admin.sellers.index', 'icon' => 'store2.png', 'label' => 'Sellers'],
                    ['route' => 'admin.cars.index', 'icon' => 'car2.png', 'label' => 'Cars'],
                    ['route' => 'admin.orders.index', 'icon' => 'order.png', 'label' => 'Orders'],
                    ['route' => 'admin.reports.index', 'icon' => 'report.png', 'label' => 'Reports'],
                ];
            @endphp

            @foreach($menu as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '*');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition
                          {{ $isActive ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <img src="{{ asset('storage/' . $item['icon']) }}" 
                        alt="{{ $item['label'] }}" 
                        class="w-5 h-5 object-contain">
                    <span class="font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach

            <div class="pt-4 mt-4 border-t border-gray-800">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white">
                    <span class="text-lg"> <img src="{{ asset('storage/home.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></span>
                    <span class="font-medium">Kembali ke Situs</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-red-400 hover:bg-red-900/20 hover:text-red-300">
                        <span class="text-lg"><img src="{{ asset('storage/logout.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></span>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- ========== MAIN CONTENT ========== --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOPBAR --}}
        <header class="bg-white border-b px-6 py-4 flex items-center justify-between sticky top-0 z-40">
            <div>
                <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500">@yield('page-subtitle', 'Selamat datang di Admin Panel')</p>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">{{ now()->format('d M Y') }}</span>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-sm">
                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-6 mt-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>