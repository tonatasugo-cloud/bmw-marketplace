@extends('layouts.app')

@section('title', 'Pesanan ' . $order->order_number)

@section('content')

<div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-10">
    <div class="max-w-5xl mx-auto px-4">
        <p class="text-blue-400 text-sm font-semibold tracking-wide">DETAIL PESANAN</p>
        <h1 class="text-2xl font-black mt-1 font-mono">{{ $order->order_number }}</h1>
        <p class="text-gray-300 mt-1">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-8">

    <a href="{{ route('orders.index') }}"
       class="inline-block mb-6 text-sm text-gray-600 hover:text-blue-600">← Kembali ke Daftar Pesanan</a>

    {{-- Status Timeline --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="font-bold text-lg mb-4">📊 Status Pesanan</h2>
        @php
            $allSteps = [
                'created'             => ['Dibuat', '📝'],
                'payment_pending'     => ['Menunggu Pembayaran', '⏳'],
                'payment_verified'    => ['Pembayaran Diverifikasi', '✓'],
                'reserved'            => ['Direservasi', '🔒'],
                'document_processing' => ['Proses Dokumen', '📄'],
                'ready_delivery'      => ['Siap Kirim', '📦'],
                'delivered'           => ['Terkirim', '🚚'],
                'completed'           => ['Selesai', '🎉'],
            ];
            $currentIndex = array_search($order->status, array_keys($allSteps));
            if ($currentIndex === false) $currentIndex = 0;
            $stepsArray = array_values($allSteps);
            $keysArray = array_keys($allSteps);
        @endphp

        <div class="grid grid-cols-4 md:grid-cols-8 gap-2">
            @foreach($allSteps as $key => $data)
                @php $idx = array_search($key, $keysArray); @endphp
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-xl
                        {{ $idx <= $currentIndex ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                        {{ $data[1] }}
                    </div>
                    <p class="text-xs mt-2 {{ $idx <= $currentIndex ? 'text-blue-600 font-medium' : 'text-gray-400' }}">
                        {{ $data[0] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom KIRI: Detail Mobil + Order --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Detail Mobil --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-lg mb-4">🚗 Mobil</h2>
                <div class="flex gap-4">
                    <div class="w-24 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-3xl flex-shrink-0">
                        🚗
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold">{{ $order->car->title ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->car->year ?? '' }} · {{ number_format($order->car->mileage ?? 0, 0, ',', '.') }} KM</p>
                        <p class="text-lg font-bold text-blue-600 mt-2">
                            Rp {{ number_format($order->car_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @if($order->car)
                    <a href="{{ route('cars.show', $order->car->slug) }}"
                       class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                        Lihat Detail Mobil →
                    </a>
                @endif
            </div>

            {{-- Info Pembayaran --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-lg mb-4">💰 Ringkasan Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga Mobil</span>
                        <span class="font-medium">Rp {{ number_format($order->car_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Booking Fee</span>
                        <span class="font-medium">Rp {{ number_format($order->booking_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sudah Dibayar</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($order->total_paid, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between">
                        <span class="font-bold">Sisa Pembayaran</span>
                        <span class="font-bold text-blue-600 text-lg">
                            Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            @if($order->payments->count())
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-bold text-lg mb-4">📄 Riwayat Pembayaran</h2>
                    <div class="divide-y">
                        @foreach($order->payments as $payment)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-mono text-xs text-gray-500">{{ $payment->payment_number }}</p>
                                    <p class="text-sm">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        {{ $payment->status === 'verified' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $payment->status === 'waiting_verification' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $payment->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-gray-100 text-gray-700' : '' }}">
                                        {{ $payment->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Review (kalau sudah selesai) --}}
            @if($order->status === 'completed' && $isBuyer)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-bold text-lg mb-4">⭐ Review</h2>
                    @if($order->review)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="font-medium text-green-800">✓ Anda sudah memberi review</p>
                            <div class="mt-2 flex gap-1 text-yellow-500">
                                {{ str_repeat('⭐', $order->review->car_rating) }}
                            </div>
                            @if($order->review->comment)
                                <p class="text-sm text-gray-700 mt-2">{{ $order->review->comment }}</p>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ route('reviews.store', $order) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-sm font-medium mb-1 block">Rating Mobil</label>
                                <select name="car_rating" required class="w-full border rounded-lg px-4 py-2">
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus</option>
                                    <option value="4">⭐⭐⭐⭐ Bagus</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium mb-1 block">Rating Seller</label>
                                <select name="seller_rating" required class="w-full border rounded-lg px-4 py-2">
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus</option>
                                    <option value="4">⭐⭐⭐⭐ Bagus</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium mb-1 block">Pengalaman Transaksi</label>
                                <select name="experience_rating" required class="w-full border rounded-lg px-4 py-2">
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus</option>
                                    <option value="4">⭐⭐⭐⭐ Bagus</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium mb-1 block">Komentar</label>
                                <textarea name="comment" rows="3" maxlength="1000"
                                          placeholder="Ceritakan pengalaman Anda..."
                                          class="w-full border rounded-lg px-4 py-2"></textarea>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                                ⭐ Kirim Review
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- Kolom KANAN: Aksi --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Aksi Pembayaran --}}
            @if($isBuyer && in_array($order->status, ['created', 'payment_pending']))
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-3">💳 Lakukan Pembayaran</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Bayar booking fee sebesar
                        <strong class="text-blue-600">Rp {{ number_format($order->booking_fee, 0, ',', '.') }}</strong>
                    </p>
                    <a href="{{ route('payments.create', $order) }}"
                       class="block text-center bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                        Bayar Sekarang →
                    </a>
                </div>
            @endif

            {{-- Invoice --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold mb-3">📄 Invoice</h3>
                <p class="text-sm text-gray-600 mb-4">Download invoice PDF untuk pesanan ini</p>
                <a href="{{ route('orders.invoice', $order) }}"
                   class="block text-center border-2 border-blue-600 text-blue-600 py-3 rounded-lg font-bold hover:bg-blue-50">
                    ⬇️ Download Invoice PDF
                </a>
            </div>

            {{-- Chat Seller --}}
            @if($order->seller)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-3">💬 Chat Seller</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ $order->seller->store_name }}</p>
                    <a href="{{ route('chat.index') }}"
                       class="block text-center border py-3 rounded-lg font-medium hover:bg-gray-50">
                        Buka Chat
                    </a>
                </div>
            @endif

            {{-- Cancel --}}
            @if($isBuyer && in_array($order->status, ['created', 'payment_pending']))
                <form method="POST" action="{{ route('orders.cancel', $order) }}"
                      onsubmit="return confirm('Yakin ingin batalkan pesanan?')">
                    @csrf @method('PATCH')
                    <button class="w-full border border-red-300 text-red-600 py-3 rounded-lg font-medium hover:bg-red-50">
                        ❌ Batalkan Pesanan
                    </button>
                </form>
            @endif

            {{-- Info Seller (kalau kita buyer) --}}
            @if($isBuyer && $order->seller)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-3">🏪 Seller</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                            {{ strtoupper(substr($order->seller->store_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $order->seller->store_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->seller->city }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection