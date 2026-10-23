@extends('layouts.app')

@section('title', 'Pembayaran — ' . $order->order_number)

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-black">💳 Pembayaran Booking Fee</h1>
        <p class="text-gray-300 mt-1">Order: {{ $order->order_number }}</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Info --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
        <p class="text-sm text-blue-800">
            <strong>Booking Fee:</strong>
            <span class="text-2xl font-bold">Rp {{ number_format($order->booking_fee, 0, ',', '.') }}</span>
        </p>
        <p class="text-xs text-blue-700 mt-2">
            Booking fee akan dikurangi dari total harga mobil. Sisa pembayaran
            <strong>Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</strong>
            dibayar setelah verifikasi.
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm p-8">
        <form method="POST" action="{{ route('payments.store', $order) }}" enctype="multipart/form-data">
            @csrf

            <h2 class="font-bold text-lg mb-4">Metode Pembayaran</h2>

            <div class="space-y-3 mb-6">
                <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="method" value="bank_transfer" required>
                    <div class="flex-1">
                        <p class="font-medium">🏦 Transfer Bank</p>
                        <p class="text-xs text-gray-500">Transfer ke rekening yang tertera</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="method" value="virtual_account">
                    <div class="flex-1">
                        <p class="font-medium">💳 Virtual Account</p>
                        <p class="text-xs text-gray-500">Bayar via ATM / mobile banking</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="method" value="ewallet">
                    <div class="flex-1">
                        <p class="font-medium">📱 E-Wallet</p>
                        <p class="text-xs text-gray-500">GoPay, OVO, DANA</p>
                    </div>
                </label>
            </div>

            <hr class="my-6">

            <h2 class="font-bold text-lg mb-4">Detail Pembayaran</h2>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Jumlah Bayar</label>
                    <input type="number" name="amount" value="{{ $order->booking_fee }}" required
                           min="1000000" class="w-full border rounded-lg px-4 py-3 bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">Booking fee minimal Rp 10.000.000</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Bank / Channel</label>
                    <input type="text" name="bank_name" placeholder="Contoh: BCA, Mandiri, GoPay"
                           class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Upload Bukti Transfer</label>
                    <input type="file" name="proof" accept="image/*,application/pdf"
                           class="w-full border rounded-lg px-4 py-3">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Max 5MB.</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Catatan (opsional)</label>
                    <textarea name="notes" rows="2"
                              class="w-full border rounded-lg px-4 py-3"></textarea>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">
                    ✓ Konfirmasi Pembayaran
                </button>
                <a href="{{ route('orders.show', $order) }}"
                   class="px-8 py-4 border rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection