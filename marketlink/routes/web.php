<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\FarmerPortalController;
use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AiChatbotController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');

Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
Route::get('/markets/{id}', [MarketController::class, 'show'])->name('markets.show');
Route::get('/map', [MarketController::class, 'map'])->name('map');

Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
Route::get('/farmers/{id}', [FarmerController::class, 'show'])->name('farmers.show');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Pre-Order Basket
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// AI Assistant Chatbot API
Route::post('/api/chat', [AiChatbotController::class, 'chat'])->name('api.chat');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Portal Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['customer'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/place', [OrderController::class, 'store'])->name('orders.store');

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{id}/modify', [OrderController::class, 'modify'])->name('orders.modify');
        Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');

        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

        Route::get('/notifications', [CustomerPortalController::class, 'notifications'])->name('notifications');
        Route::get('/profile', [CustomerPortalController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');
    });

    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Farmer Portal Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [FarmerPortalController::class, 'products'])->name('products');
    Route::post('/products/store', [FarmerPortalController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/{id}/update', [FarmerPortalController::class, 'updateProduct'])->name('products.update');
    Route::post('/products/{id}/delete', [FarmerPortalController::class, 'deleteProduct'])->name('products.delete');
    Route::post('/products/{id}/toggle', [FarmerPortalController::class, 'toggleAvailability'])->name('products.toggle');
    Route::post('/template/apply', [FarmerPortalController::class, 'applyWeeklyTemplate'])->name('template.apply');

    Route::get('/orders', [FarmerPortalController::class, 'orders'])->name('orders');
    Route::post('/orders/{id}/status', [FarmerPortalController::class, 'updateOrderStatus'])->name('orders.status');

    Route::get('/analytics', [FarmerPortalController::class, 'analytics'])->name('analytics');
    Route::get('/reviews', [FarmerPortalController::class, 'reviews'])->name('reviews');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');

    Route::get('/settings', [FarmerPortalController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [FarmerPortalController::class, 'updateSettings'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Admin Portal Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminPortalController::class, 'users'])->name('users');
    Route::post('/users/{id}/approve', [AdminPortalController::class, 'approveFarmer'])->name('users.approve');
    Route::post('/users/{id}/suspend', [AdminPortalController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{id}/activate', [AdminPortalController::class, 'activateUser'])->name('users.activate');

    Route::get('/markets', [AdminPortalController::class, 'markets'])->name('markets');
    Route::post('/markets/store', [AdminPortalController::class, 'storeMarket'])->name('markets.store');
    Route::post('/markets/{id}/update', [AdminPortalController::class, 'updateMarket'])->name('markets.update');
    Route::post('/markets/{id}/delete', [AdminPortalController::class, 'deleteMarket'])->name('markets.delete');

    Route::get('/moderation', [AdminPortalController::class, 'moderation'])->name('moderation');
    Route::post('/moderation/product/{id}/delete', [AdminPortalController::class, 'deleteProduct'])->name('moderation.product.delete');
    Route::post('/moderation/review/{id}/delete', [AdminPortalController::class, 'deleteReview'])->name('moderation.review.delete');

    Route::get('/reports', [AdminPortalController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [AdminPortalController::class, 'generateReport'])->name('reports.generate');

    Route::get('/configuration', [AdminPortalController::class, 'configuration'])->name('configuration');
    Route::post('/categories/store', [AdminPortalController::class, 'storeCategory'])->name('categories.store');
    Route::post('/categories/{id}/update', [AdminPortalController::class, 'updateCategory'])->name('categories.update');
    Route::post('/categories/{id}/delete', [AdminPortalController::class, 'deleteCategory'])->name('categories.delete');
    Route::post('/announcements/store', [AdminPortalController::class, 'storeAnnouncement'])->name('announcements.store');
});
