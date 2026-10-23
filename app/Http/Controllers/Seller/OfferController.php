<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $seller = auth()->user()->seller;

        $offers = Offer::whereHas('car', fn($q) => $q->where('seller_id', $seller->id))
            ->with(['car.primaryImage', 'user'])
            ->latest()
            ->paginate(15);

        return view('seller.offers.index', compact('offers'));
    }

    public function accept(Offer $offer)
    {
        $this->authorizeSeller($offer);

        if ($offer->status !== 'pending') {
            return back()->withErrors(['msg' => 'Offer sudah diproses.']);
        }

        $offer->update(['status' => 'accepted']);

        // Update harga jual mobil ke harga offer
        $offer->car->update(['price' => $offer->amount]);

        return back()->with('success', 'Penawaran diterima.');
    }

    public function reject(Offer $offer)
    {
        $this->authorizeSeller($offer);
        $offer->update(['status' => 'rejected']);
        return back()->with('success', 'Penawaran ditolak.');
    }

    public function counter(Request $request, Offer $offer)
    {
        $this->authorizeSeller($offer);

        $validated = $request->validate([
            'amount'  => 'required|numeric|min:1000000',
            'message' => 'nullable|string|max:500',
        ]);

        $offer->update(['status' => 'countered']);

        Offer::create([
            'user_id'         => $offer->user_id,
            'car_id'          => $offer->car_id,
            'amount'          => $validated['amount'],
            'message'         => $validated['message'] ?? null,
            'status'          => 'pending',
            'parent_offer_id' => $offer->id,
            'sender_id'       => auth()->id(),
        ]);

        return back()->with('success', 'Counter offer terkirim.');
    }

    private function authorizeSeller(Offer $offer)
    {
        if ($offer->car->seller_id !== auth()->user()->seller->id) {
            abort(403);
        }
    }
}