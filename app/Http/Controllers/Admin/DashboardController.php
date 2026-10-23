<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarSeries;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============ STATISTIK UTAMA ============
        $stats = [
            'users'           => User::count(),
            'sellers'         => Seller::count(),
            'cars'            => Car::count(),
            'transactions'    => Order::count(),
            'revenue'         => Order::whereIn('status', ['completed', 'delivered'])
                                    ->sum('car_price'),
            'pending_cars'    => Car::where('status', 'pending')->count(),
            'pending_sellers' => Seller::where('status', 'pending')->count(),
            'active_listings' => Car::where('status', 'available')->count(),
        ];

        // ============ CHART: PENJUALAN BULANAN ============
        $monthlySales = Order::whereIn('status', ['completed', 'delivered'])
            ->selectRaw('MONTH(created_at) as month, SUM(car_price) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Lengkapi 12 bulan (bulan kosong = 0)
        $salesChart = [];
        for ($m = 1; $m <= 12; $m++) {
            $salesChart[] = $monthlySales[$m] ?? 0;
        }

        // ============ CHART: TOP SERIES ============
        $topSeries = CarSeries::withCount('cars')
            ->orderByDesc('cars_count')
            ->take(8)
            ->get();

        // ============ CHART: TOP SELLERS ============
        $topSellers = Seller::withCount('cars')
            ->withSum('cars', 'view_count')
            ->where('status', 'verified')
            ->orderByDesc('cars_count')
            ->take(5)
            ->get();

        // ============ DATA TERBARU ============
        $recentOrders = Order::with(['user', 'car.primaryImage', 'seller'])
            ->latest()
            ->take(8)
            ->get();

        $pendingCars = Car::where('status', 'pending')
            ->with(['seller', 'primaryImage', 'series'])
            ->latest()
            ->take(5)
            ->get();

        $pendingSellers = Seller::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // ============ STATISTIK TAMBAHAN ============
        $additionalStats = [
            'total_wishlist'  => DB::table('favorites')->count(),
            'total_offers'    => DB::table('offers')->count(),
            'total_test_drive' => DB::table('test_drives')->count(),
            'total_messages'  => DB::table('messages')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'salesChart',
            'topSeries',
            'topSellers',
            'recentOrders',
            'pendingCars',
            'pendingSellers',
            'additionalStats'
        ));
    }
}