@extends('layouts.app')

@section('title', 'Dashboard Seller — Bavaria Select')

@section('content')

{{-- HEADER --}}
<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-blue-400 text-sm font-semibold tracking-wide">SELLER DASHBOARD</p>
                <h1 class="text-3xl font-black mt-1">{{ $seller->store_name }}</h1>
                <p class="text-gray-300 mt-1">📍 {{ $seller->city }}, {{ $seller->province }}</p>
            </div>
            <div class="text-right">
                @if($seller->status === 'verified')
                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">✓ VERIFIED</span>
                @else
                    <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full">{{ strtoupper($seller->status) }}</span>
                @endif
                <p class="text-yellow-400 mt-2">⭐ {{ number_format($seller->rating_avg, 1) }} ({{ $seller->rating_count }})</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- QUICK STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Mobil Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_listings'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Views</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_views']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Penawaran</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pending_offers'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500">Test Drive</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pending_test_drives'] }}</p>
        </div>
    </div>

    {{-- MENU CEPAT --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('seller.cars.create') }}"
           class="bg-blue-600 text-white rounded-xl p-5 hover:bg-blue-700 transition flex items-center gap-3">
            <span class="text-3xl">➕</span>
            <div>
                <p class="font-bold">Tambah Mobil</p>
                <p class="text-xs text-blue-100">Listing baru</p>
            </div>
        </a>
        <a href="{{ route('seller.cars.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition flex items-center gap-3">
            <span class="text-3xl">🚗</span>
            <div>
                <p class="font-bold">Mobil Saya</p>
                <p class="text-xs text-gray-500">Kelola listing</p>
            </div>
        </a>
        <a href="{{ route('seller.offers.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition flex items-center gap-3 relative">
            <span class="text-3xl">💰</span>
            <div>
                <p class="font-bold">Penawaran</p>
                <p class="text-xs text-gray-500">Respon offer</p>
            </div>
            @if($stats['pending_offers'] > 0)
                <span class="absolute top-3 right-3 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                    {{ $stats['pending_offers'] }}
                </span>
            @endif
        </a>
        <a href="{{ route('seller.test-drives.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition flex items-center gap-3 relative">
            <span class="text-3xl">📅</span>
            <div>
                <p class="font-bold">Test Drive</p>
                <p class="text-xs text-gray-500">Kelola jadwal</p>
            </div>
            @if($stats['pending_test_drives'] > 0)
                <span class="absolute top-3 right-3 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                    {{ $stats['pending_test_drives'] }}
                </span>
            @endif
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- MOBIL TERBARU --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h2 class="font-bold text-lg">🚗 Mobil Terbaru</h2>
                <a href="{{ route('seller.cars.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua →</a>
            </div>
            @if($recentCars->count())
                <div class="divide-y">
                    @foreach($recentCars as $car)
                        <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                🚗
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium truncate">{{ $car->title }}</p>
                                <p class="text-xs text-gray-500">{{ $car->year }} · {{ number_format($car->mileage, 0, ',', '.') }} KM</p>
                                <p class="text-sm font-bold text-blue-600 mt-1">
                                    Rp {{ number_format($car->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @php
                                    $badge = match($car->status) {
                                        'available' => 'bg-green-100 text-green-700',
                                        'reserved'  => 'bg-yellow-100 text-yellow-700',
                                        'sold'      => 'bg-blue-100 text-blue-700',
                                        'pending'   => 'bg-gray-100 text-gray-700',
                                        default     => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-1 rounded-full {{ $badge }}">
                                    {{ strtoupper($car->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">👁️ {{ $car->view_count }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-5xl mb-3">🚗</p>
                    <p class="text-gray-500 mb-4">Belum ada mobil</p>
                    <a href="{{ route('seller.cars.create') }}"
                       class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700">
                        Tambah Mobil Pertama
                    </a>
                </div>
            @endif
        </div>

        {{-- ORDER TERBARU --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h2 class="font-bold text-lg">📦 Pesanan Terbaru</h2>
                <a href="{{ route('seller.orders.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua →</a>
            </div>
            @if($recentOrders->count())
                <div class="divide-y">
                    @foreach($recentOrders as $order)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="font-mono text-xs text-gray-500">{{ $order->order_number }}</p>
                                <p class="font-medium">{{ $order->car->title ?? '-' }}</p>
                                <p class="text-xs text-gray-500">oleh {{ $order->user->name ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">
                                    Rp {{ number_format($order->car_price, 0, ',', '.') }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-5xl mb-3">📦</p>
                    <p class="text-gray-500">Belum ada pesanan</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection