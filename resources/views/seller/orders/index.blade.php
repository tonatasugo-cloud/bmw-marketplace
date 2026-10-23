@extends('layouts.app')

@section('title', 'Pesanan — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-black">📦 Pesanan Masuk</h1>
        <p class="text-gray-300 mt-1">Daftar pesanan untuk mobil Anda</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('seller.dashboard') }}"
           class="text-sm text-gray-600 hover:text-blue-600">← Kembali ke Dashboard</a>
    </div>

    @if($orders->count())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-xs">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->car->title ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-blue-600 text-sm">
                                Rp {{ number_format($order->car_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">📦</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500">Pesanan untuk mobil Anda akan muncul di sini</p>
        </div>
    @endif
</div>

@endsection