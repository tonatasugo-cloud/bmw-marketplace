@extends('layouts.admin')

@section('title', 'Kelola Mobil')
@section('page-title', 'Kelola Mobil')
@section('page-subtitle', 'Approve atau reject listing mobil')

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="font-bold text-lg">Total: {{ $cars->total() }} mobil</h2>

        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari mobil..."
                   class="border rounded-lg px-4 py-2 text-sm w-64">
            <select name="status" class="border rounded-lg px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                <option value="available" @selected(request('status') == 'available')>Available</option>
                <option value="reserved" @selected(request('status') == 'reserved')>Reserved</option>
                <option value="sold" @selected(request('status') == 'sold')>Sold</option>
                <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobil</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seller</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($cars as $car)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                    <img src="{{ asset('storage/car.png') }}" alt="Revenue" class="w-8 h-8 object-contain">
                                </div>
                                <div>
                                    <p class="font-medium">{{ $car->title }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $car->year }} · {{ number_format($car->mileage, 0, ',', '.') }} KM
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $car->seller->store_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-blue-600">
                            Rp {{ number_format($car->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $car->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $car->status === 'reserved' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $car->status === 'sold' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $car->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ strtoupper($car->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.cars.show', $car) }}"
                                   class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200">
                                    Detail
                                </a>
                                @if($car->status === 'pending')
                                    <form method="POST" action="{{ route('admin.cars.approve', $car) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full hover:bg-green-200">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.cars.reject', $car) }}">
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
                            Tidak ada mobil ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($cars->hasPages())
        <div class="p-4 border-t">
            {{ $cars->links() }}
        </div>
    @endif
</div>

@endsection