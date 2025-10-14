<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\User;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get today's date range
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        // Get dashboard data
        $dashboardData = $this->getDashboardData($user, $today, $tomorrow);
        
        return view('dashboard.index', compact('dashboardData', 'user'));
    }
    
    /**
     * Get dashboard data from database
     */
    private function getDashboardData($user, $today, $tomorrow)
    {
        // Get product count and low stock alerts
        $totalProducts = Produk::count();
        $lowStockProducts = Produk::where('stok_total', '<=', 5)->count();
        
        // Get real sales data from penjualan table
        $mySalesToday = $this->getMySalesToday($user, $today, $tomorrow);
        $allSalesToday = $this->getAllSalesToday($today, $tomorrow);
        
        return [
            'total_products' => $totalProducts,
            'low_stock_count' => $lowStockProducts,
            'low_stock_products' => $this->getLowStockProducts(),
            'my_sales_today' => $mySalesToday,
            'all_sales_today' => $allSalesToday,
            'recent_transactions' => $this->getRecentTransactions()
        ];
    }
    
    /**
     * Get low stock products
     */
    private function getLowStockProducts()
    {
        return Produk::where('stok_total', '<=', 5)
            ->select(['kd_produk', 'nama_produk', 'stok_total', 'jenis'])
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->kd_produk,
                    'name' => $product->nama_produk,
                    'stock' => $product->stok_total,
                    'category' => $product->jenis ?? 'General'
                ];
            });
    }
    
    /**
     * Get my sales today from database
     */
    private function getMySalesToday($user, $today, $tomorrow)
    {
        $sales = Penjualan::where('dibuat_oleh', $user->name ?? $user->username ?? 'system')
            ->where('status_bayar', 'Lunas')
            ->where('status_barang', 'diterima langsung')
            ->whereBetween('date_created', [$today, $tomorrow])
            ->get();
        
        $totalAmount = $sales->sum('total_harga');
        $transactionCount = $sales->count();
        
        return [
            'amount' => $totalAmount,
            'transactions' => $transactionCount,
            'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.')
        ];
    }
    
    /**
     * Get all sales today from database
     */
    private function getAllSalesToday($today, $tomorrow)
    {
        $sales = Penjualan::where('status_bayar', 'Lunas')
            ->where('status_barang', 'diterima langsung')
            ->whereBetween('date_created', [$today, $tomorrow])
            ->get();
        
        $totalAmount = $sales->sum('total_harga');
        $transactionCount = $sales->count();
        
        return [
            'amount' => $totalAmount,
            'transactions' => $transactionCount,
            'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.')
        ];
    }
    
    /**
     * Get recent transactions from database
     */
    private function getRecentTransactions()
    {
        $transactions = Penjualan::where('status_bayar', 'Lunas')
            ->where('status_barang', 'diterima langsung')
            ->with('pelanggan')
            ->orderBy('date_created', 'desc')
            ->limit(5)
            ->get();
        
        return $transactions->map(function($transaction) {
            return [
                'id' => $transaction->no_faktur_penjualan,
                'amount' => $transaction->total_harga,
                'formatted_amount' => 'Rp ' . number_format($transaction->total_harga, 0, ',', '.'),
                'time' => $transaction->date_created->format('H:i'),
                'cashier' => $transaction->dibuat_oleh ?? 'System',
                'customer' => $transaction->pelanggan ? $transaction->pelanggan->nama_lengkap : 'Pelanggan Umum'
            ];
        });
    }
    
    
}
