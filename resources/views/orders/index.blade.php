@extends('layouts.app')

@section('title', 'Pesanan Saya — Bavaria Select')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-black">📦 Pesanan Saya</h1>
        <p class="text-gray-300 mt-2">Lacak status transaksi Anda</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-6 flex flex-col md:flex-row md:items-center gap-4">

                        {{-- Foto --}}
                        <div class="w-full md:w-32 h-24 bg-gray-100 rounded-lg flex items-center justify-center text-3xl flex-shrink-0">
                            🚗
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-mono text-xs text-gray-500">{{ $order->order_number }}</p>
                            <h3 class="font-bold text-lg mt-1">{{ $order->car->title ?? 'Mobil dihapus' }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                            <p class="text-lg font-bold text-blue-600 mt-2">
                                Rp {{ number_format($order->car_price, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div class="text-right flex-shrink-0">
                            @php
                                $statusColor = match($order->status) {
                                    'created'              => 'bg-gray-100 text-gray-700',
                                    'payment_pending'      => 'bg-yellow-100 text-yellow-700',
                                    'payment_verified'     => 'bg-blue-100 text-blue-700',
                                    'reserved'             => 'bg-orange-100 text-orange-700',
                                    'document_processing'  => 'bg-purple-100 text-purple-700',
                                    'ready_delivery'       => 'bg-cyan-100 text-cyan-700',
                                    'delivered'            => 'bg-green-100 text-green-700',
                                    'completed'            => 'bg-green-500 text-white',
                                    'cancelled'            => 'bg-red-100 text-red-700',
                                    default                => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="inline-block text-xs px-3 py-1 rounded-full {{ $statusColor }} font-medium">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('orders.show', $order) }}"
                               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                                Detail →
                            </a>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div class="px-6 pb-4">
                        <div class="flex items-center gap-1">
                            @php
                                $steps = ['created', 'payment_pending', 'payment_verified', 'reserved', 'completed'];
                                $currentIndex = array_search($order->status, $steps);
                                if ($currentIndex === false) $currentIndex = -1;
                            @endphp
                            @foreach($steps as $i => $step)
                                <div class="flex-1 h-1 rounded-full {{ $i <= $currentIndex ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">📦</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-6">Reservasi BMW impian Anda sekarang</p>
            <a href="{{ route('cars.index') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700">
                🔍 Cari BMW
            </a>
        </div>
    @endif
</div>

@endsection