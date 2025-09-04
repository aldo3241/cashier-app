<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/home');
    }
    return view('auth.login');
});

Auth::routes();



// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    // Admin and Cashier can access home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {

    });

    // Cashier routes (accessible by both admin and cashier)
    Route::get('/cashier', [App\Http\Controllers\CashierController::class, 'index'])->name('cashier');
    Route::post('/cashier/scan', [App\Http\Controllers\CashierController::class, 'scanProduct'])->name('cashier.scan');
    Route::post('/cashier/transaction', [App\Http\Controllers\CashierController::class, 'processTransaction'])->name('cashier.transaction');
    Route::post('/cashier/pending-sale', [App\Http\Controllers\CashierController::class, 'createPendingSale'])->name('cashier.pending-sale');
    Route::post('/cashier/add-item', [App\Http\Controllers\CashierController::class, 'addItemToSale'])->name('cashier.add-item');
    Route::post('/cashier/complete-sale', [App\Http\Controllers\CashierController::class, 'completeSale'])->name('cashier.complete-sale');
    Route::post('/cashier/adjust-stock', [App\Http\Controllers\CashierController::class, 'adjustStock'])->name('cashier.adjust-stock');
    Route::get('/cashier/stock-history/{productId}', [App\Http\Controllers\CashierController::class, 'getStockHistory'])->name('cashier.stock-history');
    Route::get('/cashier/products', [App\Http\Controllers\CashierController::class, 'getProducts'])->name('cashier.products');
    Route::get('/cashier/product-types', [App\Http\Controllers\CashierController::class, 'getProductTypes'])->name('cashier.product-types');

    // Penjualan Detail Management Routes (Admin and Cashier access)
    Route::middleware(['role:admin,cashier'])->group(function () {
        Route::get('/cashier/sale/{saleId}/details', [App\Http\Controllers\CashierController::class, 'getSaleDetails'])->name('cashier.sale.details');
        Route::get('/cashier/sales-with-details', [App\Http\Controllers\CashierController::class, 'getAllSalesWithDetails'])->name('cashier.sales.with-details');
        Route::get('/sale-details', function() {
            return view('sales.sale-details');
        })->name('sale-details');
    });

    // Admin-only management routes
    Route::middleware(['role:admin'])->group(function () {
        Route::put('/cashier/sale-item/{detailId}', [App\Http\Controllers\CashierController::class, 'updateSaleItem'])->name('cashier.sale-item.update');
        Route::delete('/cashier/sale-item/{detailId}', [App\Http\Controllers\CashierController::class, 'removeSaleItem'])->name('cashier.sale-item.remove');

        // Payment Methods Management Routes
        Route::get('/payment-methods', [App\Http\Controllers\KeuanganKotakController::class, 'index'])->name('payment-methods.index');
        Route::get('/payment-methods/all', [App\Http\Controllers\KeuanganKotakController::class, 'getAllPaymentMethods'])->name('payment-methods.all');
        Route::post('/payment-methods', [App\Http\Controllers\KeuanganKotakController::class, 'store'])->name('payment-methods.store');
        Route::get('/payment-methods/{id}', [App\Http\Controllers\KeuanganKotakController::class, 'show'])->name('payment-methods.show');
        Route::put('/payment-methods/{id}', [App\Http\Controllers\KeuanganKotakController::class, 'update'])->name('payment-methods.update');
        Route::delete('/payment-methods/{id}', [App\Http\Controllers\KeuanganKotakController::class, 'destroy'])->name('payment-methods.destroy');
        Route::get('/payment-methods/dropdown/all', [App\Http\Controllers\KeuanganKotakController::class, 'getAllForDropdown'])->name('payment-methods.dropdown');

        // Product routes
        Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/api/products', [App\Http\Controllers\ProductController::class, 'getProductData'])->name('products.api');

        // User Management routes
        Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}/change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change-password');
        Route::patch('/users/{user}/update-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('users.update-password');

        // Role Management routes
        Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [App\Http\Controllers\RoleController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Dashboard API routes (accessible by both admin and cashier)
    Route::get('/api/dashboard/stats', [App\Http\Controllers\HomeController::class, 'getDashboardStats'])->name('api.dashboard.stats');
    
    // Sales API routes (accessible by both admin and cashier)
    Route::middleware(['role:admin,cashier'])->group(function () {
        Route::get('/api/sales/stats', [App\Http\Controllers\SalesController::class, 'getStats']);
        Route::get('/api/sales', [App\Http\Controllers\SalesController::class, 'getSales']);
        Route::get('/api/sales/{id}', [App\Http\Controllers\SalesController::class, 'getSaleDetails']);
        Route::get('/api/sales/export', [App\Http\Controllers\SalesController::class, 'exportSales']);
    });
    
    // Admin-only sales management
    Route::middleware(['role:admin'])->group(function () {
        Route::put('/api/sale-items/{id}', [App\Http\Controllers\SalesController::class, 'updateSaleItem']);
        Route::delete('/api/sale-items/{id}', [App\Http\Controllers\SalesController::class, 'deleteSaleItem']);

        // API Routes for Payment Methods
        Route::get('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'getPaymentMethods']);
        Route::get('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'getPaymentMethod']);
        Route::post('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'store']);
        Route::put('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'update']);
        Route::delete('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'destroy']);
    });
});
