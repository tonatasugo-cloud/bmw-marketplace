<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::where('user_id', auth()->id())
            ->with(['car.primaryImage', 'car.seller'])
            ->latest()
            ->paginate(10);

        return view('offers.index', compact('offers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id'  => 'required|exists:cars,id',
            'amount'  => 'required|numeric|min:1000000',
            'message' => 'nullable|string|max:500',
        ]);

        $car = Car::findOrFail($validated['car_id']);

        // Minimal 70% dari harga list
        if ($validated['amount'] < $car->price * 0.7) {
            return back()->withErrors([
                'amount' => 'Penawaran minimal 70% dari harga list (Rp '
                    . number_format($car->price * 0.7, 0, ',', '.') . ')'
            ]);
        }

        Offer::create([
            'user_id'   => auth()->id(),
            'car_id'    => $car->id,
            'amount'    => $validated['amount'],
            'message'   => $validated['message'] ?? null,
            'sender_id' => auth()->id(),
            'status'    => 'pending',
        ]);

        return back()->with('success', 'Penawaran terkirim. Menunggu respon penjual.');
    }

    public function cancel(Offer $offer)
    {
        if ($offer->user_id !== auth()->id()) {
            abort(403);
        }

        if ($offer->status !== 'pending') {
            return back()->withErrors(['msg' => 'Penawaran sudah diproses, tidak bisa dibatalkan.']);
        }

        $offer->update(['status' => 'cancelled']);

        return back()->with('success', 'Penawaran dibatalkan.');
    }
}