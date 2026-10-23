<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return back()->withErrors(['msg' => 'Review hanya bisa diberikan setelah transaksi selesai.']);
        }

        if ($order->review) {
            return back()->withErrors(['msg' => 'Anda sudah memberi review untuk order ini.']);
        }

        $validated = $request->validate([
            'car_rating'        => 'required|integer|min:1|max:5',
            'seller_rating'     => 'required|integer|min:1|max:5',
            'experience_rating' => 'required|integer|min:1|max:5',
            'comment'           => 'nullable|string|max:1000',
        ]);

        Review::create([
            'order_id'          => $order->id,
            'user_id'           => Auth::id(),
            'car_id'            => $order->car_id,
            'seller_id'         => $order->seller_id,
            'car_rating'        => $validated['car_rating'],
            'seller_rating'     => $validated['seller_rating'],
            'experience_rating' => $validated['experience_rating'],
            'comment'           => $validated['comment'] ?? null,
        ]);

        // Update rating seller
        $seller = $order->seller;
        $avg = Review::where('seller_id', $seller->id)
            ->selectRaw('AVG((car_rating + seller_rating + experience_rating) / 3) as avg')
            ->value('avg');

        $seller->update([
            'rating_avg'   => round($avg, 2),
            'rating_count' => Review::where('seller_id', $seller->id)->count(),
        ]);

        return back()->with('success', 'Terima kasih atas review Anda!');
    }
}