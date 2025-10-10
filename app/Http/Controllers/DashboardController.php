<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\User;
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
     * Get dashboard data without modifying database
     * We'll simulate sales data since we can't modify DB
     */
    private function getDashboardData($user, $today, $tomorrow)
    {
        // Get product count and low stock alerts
        $totalProducts = Produk::count();
        $lowStockProducts = Produk::where('stok_total', '<=', 5)->count();
        
        
        // Simulate sales data (since we can't modify DB)
        // In a real scenario, you'd have a sales/transactions table
        $mySalesToday = $this->getSimulatedSales($user->kd, $today);
        $allSalesToday = $this->getSimulatedAllSales($today);
        
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
     * Simulate sales data (replace with real data when available)
     */
    private function getSimulatedSales($userId, $date)
    {
        // This is simulation - in real app, you'd query sales table
        $baseAmount = rand(200000, 800000);
        $transactionCount = rand(5, 15);
        
        return [
            'amount' => $baseAmount,
            'transactions' => $transactionCount,
            'formatted_amount' => 'Rp ' . number_format($baseAmount, 0, ',', '.')
        ];
    }
    
    private function getSimulatedAllSales($date)
    {
        // Simulate all cashiers' sales
        $baseAmount = rand(800000, 2000000);
        $transactionCount = rand(20, 50);
        
        return [
            'amount' => $baseAmount,
            'transactions' => $transactionCount,
            'formatted_amount' => 'Rp ' . number_format($baseAmount, 0, ',', '.')
        ];
    }
    
    /**
     * Get recent transactions (simulated)
     */
    private function getRecentTransactions()
    {
        // Simulate recent transactions
        $transactions = [];
        for ($i = 0; $i < 5; $i++) {
            $transactions[] = [
                'id' => 'TXN' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'amount' => rand(50000, 300000),
                'time' => Carbon::now()->subMinutes(rand(5, 120))->format('H:i'),
                'cashier' => ['John', 'Sarah', 'Mike', 'Lisa'][rand(0, 3)]
            ];
        }
        
        return collect($transactions)->map(function($txn) {
            return [
                'id' => $txn['id'],
                'amount' => $txn['amount'],
                'formatted_amount' => 'Rp ' . number_format($txn['amount'], 0, ',', '.'),
                'time' => $txn['time'],
                'cashier' => $txn['cashier']
            ];
        });
    }
    
    
}
