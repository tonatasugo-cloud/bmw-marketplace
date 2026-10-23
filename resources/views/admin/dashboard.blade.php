@extends('layouts.admin')

@section('title', 'Dashboard — Admin Panel')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas marketplace')

@section('content')

{{-- ========== STAT CARDS ========== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['users']) }}</p>
                <p class="text-xs text-green-600 mt-2">↑ Aktif</p>
            </div>
            <div class="text-3xl"><img src="{{ asset('storage/pipel.png') }}" 
                alt="Users" 
                class="w-12 h-12 object-contain">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Sellers</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['sellers']) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $stats['pending_sellers'] }} pending</p>
            </div>
            <div class="text-3xl"><img src="{{ asset('storage/penjualan.png') }}" 
                alt="Users" 
                class="w-12 h-12 object-contain">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Mobil</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['cars']) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $stats['pending_cars'] }} pending</p>
            </div>
            <div class="text-3xl"><img src="{{ asset('storage/car.png') }}" 
                alt="Users" 
                class="w-12 h-12 object-contain">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Revenue</p>
                <p class="text-xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 mt-2">{{ $stats['transactions'] }} transaksi</p>
            </div>
            <div class="text-3xl"><img src="{{ asset('storage/duit.png') }}" 
                alt="Users" 
                class="w-12 h-12 object-contain">
            </div>
        </div>
    </div>
</div>

{{-- ========== CHART + TOP SERIES ========== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Sales Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-lg mb-4">
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <img src="{{ asset('storage/tingkat.png') }}" 
                    alt="Sellers" 
                    class="w-8 h-8 object-contain">
            </div>
            Penjualan Bulanan ({{ date('Y') }})</h3>
        <div class="flex items-end justify-between gap-2 h-48">
            @php
                $maxSale = max($salesChart) ?: 1;
            @endphp
            @foreach($salesChart as $m => $total)
                <div class="flex-1 flex flex-col items-center justify-end h-full">
                    <div class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition relative group"
                         style="height: {{ ($total / $maxSale) * 100 }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap">
                            Rp {{ number_format($total / 1000000, 0) }}jt
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ $m }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Top Series --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-lg mb-4">
            <div class="w-14 h-14 flex items-center justify-center">
                <img src="{{ asset('storage/piala.png') }}" 
                alt="Sellers" 
                class="w-8 h-8 object-contain">
            </div> Series Terpopuler</h3>
        <div class="space-y-3">
            @foreach($topSeries as $s)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium">{{ $s->name }}</span>
                        <span class="text-gray-500">{{ $s->cars_count }} unit</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full"
                             style="width: {{ $topSeries->max('cars_count') ? ($s->cars_count / $topSeries->max('cars_count')) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ========== PENDING + RECENT ========== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Pending Cars --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-bold text-lg">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('storage/car.png') }}" 
                        alt="Sellers" 
                        class="w-8 h-8 object-contain">
                </div>
                 Mobil Pending</h3>
            <a href="{{ route('admin.cars.index', ['status' => 'pending']) }}"
               class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        @if($pendingCars->count())
            <div class="divide-y">
                @foreach($pendingCars as $car)
                    <div class="p-4 flex items-center gap-3 hover:bg-gray-50">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-2xl">🚗</div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate">{{ $car->title }}</p>
                            <p class="text-xs text-gray-500">{{ $car->seller->store_name ?? '-' }}</p>
                            <p class="text-sm font-bold text-blue-600 mt-1">
                                Rp {{ number_format($car->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.cars.show', $car) }}"
                           class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                            Review
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <div class="w-14 h-14 flex items-center justify-center">
                    <img src="{{ asset('storage/centang.png') }}" 
                        alt="Sellers" 
                        class="w-8 h-8 object-contain">
                </div>
                Tidak ada mobil pending</div>
        @endif
    </div>

    {{-- Pending Sellers --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-bold text-lg">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('storage/seller.png') }}" 
                        alt="Sellers" 
                        class="w-8 h-8 object-contain">
                </div>
                Seller Pending</h3>
            <a href="{{ route('admin.sellers.index', ['status' => 'pending']) }}"
               class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        @if($pendingSellers->count())
            <div class="divide-y">
                @foreach($pendingSellers as $seller)
                    <div class="p-4 flex items-center gap-3 hover:bg-gray-50">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold">
                            {{ strtoupper(substr($seller->store_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate">{{ $seller->store_name }}</p>
                            <p class="text-xs text-gray-500">{{ $seller->city }}, {{ $seller->province }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.sellers.verify', $seller) }}">
                            @csrf
                            @method('PATCH')
                            <button class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Verify
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <div class="w-14 h-14 flex items-center justify-center">
                    <img src="{{ asset('storage/centang.png') }}" 
                        alt="Sellers" 
                        class="w-8 h-8 object-contain">
                </div>
                Tidak ada seller pending</div>
        @endif
    </div>
</div>

{{-- ========== RECENT ORDERS ========== --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-bold text-lg">
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <img src="{{ asset('storage/box.png') }}" 
                    alt="Sellers" 
                    class="w-8 h-8 object-contain">
            </div>
            Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}"
           class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
    </div>
    @if($recentOrders->count())
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-xs">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->car->title ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-blue-600">
                                Rp {{ number_format($order->car_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                    {{ $order->status_label ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-8 text-center text-gray-500">Belum ada pesanan</div>
    @endif
</div>

@endsection