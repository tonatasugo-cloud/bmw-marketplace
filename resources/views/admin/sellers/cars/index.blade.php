@extends('layouts.app')

@section('title', 'Mobil Saya — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black">🚗 Mobil Saya</h1>
            <p class="text-gray-300 mt-1">Kelola semua listing mobil Anda</p>
        </div>
        <a href="{{ route('seller.cars.create') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
            ➕ Tambah Mobil
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6 flex gap-2">
        <a href="{{ route('seller.dashboard') }}"
           class="text-sm text-gray-600 hover:text-blue-600">← Kembali ke Dashboard</a>
    </div>

    @if($cars->count())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($cars as $car)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                            🚗
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $car->title }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $car->year }} · {{ number_format($car->mileage, 0, ',', '.') }} KM
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-blue-600">
                                    Rp {{ number_format($car->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $car->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $car->status === 'reserved' ? 'bg-orange-100 text-orange-700' : '' }}
                                        {{ $car->status === 'sold' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $car->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ strtoupper($car->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">👁️ {{ $car->view_count }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('cars.show', $car->slug) }}" target="_blank"
                                           class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full hover:bg-gray-200">
                                            Lihat
                                        </a>
                                        <a href="{{ route('seller.cars.edit', $car) }}"
                                           class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('seller.cars.destroy', $car) }}"
                                              onsubmit="return confirm('Yakin ingin hapus mobil ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full hover:bg-red-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $cars->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">🚗</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Mobil</h2>
            <p class="text-gray-500 mb-6">Mulai listing mobil pertama Anda</p>
            <a href="{{ route('seller.cars.create') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700">
                ➕ Tambah Mobil Pertama
            </a>
        </div>
    @endif
</div>

@endsection