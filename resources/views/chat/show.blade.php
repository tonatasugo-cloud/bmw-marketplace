@extends('layouts.app')

@section('title', 'Chat — ' . ($conversation->seller->store_name ?? 'Seller'))

@section('content')

<div class="bg-white border-b">
    <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('chat.index') }}" class="text-gray-500 hover:text-blue-600 text-xl">←</a>
            @php
                $isBuyer = $conversation->buyer_id === auth()->id();
                $otherUser = $isBuyer ? $conversation->seller->user : $conversation->buyer;
            @endphp
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                {{ strtoupper(substr($otherUser->name ?? '?', 0, 1)) }}
            </div>
            <div>
                <p class="font-bold">{{ $otherUser->name ?? 'User' }}</p>
                @if($conversation->seller && $conversation->seller->status === 'verified')
                    <p class="text-xs text-blue-600">✓ Verified Seller</p>
                @endif
            </div>
        </div>
        @if($conversation->car)
            <a href="{{ route('cars.show', $conversation->car->slug) }}"
               class="text-sm text-blue-600 hover:underline">Lihat Mobil</a>
        @endif
    </div>

    {{-- Info mobil --}}
    @if($conversation->car)
        <div class="bg-blue-50 border-t border-blue-100">
            <div class="max-w-4xl mx-auto px-4 py-3 flex items-center gap-3">
                <div class="w-12 h-10 bg-white rounded flex items-center justify-center text-lg flex-shrink-0">
                    🚗
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ $conversation->car->title }}</p>
                    <p class="text-xs text-blue-700 font-bold">
                        Rp {{ number_format($conversation->car->price, 0, ',', '.') }}
                    </p>
                </div>
                <a href="{{ route('cars.show', $conversation->car->slug) }}"
                   class="text-xs bg-white border border-blue-200 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-100">
                    Detail
                </a>
            </div>
        </div>
    @endif
</div>

{{-- AREA CHAT --}}
<div class="max-w-4xl mx-auto px-4 py-6" style="min-height: calc(100vh - 300px);">

    <div class="space-y-4" id="chatContainer">
        @forelse($messages as $msg)
            @php $isMine = $msg->sender_id === auth()->id(); @endphp
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs md:max-w-md">
                    <div class="rounded-2xl px-4 py-3
                        {{ $isMine ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white border text-gray-800 rounded-bl-sm' }}">
                        <p class="whitespace-pre-line">{{ $msg->body }}</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 {{ $isMine ? 'text-right' : '' }}">
                        {{ $msg->created_at->format('H:i') }}
                        @if($isMine && $msg->is_read)
                            · ✓✓
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-12">
                <p class="text-5xl mb-3">💬</p>
                <p>Belum ada pesan. Mulai percakapan!</p>
            </div>
        @endforelse
    </div>
</div>

{{-- FORM KIRIM PESAN --}}
<div class="bg-white border-t sticky bottom-0">
    <div class="max-w-4xl mx-auto px-4 py-4">
        <form method="POST" action="{{ route('chat.store', $conversation) }}" class="flex gap-2">
            @csrf
            <input type="text" name="body" required
                   placeholder="Ketik pesan..."
                   autocomplete="off"
                   class="flex-1 border rounded-full px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-full font-medium hover:bg-blue-700">
                ➤ Kirim
            </button>
        </form>
    </div>
</div>

{{-- Auto scroll ke bawah --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('chatContainer');
        if (container) {
            window.scrollTo(0, document.body.scrollHeight);
        }
    });
</script>

@endsection