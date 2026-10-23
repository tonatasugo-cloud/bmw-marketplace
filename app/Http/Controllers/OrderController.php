<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['car.primaryImage', 'seller', 'payments'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id'           => 'required|exists:cars,id',
            'delivery_method'  => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:delivery_method,delivery|nullable|string',
            'notes'            => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $car = Car::where('id', $validated['car_id'])
                ->where('status', 'available')
                ->lockForUpdate()
                ->firstOrFail();

            $bookingFee = 10_000_000;

            $order = Order::create([
                'user_id'           => auth()->id(),
                'car_id'            => $car->id,
                'seller_id'         => $car->seller_id,
                'car_price'         => $car->price,
                'booking_fee'       => $bookingFee,
                'total_paid'        => 0,
                'remaining_payment' => $car->price - $bookingFee,
                'status'            => 'payment_pending',
                'delivery_method'   => $validated['delivery_method'],
                'delivery_address'  => $validated['delivery_address'] ?? null,
                'notes'             => $validated['notes'] ?? null,
            ]);

            // Reserve mobil
            $car->update(['status' => 'reserved']);

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Mobil berhasil direservasi! Silakan selesaikan booking fee.');
        });
    }

    public function show(Order $order)
    {
        // Cek akses: pembeli, penjual, atau admin
        $user = auth()->user();
        $isBuyer  = $order->user_id === $user->id;
        $isSeller = $order->seller->user_id === $user->id;
        $isAdmin  = $user->hasRole('admin');

        if (!$isBuyer && !$isSeller && !$isAdmin) {
            abort(403);
        }

        $order->load([
            'car.images', 'car.series',
            'seller.user', 'user',
            'payments', 'shipment.trackings',
            'review',
        ]);

        return view('orders.show', compact('order', 'isBuyer', 'isSeller', 'isAdmin'));
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $order->load(['car.series', 'seller.user', 'user']);

        $pdf = Pdf::loadView('orders.invoice-pdf', compact('order'));
        $pdf->setPaper('a4');

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['created', 'payment_pending'])) {
            return back()->withErrors(['msg' => 'Pesanan tidak bisa dibatalkan.']);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            $order->car->update(['status' => 'available']);
        });

        return back()->with('success', 'Pesanan dibatalkan.');
    }
}