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
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');

    // Cashier routes
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

    // Penjualan Detail Management Routes
    Route::get('/cashier/sale/{saleId}/details', [App\Http\Controllers\CashierController::class, 'getSaleDetails'])->name('cashier.sale.details');
    Route::put('/cashier/sale-item/{detailId}', [App\Http\Controllers\CashierController::class, 'updateSaleItem'])->name('cashier.sale-item.update');
    Route::delete('/cashier/sale-item/{detailId}', [App\Http\Controllers\CashierController::class, 'removeSaleItem'])->name('cashier.sale-item.remove');
    Route::get('/cashier/sales-with-details', [App\Http\Controllers\CashierController::class, 'getAllSalesWithDetails'])->name('cashier.sales.with-details');
    Route::get('/sale-details', function() {
        return view('sales.sale-details');
    })->name('sale-details');

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

    // Dashboard API routes
    Route::get('/api/dashboard/stats', [App\Http\Controllers\HomeController::class, 'getDashboardStats'])->name('api.dashboard.stats');

    // API Routes for Sales
    Route::get('/api/sales', [App\Http\Controllers\SalesController::class, 'getSales']);
    Route::get('/api/sales/{id}', [App\Http\Controllers\SalesController::class, 'getSaleDetails']);
    Route::put('/api/sale-items/{id}', [App\Http\Controllers\SalesController::class, 'updateSaleItem']);
    Route::delete('/api/sale-items/{id}', [App\Http\Controllers\SalesController::class, 'deleteSaleItem']);
    Route::get('/api/sales/export', [App\Http\Controllers\SalesController::class, 'exportSales']);

    // API Routes for Payment Methods
    Route::get('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'getPaymentMethods']);
    Route::get('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'getPaymentMethod']);
    Route::post('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'store']);
    Route::put('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'update']);
    Route::delete('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'destroy']);
});
