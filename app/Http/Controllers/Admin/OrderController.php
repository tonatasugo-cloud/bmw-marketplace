<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'car.primaryImage', 'seller', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('order_number', 'like', '%' . $request->q . '%');
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user', 'car.images', 'car.series', 'seller.user',
            'payments', 'shipment.trackings', 'review',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:created,payment_pending,payment_verified,reserved,document_processing,ready_delivery,delivered,completed,cancelled',
            'notes'  => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'status' => $validated['status'],
                'notes'  => $validated['notes'] ?? $order->notes,
            ]);

            // Sinkronisasi status mobil
            if ($validated['status'] === 'completed' || $validated['status'] === 'delivered') {
                $order->car->update([
                    'status'  => 'sold',
                    'sold_at' => now(),
                ]);
                $order->update(['completed_at' => now()]);
            }

            if ($validated['status'] === 'cancelled') {
                $order->car->update(['status' => 'available']);
            }
        });

        return back()->with('success', 'Status order diperbarui.');
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'action' => 'required|in:verify,reject',
            'notes'  => 'nullable|string|max:500',
        ]);

        if ($validated['action'] === 'verify') {
            $payment->update([
                'status'      => 'verified',
                'verified_at' => now(),
                'notes'       => $validated['notes'] ?? null,
            ]);

            $order = $payment->order;
            $order->increment('total_paid', $payment->amount);
            $order->update(['status' => 'reserved']);

            return back()->with('success', 'Pembayaran diverifikasi.');
        }

        $payment->update([
            'status' => 'rejected',
            'notes'  => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }
}