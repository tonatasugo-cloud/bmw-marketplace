@extends('layouts.app')

@section('title', 'Chat Masuk — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-2xl font-black">💬 Chat Masuk</h1>
        <p class="text-gray-300 mt-1">Percakapan dari calon pembeli</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('seller.dashboard') }}"
           class="text-sm text-gray-600 hover:text-blue-600">← Kembali ke Dashboard</a>
    </div>

    @if($conversations->count())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden divide-y">
            @foreach($conversations as $conv)
                @php
                    $unread = $conv->messages()
                        ->where('sender_id', '!=', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                <a href="{{ route('chat.show', $conv) }}"
                   class="flex items-center gap-4 p-4 hover:bg-gray-50">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600 flex-shrink-0">
                        {{ strtoupper(substr($conv->buyer->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold">{{ $conv->buyer->name ?? 'Buyer' }}</p>
                        @if($conv->car)
                            <p class="text-xs text-blue-600">🚗 {{ $conv->car->title }}</p>
                        @endif
                        @if($conv->lastMessage)
                            <p class="text-sm text-gray-500 truncate mt-1">
                                {{ $conv->lastMessage->body }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($conv->last_message_at)
                            <p class="text-xs text-gray-400">{{ $conv->last_message_at->diffForHumans(null, true) }}</p>
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
        <div class="mt-6">{{ $conversations->links() }}</div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">💬</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Chat</h2>
            <p class="text-gray-500">Chat dari pembeli akan muncul di sini</p>
        </div>
    @endif
</div>

@endsection