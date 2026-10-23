@extends('layouts.app')

@section('title', $seller->store_name . ' — Bavaria Select')

@section('content')

{{-- HEADER --}}
<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="w-24 h-24 bg-blue-100 rounded-2xl flex items-center justify-center text-4xl font-black text-blue-600 flex-shrink-0">
                @if($seller->logo)
                    <img src="{{ asset('storage/' . $seller->logo) }}"
                         class="w-full h-full object-cover rounded-2xl">
                @else
                    {{ strtoupper(substr($seller->store_name, 0, 1)) }}
                @endif
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-3xl font-black">{{ $seller->store_name }}</h1>
                    @if($seller->status === 'verified')
                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">✓ VERIFIED</span>
                    @endif
                </div>
                <p class="text-gray-300 mt-2">📍 {{ $seller->address }}, {{ $seller->city }}, {{ $seller->province }}</p>
                <div class="flex items-center gap-4 mt-3 text-sm flex-wrap">
                    <span class="text-yellow-400">⭐ {{ number_format($seller->rating_avg, 1) }} ({{ $seller->rating_count }} review)</span>
                    <span><img src="{{ asset('storage/clock.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> {{ $seller->operational_hours ?? 'Senin-Sabtu' }}</span>
                </div>
            </div>
        </div>

        {{-- Kontak --}}
        <div class="mt-6 flex flex-wrap gap-3">
            @auth
                @if(auth()->id() !== $seller->user_id)
                    <form method="POST" action="{{ route('chat.start', ['car' => $cars->first()->id ?? 0]) }}">
                        @csrf
                        <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100">
                        <img src="{{ asset('storage/chat.png') }}" alt="Revenue" class="w-8 h-8 object-contain">    
                        Chat Seller
                        </button>
                    </form>
                @endif
            @endauth
            <a href="tel:{{ $seller->phone }}"
               class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600">
               <img src="{{ asset('storage/telepon.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> 
               {{ $seller->phone }}
            </a>
            @if($seller->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $seller->whatsapp) }}"
                   target="_blank"
                   class="bg-white text-green-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100">
                    <img src="{{ asset('storage/chat.png') }}" alt="Revenue" class="w-8 h-8 object-contain">
                   WhatsApp
                </a>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- About --}}
    @if($seller->description)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="font-bold text-lg mb-3"><img src="{{ asset('storage/seller.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Tentang Showroom</h2>
            <p class="text-gray-700">{{ $seller->description }}</p>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $seller->cars()->where('status', 'available')->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Mobil Aktif</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $seller->cars()->where('status', 'sold')->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Terjual</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-yellow-600">{{ $seller->rating_count }}</p>
            <p class="text-xs text-gray-500 mt-1">Review</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-purple-600">
                {{ $seller->joined_at ? $seller->joined_at->diffForHumans(null, true) : 'Baru' }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Bergabung</p>
        </div>
    </div>

    {{-- Mobil yang dijual --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4"><img src="{{ asset('storage/car.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Mobil yang Dijual</h2>

        @if($cars->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($cars as $car)
                    <x-car-card :car="$car" />
                @endforeach
            </div>

            <div class="mt-6">
                {{ $cars->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow p-12 text-center text-gray-500">
                Belum ada mobil tersedia dari showroom ini.
            </div>
        @endif
    </div>

    {{-- Review --}}
    @if($reviews->count())
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">⭐ Review Pembeli</h2>
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600 flex-shrink-0">
                                {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <p class="font-bold">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <span class="text-yellow-500 text-sm">
                                        {{ str_repeat('⭐', $review->seller_rating) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                                @if($review->comment)
                                    <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection