<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

// Public routes
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/menu', function () {
    $coffeeMenus = \App\Models\Menu::active()->coffee()->get();
    $nonCoffeeMenus = \App\Models\Menu::active()->nonCoffee()->get();
    return view('menu', compact('coffeeMenus', 'nonCoffeeMenus'));
})->name('menu');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// 🔴 REALTIME: Broadcasting authentication routes
Broadcast::routes(['middleware' => ['auth']]);

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Checkout page (requires auth)
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');

    // 🔴 REALTIME: Test Echo page
    Route::get('/test-echo', function () {
        return view('test-echo');
    })->name('test.echo');

    // 🔴 REALTIME: Simple test page
    Route::get('/test-simple', function () {
        return view('test-simple');
    })->name('test.simple');

    // Order routes
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order-status', [OrderController::class, 'status'])->name('order.status');
    Route::get('/order-history', [OrderController::class, 'history'])->name('order.history');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('dashboard');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/estimated-time', [AdminOrderController::class, 'updateEstimatedTime'])->name('orders.updateEstimatedTime');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Menu management routes
    Route::resource('menus', AdminMenuController::class);
});

require __DIR__.'/auth.php';
