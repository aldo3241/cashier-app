<?php

namespace App\Services;

use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats($user)
    {
        return Cache::remember("dashboard_stats_{$user->kd}", 300, function() use ($user) {
            return [
                'inventory' => $this->getInventoryStats(),
                'sales' => $this->getSalesStats($user),
                'alerts' => $this->getAlertStats(),
                'performance' => $this->getPerformanceStats($user)
            ];
        });
    }
    
    /**
     * Get inventory statistics
     */
    private function getInventoryStats()
    {
        $totalProducts = Produk::count();
        $inStockProducts = Produk::inStock()->count();
        $lowStockProducts = Produk::where('stok_total', '<=', 5)->count();
        $outOfStockProducts = Produk::where('stok_total', '<=', 0)->count();
        
        return [
            'total' => $totalProducts,
            'in_stock' => $inStockProducts,
            'low_stock' => $lowStockProducts,
            'out_of_stock' => $outOfStockProducts,
            'stock_percentage' => $totalProducts > 0 ? round(($inStockProducts / $totalProducts) * 100, 1) : 0
        ];
    }
    
    /**
     * Get sales statistics (simulated)
     */
    private function getSalesStats($user)
    {
        $today = Carbon::today();
        
        // Simulate sales data - in real app, query sales table
        $mySales = $this->simulateUserSales($user->kd, $today);
        $allSales = $this->simulateAllSales($today);
        
        return [
            'my_today' => $mySales,
            'all_today' => $allSales,
            'growth' => $this->calculateGrowth($mySales, $allSales)
        ];
    }
    
    /**
     * Get alert statistics
     */
    private function getAlertStats()
    {
        $lowStockCount = Produk::where('stok_total', '<=', 5)->count();
        $outOfStockCount = Produk::where('stok_total', '<=', 0)->count();
        
        return [
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
            'total_alerts' => $lowStockCount + $outOfStockCount
        ];
    }
    
    /**
     * Get performance statistics
     */
    private function getPerformanceStats($user)
    {
        // Simulate performance metrics
        return [
            'transactions_today' => rand(8, 25),
            'average_transaction' => rand(75000, 150000),
            'top_selling_category' => $this->getTopCategory(),
            'efficiency_score' => rand(85, 98)
        ];
    }
    
    /**
     * Get top selling category
     */
    private function getTopCategory()
    {
        $categories = Produk::select('jenis')
            ->whereNotNull('jenis')
            ->where('jenis', '!=', '')
            ->groupBy('jenis')
            ->orderByRaw('COUNT(*) DESC')
            ->first();
            
        return $categories ? $categories->jenis : 'General';
    }
    
    /**
     * Simulate user sales data
     */
    private function simulateUserSales($userId, $date)
    {
        // Use user ID as seed for consistent "random" data
        srand(crc32($userId . $date->format('Y-m-d')));
        
        $baseAmount = rand(150000, 600000);
        $transactionCount = rand(5, 18);
        
        return [
            'amount' => $baseAmount,
            'transactions' => $transactionCount,
            'formatted_amount' => 'Rp ' . number_format($baseAmount, 0, ',', '.'),
            'average_transaction' => $transactionCount > 0 ? round($baseAmount / $transactionCount) : 0
        ];
    }
    
    /**
     * Simulate all sales data
     */
    private function simulateAllSales($date)
    {
        srand(crc32('all_sales_' . $date->format('Y-m-d')));
        
        $baseAmount = rand(500000, 1500000);
        $transactionCount = rand(20, 60);
        
        return [
            'amount' => $baseAmount,
            'transactions' => $transactionCount,
            'formatted_amount' => 'Rp ' . number_format($baseAmount, 0, ',', '.'),
            'average_transaction' => $transactionCount > 0 ? round($baseAmount / $transactionCount) : 0
        ];
    }
    
    /**
     * Calculate growth percentage
     */
    private function calculateGrowth($mySales, $allSales)
    {
        if ($allSales['amount'] == 0) return 0;
        
        $percentage = ($mySales['amount'] / $allSales['amount']) * 100;
        return round($percentage, 1);
    }
    
    
    /**
     * Get product image URL
     */
    private function getProductImageUrl($image)
    {
        if ($image && file_exists(public_path('images/products/' . $image))) {
            return asset('images/products/' . $image);
        }
        return asset('images/no-image.png');
    }
    
    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts($limit = 5)
    {
        return Produk::where('stok_total', '<=', 5)
            ->where('stok_total', '>', 0)
            ->select([
                'kd_produk as id',
                'nama_produk as name',
                'stok_total as stock',
                'jenis as category',
                'harga_jual as price'
            ])
            ->orderBy('stok_total', 'asc')
            ->limit($limit)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'category' => $product->category ?? 'General',
                    'price' => (float) $product->price,
                    'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'alert_level' => $product->stock <= 2 ? 'critical' : 'warning'
                ];
            });
    }
    
    /**
     * Clear dashboard cache
     */
    public function clearCache($user = null)
    {
        if ($user) {
            Cache::forget("dashboard_stats_{$user->kd}");
        } else {
            Cache::flush();
        }
    }
}
