<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientPanelController;
use App\Http\Controllers\FlutterwaveWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [HomeController::class, 'menu'])->name('menu');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/privacy-policy', [InfoPageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-of-service', [InfoPageController::class, 'terms'])->name('pages.terms');
Route::get('/refund-order-policy', [InfoPageController::class, 'refunds'])->name('pages.refunds');
Route::get('/operating-hours', [InfoPageController::class, 'hours'])->name('pages.hours');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/payments/flutterwave/callback', [CheckoutController::class, 'flutterwaveCallback'])->name('payments.flutterwave.callback');
Route::post('/payments/flutterwave/webhook', FlutterwaveWebhookController::class)->name('payments.flutterwave.webhook');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:4,1')->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/client/orders/{order}/flutterwave', [CheckoutController::class, 'restartFlutterwavePayment'])->name('client.orders.flutterwave');
    Route::get('/client', [ClientPanelController::class, 'index'])->name('client.dashboard');
    Route::get('/client/orders/{order}', [ClientPanelController::class, 'show'])->name('client.orders.show');
    Route::get('/client/orders/{order}/receipt', [ClientPanelController::class, 'receipt'])->name('client.orders.receipt');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::match(['put', 'patch'], '/settings', [SettingController::class, 'update'])->name('settings.update');
});
