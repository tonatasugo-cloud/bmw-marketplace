@extends('layouts.app')

@section('title', 'Wishlist — Bavaria Select')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-black">❤️ Wishlist Saya</h1>
        <p class="text-gray-300 mt-2">Mobil BMW yang Anda simpan</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    @if($favorites->count())
        <p class="text-sm text-gray-600 mb-6">
            Total <span class="font-bold">{{ $favorites->total() }}</span> mobil tersimpan
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($favorites as $fav)
                @if($fav->car)
                    <div class="relative">
                        <x-car-card :car="$fav->car" />

                        {{-- Tombol hapus dari wishlist --}}
                        <form method="POST" action="{{ route('wishlist.toggle', $fav->car) }}"
                              class="absolute top-2 right-2 z-10">
                            @csrf
                            <button type="submit"
                                    title="Hapus dari wishlist"
                                    class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center text-red-500 hover:bg-red-50">
                                ❤️
                            </button>
                        </form>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-8">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">💔</p>
            <h2 class="text-xl font-bold mb-2">Wishlist Anda Kosong</h2>
            <p class="text-gray-500 mb-6">Simpan mobil BMW favorit Anda dengan klik ikon hati</p>
            <a href="{{ route('cars.index') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700">
                🔍 Jelajahi BMW
            </a>
        </div>
    @endif
</div>

@endsection