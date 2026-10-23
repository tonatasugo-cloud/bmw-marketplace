@extends('layouts.app')

@section('title', 'Dashboard — Nicolas Select')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-black">Halo, {{ auth()->user()->name }}</h1>
        <p class="text-gray-300 mt-2">Selamat datang di dashboard Nicolas Select</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Wishlist</p>
            <p class="text-3xl font-bold text-red-500">
                {{ auth()->user()->favorites()->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pesanan</p>
            <p class="text-3xl font-bold text-blue-600">
                {{ auth()->user()->orders()->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Test Drive</p>
            <p class="text-3xl font-bold text-purple-600">
                {{ auth()->user()->testDrives()->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Penawaran</p>
            <p class="text-3xl font-bold text-yellow-500">
                {{ auth()->user()->offers()->count() }}
            </p>
        </div>
    </div>

    {{-- Menu Cepat --}}
    <h2 class="text-2xl font-bold mb-4">Menu Cepat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('cars.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition flex items-center gap-4">
            <div class="text-3xl"><img src="{{ asset('storage/car.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></div>
            <div>
                <p class="font-bold">Cari BMW</p>
                <p class="text-sm text-gray-500">Jelajahi 20+ unit BMW</p>
            </div>
        </a>
        <a href="{{ route('wishlist.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition flex items-center gap-4">
            <div class="text-3xl">❤️</div>
            <div>
                <p class="font-bold">Wishlist</p>
                <p class="text-sm text-gray-500">Mobil yang Anda simpan</p>
            </div>
        </a>
        <a href="{{ route('chat.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition flex items-center gap-4">
            <div class="text-3xl"><img src="{{ asset('storage/chat.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></div>
            <div>
                <p class="font-bold">Chat Seller</p>
                <p class="text-sm text-gray-500">Tanya langsung ke penjual</p>
            </div>
        </a>
        <a href="{{ route('orders.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition flex items-center gap-4">
            <div class="text-3xl"><img src="{{ asset('storage/box.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></div>
            <div>
                <p class="font-bold">Pesanan Saya</p>
                <p class="text-sm text-gray-500">Lacak transaksi</p>
            </div>
        </a>
        <a href="{{ route('test-drives.index') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition flex items-center gap-4">
            <div class="text-3xl"><img src="{{ asset('storage/tgl.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></div>
            <div>
                <p class="font-bold">Test Drive</p>
                <p class="text-sm text-gray-500">Jadwal test drive Anda</p>
            </div>
        </a>
        <a href="{{ route('profile.edit') }}"
           class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition flex items-center gap-4">
            <div class="text-3xl"><img src="{{ asset('storage/setting.png') }}" alt="Revenue" class="w-8 h-8 object-contain"></div>
            <div>
                <p class="font-bold">Profil</p>
                <p class="text-sm text-gray-500">Kelola akun</p>
            </div>
        </a>
    </div>

    {{-- Rekomendasi Mobil --}}
    @php
        $recommended = \App\Models\Car::available()->with('primaryImage', 'series')->inRandomOrder()->take(4)->get();
    @endphp

    @if($recommended->count())
        <h2 class="text-2xl font-bold mb-4">Rekomendasi Untuk Anda</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($recommended as $car)
                <x-car-card :car="$car" />
            @endforeach
        </div>
    @endif
</div>

@endsection