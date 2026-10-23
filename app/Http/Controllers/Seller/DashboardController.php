<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Offer;
use App\Models\Order;
use App\Models\TestDrive;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('seller.profile.edit')
                ->with('warning', 'Lengkapi profil showroom dulu.');
        }

        // Ambil conversation IDs milik seller ini
        $conversationIds = \App\Models\Conversation::where('seller_id', $seller->id)
            ->pluck('id');

        $stats = [
            'active_listings' => $seller->cars()->where('status', 'available')->count(),
            'total_views'     => $seller->cars()->sum('view_count') ?? 0,
            'unread_messages' => Message::whereIn('conversation_id', $conversationIds)
                ->where('sender_id', '!=', Auth::id())
                ->where('is_read', false)
                ->count(),
            'pending_test_drives' => TestDrive::whereHas('car',
                    fn($q) => $q->where('seller_id', $seller->id))
                ->where('status', 'pending')
                ->count(),
            'pending_offers' => Offer::whereHas('car',
                    fn($q) => $q->where('seller_id', $seller->id))
                ->where('status', 'pending')
                ->count(),
            'sold' => $seller->cars()->where('status', 'sold')->count(),
            'total_offers' => Offer::whereHas('car',
                    fn($q) => $q->where('seller_id', $seller->id))
                ->count(),
            'total_test_drives' => TestDrive::whereHas('car',
                    fn($q) => $q->where('seller_id', $seller->id))
                ->count(),
        ];

        // Mobil terbaru seller
        $recentCars = $seller->cars()
            ->with('primaryImage', 'series')
            ->latest()
            ->take(5)
            ->get();

        // Order terbaru
        $recentOrders = Order::where('seller_id', $seller->id)
            ->with('car.primaryImage', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'seller', 'stats', 'recentCars', 'recentOrders'
        ));
    }
}