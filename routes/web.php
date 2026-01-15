<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cashierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\ReceiptController;
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
    Route::get('/sales/period', [SalesReportController::class, 'periodSales'])->name('sales.period');
    Route::get('/sales/transaction/{id}', [SalesReportController::class, 'transactionDetails'])->name('sales.transaction-details');
    
    // Receipt routes (protected)
    Route::get('/receipt/print/{id}', [ReceiptController::class, 'print'])->name('receipt.print');
    Route::get('/receipt/print-invoice/{invoiceNumber}', [ReceiptController::class, 'printByInvoice'])->name('receipt.print-invoice');
    Route::get('/receipt/print-latest', [ReceiptController::class, 'printLatest'])->name('receipt.print-latest');
    
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
        Route::get('/sales/payment-methods', [PenjualanController::class, 'getPaymentMethods'])->name('api.sales.payment-methods');
        Route::get('/sales/stats', [PenjualanController::class, 'getStats'])->name('api.sales.stats');
        
        // Sales Report DataTable APIs (must be before /sales/{id} route)
        Route::get('/sales/my-sales-data', [SalesReportController::class, 'mySalesData'])->name('api.sales.my-sales-data');
        Route::get('/sales/all-sales-data', [SalesReportController::class, 'allSalesData'])->name('api.sales.all-sales-data');
        Route::get('/sales/period-data', [SalesReportController::class, 'periodSalesData'])->name('api.sales.period-data');
        Route::get('/sales/period-analytics', [SalesReportController::class, 'periodAnalytics'])->name('api.sales.period-analytics');
        Route::get('/sales/period/create', [SalesReportController::class, 'create'])->name('sales.period-create');
        Route::post('/sales/period', [SalesReportController::class, 'store'])->name('sales.period-store');
        
        Route::get('/sales/period/{id}/edit', [SalesReportController::class, 'edit'])->name('sales.period-edit');
        Route::put('/sales/period/{id}', [SalesReportController::class, 'update'])->name('sales.period-update');

        Route::get('/sales/period/{id}', [SalesReportController::class, 'periodSalesDetail'])->name('sales.period-detail');
        Route::delete('/sales/period/{id}', [SalesReportController::class, 'destroy'])->name('sales.period-delete');
        
        // Generic sales route (must be last)
        Route::get('/sales/{id}', [PenjualanController::class, 'show'])->name('api.sales.show');
        
        // Payment Methods API
        Route::get('/payment-methods', [PenjualanController::class, 'getPaymentMethods'])->name('api.payment-methods');
        
        // Cart APIs (Real-time cart system)
        Route::get('/cart', [App\Http\Controllers\Api\CartController::class, 'getCart'])->name('api.cart.get');
        Route::post('/cart/add', [App\Http\Controllers\Api\CartController::class, 'addToCart'])->name('api.cart.add');
        Route::put('/cart/update', [App\Http\Controllers\Api\CartController::class, 'updateCartItem'])->name('api.cart.update');
        Route::delete('/cart/remove', [App\Http\Controllers\Api\CartController::class, 'removeFromCart'])->name('api.cart.remove');
        Route::post('/cart/clear', [App\Http\Controllers\Api\CartController::class, 'clearCart'])->name('api.cart.clear');
        Route::post('/cart/checkout', [App\Http\Controllers\Api\CartController::class, 'checkout'])->name('api.cart.checkout');
        Route::get('/cart/stats', [App\Http\Controllers\Api\CartController::class, 'getStats'])->name('api.cart.stats');
        Route::post('/cart/fresh', [App\Http\Controllers\Api\CartController::class, 'createFreshTransaction'])->name('api.cart.fresh');
        
        // Draft transaction management
        Route::get('/cart/drafts', [App\Http\Controllers\Api\CartController::class, 'getDraftTransactions'])->name('api.cart.drafts');
        Route::post('/cart/switch-draft', [App\Http\Controllers\Api\CartController::class, 'switchToDraft'])->name('api.cart.switch-draft');
        Route::delete('/cart/delete-draft', [App\Http\Controllers\Api\CartController::class, 'deleteDraft'])->name('api.cart.delete-draft');
        
        // Debug route
        Route::get('/cart/debug', function() {
            return response()->json([
                'success' => true,
                'message' => 'Cart API is working',
                'user' => auth()->user() ? auth()->user()->name : 'Not authenticated',
                'timestamp' => now()
            ]);
        })->name('api.cart.debug');
        
        // Debug draft transactions
        Route::get('/cart/debug-drafts', function() {
            $userId = auth()->user()->name ?? 'system';
            $drafts = \App\Models\Penjualan::where('dibuat_oleh', $userId)
                ->where('status_bayar', 'Belum Lunas')
                ->where('status_barang', 'Draft')
                ->get();
            
            return response()->json([
                'success' => true,
                'user' => $userId,
                'total_drafts' => $drafts->count(),
                'drafts' => $drafts->map(function($draft) {
                    return [
                        'id' => $draft->kd_penjualan,
                        'invoice' => $draft->no_faktur_penjualan,
                        'customer' => $draft->kd_pelanggan,
                        'status_bayar' => $draft->status_bayar,
                        'status_barang' => $draft->status_barang,
                        'created' => $draft->date_created
                    ];
                })
            ]);
        })->name('api.cart.debug-drafts');
        
        // Continue transaction
        Route::get('/cart/continue/{transactionId}', [App\Http\Controllers\Api\CartController::class, 'continueTransaction'])->name('api.cart.continue');
        
        // Financial mutation details
        Route::get('/keuangan/{id}', [App\Http\Controllers\Api\KeuanganController::class, 'show'])->name('api.keuangan.show');
        Route::get('/keuangan/by-invoice/{invoiceNumber}', [App\Http\Controllers\Api\KeuanganController::class, 'getByInvoice'])->name('api.keuangan.by-invoice');
        
        // Stock mutation details
        Route::get('/stock/by-invoice/{invoiceNumber}', [App\Http\Controllers\Api\StokController::class, 'getByInvoice'])->name('api.stock.by-invoice');
    });
});
