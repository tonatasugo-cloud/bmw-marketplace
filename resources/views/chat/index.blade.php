@extends('layouts.app')

@section('title', 'Chat — Bavaria Select')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-3xl font-black">💬 Chat</h1>
        <p class="text-gray-300 mt-2">Percakapan Anda dengan penjual / pembeli</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-8">

    @if($conversations->count())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden divide-y">
            @foreach($conversations as $conv)
                @php
                    // Cek apakah saya buyer atau seller di percakapan ini
                    $isBuyer  = $conv->buyer_id === auth()->id();
                    $otherUser = $isBuyer ? $conv->seller->user : $conv->buyer;
                    $unread = $conv->messages()
                        ->where('sender_id', '!=', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp

                <a href="{{ route('chat.show', $conv) }}"
                   class="flex items-center gap-4 p-4 hover:bg-gray-50 transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600 flex-shrink-0 text-lg">
                        {{ strtoupper(substr($otherUser->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-bold truncate">{{ $otherUser->name ?? 'User' }}</p>
                            <span class="text-xs text-gray-400">
                                {{ $isBuyer ? '(Seller)' : '(Buyer)' }}
                            </span>
                        </div>
                        @if($conv->car)
                            <p class="text-xs text-blue-600 truncate mt-0.5">
                                🚗 {{ $conv->car->title }}
                            </p>
                        @endif
                        @if($conv->lastMessage)
                            <p class="text-sm text-gray-500 truncate mt-1">
                                {{ $conv->lastMessage->sender_id === auth()->id() ? 'Anda: ' : '' }}
                                {{ $conv->lastMessage->body }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400 italic mt-1">Belum ada pesan</p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($conv->last_message_at)
                            <p class="text-xs text-gray-400">
                                {{ $conv->last_message_at->diffForHumans(null, true) }}
                            </p>
                        @endif
                        @if($unread > 0)
                            <span class="inline-block mt-1 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                                {{ $unread }}
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $conversations->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">💬</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Percakapan</h2>
            <p class="text-gray-500 mb-6">Mulai chat dengan penjual dari halaman detail mobil</p>
            <a href="{{ route('cars.index') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700">
                🔍 Cari Mobil
            </a>
        </div>
    @endif
</div>

@endsection