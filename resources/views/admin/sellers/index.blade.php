@extends('layouts.admin')

@section('title', 'Kelola Sellers')
@section('page-title', 'Kelola Sellers')
@section('page-subtitle', 'Verifikasi dan kelola showroom')

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="font-bold text-lg">Total: {{ $sellers->total() }} sellers</h2>

        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari showroom..."
                   class="border rounded-lg px-4 py-2 text-sm w-64">
            <select name="status" class="border rounded-lg px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                <option value="verified" @selected(request('status') == 'verified')>Verified</option>
                <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                <option value="suspended" @selected(request('status') == 'suspended')>Suspended</option>
            </select>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                <img src="{{ asset('storage/search2.png') }}" alt="Revenue" class="w-8 h-8 object-contain">
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Showroom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($sellers as $seller)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center font-bold text-purple-600">
                                    {{ strtoupper(substr($seller->store_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium">{{ $seller->store_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $seller->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $seller->city }}, {{ $seller->province }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-yellow-500">⭐ {{ number_format($seller->rating_avg, 1) }}</span>
                            <span class="text-gray-400">({{ $seller->rating_count }})</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $seller->status === 'verified' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $seller->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $seller->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $seller->status === 'suspended' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ strtoupper($seller->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.sellers.show', $seller) }}"
                                   class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200">
                                    Detail
                                </a>
                                @if($seller->status === 'pending')
                                    <form method="POST" action="{{ route('admin.sellers.verify', $seller) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full hover:bg-green-200">
                                            ✓ Verify
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.sellers.reject', $seller) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full hover:bg-red-200">
                                            ✗ Reject
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada seller ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sellers->hasPages())
        <div class="p-4 border-t">
            {{ $sellers->links() }}
        </div>
    @endif
</div>

@endsection