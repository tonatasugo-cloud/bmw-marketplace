@extends('layouts.app')

@section('title', 'Halaman — Bavaria Select')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="bg-white rounded-xl shadow p-12 text-center">
        <p class="text-6xl mb-4">🚧</p>
        <h1 class="text-2xl font-bold mb-2">Halaman Dalam Pengembangan</h1>
        <p class="text-gray-600 mb-6">Fitur ini akan tersedia segera.</p>
        <a href="{{ url()->previous() }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg">
            ← Kembali
        </a>
    </div>
</div>
@endsection