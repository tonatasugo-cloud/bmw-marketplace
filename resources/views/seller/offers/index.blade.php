@extends('layouts.app')

@section('title', 'Penawaran — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-black">💰 Penawaran Masuk</h1>
        <p class="text-gray-300 mt-1">Kelola penawaran dari pembeli</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <a href="{{ route('seller.dashboard') }}"
           class="text-sm text-gray-600 hover:text-blue-600">← Kembali ke Dashboard</a>
    </div>

    @if($offers->count())
        <div class="space-y-4">
            @foreach($offers as $offer)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                🚗
                            </div>
                            <div class="flex-1">
                                <p class="font-bold">{{ $offer->car->title ?? 'Mobil dihapus' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Dari: {{ $offer->user->name ?? '-' }}</p>

                                <div class="mt-3 flex items-center gap-6">
                                    <div>
                                        <p class="text-xs text-gray-500">Harga List</p>
                                        <p class="text-sm text-gray-600 line-through">
                                            Rp {{ number_format($offer->car->price ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Penawaran</p>
                                        <p class="text-lg font-bold text-blue-600">
                                            Rp {{ number_format($offer->amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                @if($offer->message)
                                    <p class="text-sm text-gray-600 mt-3 italic">"{{ $offer->message }}"</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            @if($offer->status === 'pending')
                                <div class="flex flex-col gap-2">
                                    <form method="POST" action="{{ route('seller.offers.accept', $offer) }}">
                                        @csrf @method('PATCH')
                                        <button class="w-full bg-green-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-700">
                                            ✓ Terima
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('seller.offers.reject', $offer) }}">
                                        @csrf @method('PATCH')
                                        <button class="w-full bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg hover:bg-red-200">
                                            ✗ Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs px-3 py-1 rounded-full
                                    {{ $offer->status === 'accepted' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $offer->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $offer->status === 'countered' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                    {{ strtoupper($offer->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $offers->links() }}</div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">💰</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Penawaran</h2>
            <p class="text-gray-500">Penawaran dari pembeli akan muncul di sini</p>
        </div>
    @endif
</div>

@endsection