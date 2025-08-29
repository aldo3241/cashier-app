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
Route::get('/cashier/products', [App\Http\Controllers\CashierController::class, 'getProducts'])->name('cashier.products');
Route::get('/cashier/product-types', [App\Http\Controllers\CashierController::class, 'getProductTypes'])->name('cashier.product-types');

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
