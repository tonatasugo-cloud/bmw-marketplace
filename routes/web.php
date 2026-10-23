<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TestDriveController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Seller\CarController as SellerCarController;
use App\Http\Controllers\Seller\OfferController as SellerOfferController;
use App\Http\Controllers\Seller\TestDriveController as SellerTestDriveController;
use App\Http\Controllers\Seller\MessageController as SellerMessageController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProfileController as SellerProfileController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{slug}', [CarController::class, 'show'])->name('cars.show');

Route::get('/sellers/{slug}', [CarController::class, 'sellerProfile'])->name('sellers.show');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| BUYER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{car}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start/{car}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/message', [ChatController::class, 'store'])->name('chat.store');

    // Test Drive
    Route::get('/test-drives', [TestDriveController::class, 'index'])->name('test-drives.index');
    Route::post('/test-drives', [TestDriveController::class, 'store'])->name('test-drives.store');
    Route::patch('/test-drives/{testDrive}/cancel', [TestDriveController::class, 'cancel'])->name('test-drives.cancel');

    // Offers
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::post('/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::patch('/offers/{offer}/cancel', [OfferController::class, 'cancel'])->name('offers.cancel');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    // Payment
    Route::get('/orders/{order}/payment', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');

    // Review
    Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| SELLER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

    Route::get('/dashboard', [SellerDashboard::class, 'index'])->name('dashboard');

    Route::resource('cars', SellerCarController::class);

    Route::get('/messages', [SellerMessageController::class, 'index'])->name('messages.index');

    Route::get('/offers', [SellerOfferController::class, 'index'])->name('offers.index');
    Route::patch('/offers/{offer}/accept', [SellerOfferController::class, 'accept'])->name('offers.accept');
    Route::patch('/offers/{offer}/reject', [SellerOfferController::class, 'reject'])->name('offers.reject');
    Route::post('/offers/{offer}/counter', [SellerOfferController::class, 'counter'])->name('offers.counter');

    Route::get('/test-drives', [SellerTestDriveController::class, 'index'])->name('test-drives.index');
    Route::patch('/test-drives/{testDrive}/respond', [SellerTestDriveController::class, 'respond'])->name('test-drives.respond');

    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [SellerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');

        // Car Images Management (Seller)
    Route::post('/cars/{car}/images', [\App\Http\Controllers\Admin\CarImageController::class, 'store'])
        ->name('cars.images.store');
    Route::patch('/cars/images/{image}/primary', [\App\Http\Controllers\Admin\CarImageController::class, 'setPrimary'])
        ->name('cars.images.primary');
    Route::patch('/cars/images/{image}/category', [\App\Http\Controllers\Admin\CarImageController::class, 'updateCategory'])
        ->name('cars.images.category');
    Route::delete('/cars/images/{image}', [\App\Http\Controllers\Admin\CarImageController::class, 'destroy'])
        ->name('cars.images.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserController::class);
    Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::patch('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    Route::resource('sellers', AdminSellerController::class);
    Route::patch('/sellers/{seller}/verify', [AdminSellerController::class, 'verify'])->name('sellers.verify');
    Route::patch('/sellers/{seller}/reject', [AdminSellerController::class, 'reject'])->name('sellers.reject');
    Route::patch('/sellers/{seller}/suspend', [AdminSellerController::class, 'suspend'])->name('sellers.suspend');

    Route::resource('cars', AdminCarController::class);
    Route::patch('/cars/{car}/approve', [AdminCarController::class, 'approve'])->name('cars.approve');
    Route::patch('/cars/{car}/reject', [AdminCarController::class, 'reject'])->name('cars.reject');
    Route::patch('/cars/{car}/feature', [AdminCarController::class, 'feature'])->name('cars.feature');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/payments/{payment}/verify', [AdminOrderController::class, 'verifyPayment'])->name('payments.verify');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::patch('/reports/{report}/resolve', [AdminReportController::class, 'resolve'])->name('reports.resolve');

        // Car Images Management
    Route::post('/cars/{car}/images', [\App\Http\Controllers\Admin\CarImageController::class, 'store'])
        ->name('cars.images.store');
    Route::patch('/cars/images/{image}/primary', [\App\Http\Controllers\Admin\CarImageController::class, 'setPrimary'])
        ->name('cars.images.primary');
    Route::patch('/cars/images/{image}/category', [\App\Http\Controllers\Admin\CarImageController::class, 'updateCategory'])
        ->name('cars.images.category');
    Route::delete('/cars/images/{image}', [\App\Http\Controllers\Admin\CarImageController::class, 'destroy'])
        ->name('cars.images.destroy');
});