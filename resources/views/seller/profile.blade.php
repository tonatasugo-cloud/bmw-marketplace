@extends('layouts.app')

@section('title', 'Profil Showroom — Nicolas Select')

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-black"><img src="{{ asset('storage/seller.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Profil Showroom</h1>
        <p class="text-gray-300 mt-2">Lengkapi data showroom Anda untuk mulai berjualan</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-8">

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
            <img src="{{ asset('storage/centang.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6">

                {{-- Info Login --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Info:</strong> Login sebagai <strong>{{ auth()->user()->name }}</strong>
                        ({{ auth()->user()->email }})
                    </p>
                </div>

                <h2 class="text-xl font-bold border-b pb-2"><img src="{{ asset('storage/catatan.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Info Showroom</h2>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">
                        Nama Showroom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="store_name"
                           value="{{ old('store_name', $seller->store_name ?? '') }}" required
                           placeholder="Contoh: Nicolas Auto Premium"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">
                        Deskripsi Showroom
                    </label>
                    <textarea name="description" rows="3"
                              placeholder="Ceritakan tentang showroom Anda..."
                              class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('description', $seller->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">
                        Logo Showroom (opsional)
                    </label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full border rounded-lg px-4 py-2">
                </div>

                <h2 class="text-xl font-bold border-b pb-2 pt-4"><img src="{{ asset('storage/mapsblack.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Alamat</h2>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="address"
                           value="{{ old('address', $seller->address ?? '') }}" required
                           placeholder="Jl. Sudirman No. 123"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city"
                               value="{{ old('city', $seller->city ?? '') }}" required
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="province"
                               value="{{ old('province', $seller->province ?? '') }}" required
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Kode Pos</label>
                        <input type="text" name="postal_code"
                               value="{{ old('postal_code', $seller->postal_code ?? '') }}"
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                </div>

                <h2 class="text-xl font-bold border-b pb-2 pt-4"><img src="{{ asset('storage/telepon.png') }}" alt="Revenue" class="w-8 h-8 object-contain"> Kontak</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">
                            Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $seller->phone ?? '') }}" required
                               placeholder="021-5550123"
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">
                            WhatsApp
                        </label>
                        <input type="text" name="whatsapp"
                               value="{{ old('whatsapp', $seller->whatsapp ?? '') }}"
                               placeholder="6281234567890"
                               class="w-full border rounded-lg px-4 py-3">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">
                        Jam Operasional
                    </label>
                    <input type="text" name="operational_hours"
                           value="{{ old('operational_hours', $seller->operational_hours ?? 'Senin-Sabtu 09:00-18:00') }}"
                           class="w-full border rounded-lg px-4 py-3">
                </div>

            </div>

            <div class="mt-8 pt-6 border-t flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                     Simpan Profil
                </button>
                <a href="{{ route('home') }}"
                   class="px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection