<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Favorite;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['car.primaryImage', 'car.series', 'car.seller'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('favorites'));
    }

    public function toggle(Car $car)
    {
        $user = auth()->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('car_id', $car->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $car->decrement('wishlist_count');
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'car_id'  => $car->id,
            ]);
            $car->increment('wishlist_count');
            $status = 'added';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'status' => $status,
                'count'  => $car->fresh()->wishlist_count,
            ]);
        }

        return back()->with('success',
            $status === 'added' ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist'
        );
    }
}