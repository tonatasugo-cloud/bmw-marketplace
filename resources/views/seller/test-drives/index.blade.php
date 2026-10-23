@extends('layouts.app')

@section('title', 'Test Drive — Seller Dashboard')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-black">📅 Test Drive</h1>
        <p class="text-gray-300 mt-1">Jadwal test drive dari pembeli</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('seller.dashboard') }}"
           class="text-sm text-gray-600 hover:text-blue-600">← Kembali ke Dashboard</a>
    </div>

    @if($testDrives->count())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($testDrives as $td)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ $td->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $td->user->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $td->car->title ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <p class="font-medium">{{ $td->scheduled_date->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $td->scheduled_time }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $td->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $td->status === 'confirmed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $td->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $td->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ strtoupper($td->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($td->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('seller.test-drives.respond', $td) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                                ✓ Konfirmasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('seller.test-drives.respond', $td) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                                ✗ Tolak
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $testDrives->links() }}</div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <p class="text-6xl mb-4">📅</p>
            <h2 class="text-xl font-bold mb-2">Belum Ada Test Drive</h2>
            <p class="text-gray-500">Permintaan test drive akan muncul di sini</p>
        </div>
    @endif
</div>

@endsection