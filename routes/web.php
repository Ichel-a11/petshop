<?php

use Illuminate\Support\Facades\Route;

// ================= CONTROLLERS =================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentCallbackController;

// Customer Controllers
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\SearchController;
use App\Http\Controllers\Customer\GroomingController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\GroomingController as AdminGroomingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\GroomingServiceController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\MidtransNotificationController;

// ================= MIDDLEWARE =================
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCustomer;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/
Route::get('/', [CatalogController::class, 'index'])->name('customer.catalog');
Route::get('/search', [SearchController::class, 'index'])->name('customer.search');

/*
|--------------------------------------------------------------------------
| AUTH / LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| GLOBAL ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{id}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware(['auth', IsCustomer::class]);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Produk
        Route::resource('products', AdminProductController::class);

        // Transaksi (pakai resource biar konsisten)
        Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'update']);

        // Orders (masih dipakai)
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Customers
        Route::resource('customers', AdminCustomerController::class);
        Route::put('customers/{customer}/ban', [AdminCustomerController::class, 'ban'])->name('customers.ban');
        Route::put('customers/{customer}/unban', [AdminCustomerController::class, 'unban'])->name('customers.unban');

        // Grooming Bookings (Admin side)
        Route::resource('grooming', AdminGroomingController::class)->only(['index', 'show', 'update', 'destroy']);

        // Grooming Services (CRUD)
        Route::resource('grooming-services', GroomingServiceController::class);

        // Categories
        Route::resource('categories', CategoryController::class);

        // Reviews (Ulasan)
        Route::resource('reviews', AdminReviewController::class)->only(['index', 'destroy']);
    });

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsCustomer::class])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

        // Produk
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/{id}', [ProductController::class, 'show'])->name('show');
            Route::post('/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        });

        // ===========================
        // Cart (Produk + Grooming)
        // ===========================
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add/product/{id}', [CartController::class, 'add'])->name('add.product')->defaults('type', 'product');
            Route::post('/add/grooming/{id}', [CartController::class, 'add'])->name('add.grooming')->defaults('type', 'grooming');
            Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
            Route::post('/{id}/update-quantity', [CartController::class, 'updateQuantity'])->name('update-quantity');
        });

        // ===========================
        // Checkout
        // ===========================
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

        // ===========================
        // Orders
        // ===========================
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [CustomerOrderController::class, 'index'])->name('index');
            Route::get('/{id}', [CustomerOrderController::class, 'show'])->name('show');
        });

        // ===========================
        // Grooming (Customer side)
        // ===========================
        Route::prefix('grooming')->name('grooming.')->group(function () {
            Route::get('/', [GroomingController::class, 'index'])->name('index');
            Route::get('/booking/{serviceId}', [GroomingController::class, 'bookingForm'])->name('bookingForm');
            Route::post('/booking/{serviceId}', [GroomingController::class, 'book'])->name('book');
            Route::get('/my-bookings', [GroomingController::class, 'myBookings'])->name('my-bookings');
            Route::post('/bookings/{bookingId}/cancel', [GroomingController::class, 'cancelBooking'])->name('cancel');
            Route::post('/my-bookings/{bookingId}/add-to-cart', [GroomingController::class, 'addToCart'])->name('addToCart');
            Route::delete('/bookings/{id}', [GroomingController::class, 'deleteBooking'])->name('deleteBooking');
        });

        // ===========================
        // Transaksi (Detail Pesanan)
        // ===========================
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/{id}', [CustomerTransactionController::class, 'show'])->name('show');
        });
    });

/*
|--------------------------------------------------------------------------
| PAYMENTS (Midtrans)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsCustomer::class])->prefix('payment')->name('payment.')->group(function () {
    Route::get('/{order}', [PaymentController::class, 'checkout'])->name('index');
});

// Callback dari Midtrans (tanpa login)
Route::post('/payment/midtrans/callback', [PaymentCallbackController::class, 'callback'])->name('payment.callback');
Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| DEFAULT AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
