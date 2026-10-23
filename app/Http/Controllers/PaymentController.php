<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'method'    => 'required|in:bank_transfer,ewallet,virtual_account',
            'amount'    => 'required|numeric|min:1000000',
            'bank_name' => 'nullable|string|max:100',
            'va_number' => 'nullable|string|max:50',
            'proof'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes'     => 'nullable|string|max:500',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payment-proofs', 'public');
        }

        Payment::create([
            'order_id'   => $order->id,
            'method'     => $validated['method'],
            'amount'     => $validated['amount'],
            'bank_name'  => $validated['bank_name'] ?? null,
            'va_number'  => $validated['va_number'] ?? null,
            'proof_path' => $proofPath,
            'notes'      => $validated['notes'] ?? null,
            'status'     => 'waiting_verification',
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }
}