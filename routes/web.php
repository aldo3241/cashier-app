<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');

// Cashier routes
Route::get('/cashier', [App\Http\Controllers\CashierController::class, 'index'])->name('cashier');
Route::post('/cashier/scan', [App\Http\Controllers\CashierController::class, 'scanProduct'])->name('cashier.scan');
Route::post('/cashier/transaction', [App\Http\Controllers\CashierController::class, 'processTransaction'])->name('cashier.transaction');
Route::post('/cashier/pending-sale', [App\Http\Controllers\CashierController::class, 'createPendingSale'])->name('cashier.pending-sale');
Route::post('/cashier/add-item', [App\Http\Controllers\CashierController::class, 'addItemToSale'])->name('cashier.add-item');
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

// Test route to verify akun table connection
Route::get('/test-users', function () {
    $users = User::all();
    return response()->json([
        'count' => $users->count(),
        'users' => $users->map(function($user) {
            return [
                'id' => $user->kd,
                'name' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role
            ];
        })
    ]);
});

// Test route to check products
Route::get('/test-products', function () {
    try {
        $products = \App\Models\Product::take(5)->get();
        return response()->json([
            'count' => $products->count(),
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->kd_produk,
                    'name' => $product->nama_produk,
                    'price' => $product->harga_jual,
                    'stock' => $product->stok_total
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Product routes
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/api/products', [App\Http\Controllers\ProductController::class, 'getProductData'])->name('products.api');
