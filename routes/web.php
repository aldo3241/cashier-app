<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cashierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\PenjualanController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes (protected)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Sales Report routes (protected)
    Route::get('/sales/my-sales', [SalesReportController::class, 'mySales'])->name('sales.my-sales');
    Route::get('/sales/all-sales', [SalesReportController::class, 'allSales'])->name('sales.all-sales');
    
    // Cashier routes (protected)
    Route::get('/cashier', [cashierController::class, 'index'])->name('cashier.index');
    
    // Profile routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.update');
    
    // API routes for products (protected)
    Route::prefix('api')->group(function () {
        // Product APIs
        Route::get('/products/search', [ProdukController::class, 'search'])->name('api.products.search');
        Route::get('/products/barcode', [ProdukController::class, 'getByBarcode'])->name('api.products.barcode');
        Route::get('/products/categories', [ProdukController::class, 'getCategories'])->name('api.products.categories');
        Route::post('/products/update-stock', [ProdukController::class, 'updateStock'])->name('api.products.update-stock');
        
        // Customer APIs
        Route::get('/customers/default', [PelangganController::class, 'getDefault'])->name('api.customers.default');
        Route::get('/customers/search', [PelangganController::class, 'search'])->name('api.customers.search');
        Route::get('/customers/get', [PelangganController::class, 'getById'])->name('api.customers.get');
        Route::get('/customers', [PelangganController::class, 'index'])->name('api.customers.index');
        Route::get('/customers/stats', [PelangganController::class, 'stats'])->name('api.customers.stats');
        
        // Sales APIs
        Route::post('/sales/create', [PenjualanController::class, 'createSale'])->name('api.sales.create');
        Route::get('/sales', [PenjualanController::class, 'index'])->name('api.sales.index');
        Route::get('/sales/{id}', [PenjualanController::class, 'show'])->name('api.sales.show');
        Route::get('/sales/payment-methods', [PenjualanController::class, 'getPaymentMethods'])->name('api.sales.payment-methods');
        Route::get('/sales/stats', [PenjualanController::class, 'getStats'])->name('api.sales.stats');
    });
});
