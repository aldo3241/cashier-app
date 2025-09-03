<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Get dashboard statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        try {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            
            // Today's sales
            $todaySales = DB::table('penjualan')
                ->whereDate('date_created', $today)
                ->where('status_bayar', 'Lunas')
                ->sum('total_harga');
            
            // Yesterday's sales for comparison
            $yesterdaySales = DB::table('penjualan')
                ->whereDate('date_created', $yesterday)
                ->where('status_bayar', 'Lunas')
                ->sum('total_harga');
            
            // Calculate growth percentage
            $salesGrowth = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;
            
            // Today's transactions
            $todayTransactions = DB::table('penjualan')
                ->whereDate('date_created', $today)
                ->where('status_bayar', 'Lunas')
                ->count();
            
            // Average transactions per hour (assuming 8-hour workday)
            $avgPerHour = $todayTransactions > 0 ? round($todayTransactions / 8, 1) : 0;
            
            // Total products
            $totalProducts = DB::table('produk')->count();
            
            // Low stock products (less than 10 items)
            $lowStockCount = DB::table('produk')
                ->where('stok_total', '<', 10)
                ->where('stok_total', '>', 0)
                ->count();
            
            // Pending sales
            $pendingSales = DB::table('penjualan')
                ->where('status_bayar', 'Pending')
                ->count();
            
            // Urgent sales (pending for more than 1 hour)
            $urgentSales = DB::table('penjualan')
                ->where('status_bayar', 'Pending')
                ->where('date_created', '<', Carbon::now()->subHour())
                ->count();
            
            // Recent transactions (last 5 completed sales)
            $recentTransactions = DB::table('penjualan')
                ->where('status_bayar', 'Lunas')
                ->orderBy('date_created', 'desc')
                ->limit(5)
                ->get()
                ->map(function($sale) {
                    return [
                        'invoice' => $sale->no_faktur_penjualan,
                        'customer' => $sale->nama_pelanggan ?: 'Walk-in Customer',
                        'amount' => $sale->total_harga,
                        'status' => 'completed',
                        'time' => Carbon::parse($sale->date_created)->diffForHumans()
                    ];
                });
            
            // Alerts
            $alerts = [];
            
            // Low stock alerts
            $lowStockProducts = DB::table('produk')
                ->where('stok_total', '<', 5)
                ->where('stok_total', '>', 0)
                ->limit(3)
                ->get();
            
            foreach ($lowStockProducts as $product) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Low stock alert',
                    'message' => "Product \"{$product->nama_produk}\" is running low ({$product->stok_total} items left)",
                    'time' => Carbon::now()->subMinutes(rand(10, 60))->diffForHumans()
                ];
            }
            
            // Recent sale alerts
            if ($recentTransactions->count() > 0) {
                $latestSale = $recentTransactions->first();
                $alerts[] = [
                    'type' => 'success',
                    'title' => 'New sale completed',
                    'message' => "Invoice {$latestSale['invoice']}",
                    'time' => $latestSale['time']
                ];
            }
            
            // Pending sales alerts
            if ($urgentSales > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'Urgent attention needed',
                    'message' => "{$urgentSales} pending sales require immediate attention",
                    'time' => Carbon::now()->subMinutes(rand(5, 30))->diffForHumans()
                ];
            }
            
            return response()->json([
                'todaySales' => $todaySales,
                'salesGrowth' => round($salesGrowth, 1),
                'todayTransactions' => $todayTransactions,
                'avgPerHour' => $avgPerHour,
                'totalProducts' => $totalProducts,
                'lowStockCount' => $lowStockCount,
                'pendingSales' => $pendingSales,
                'urgentSales' => $urgentSales,
                'recentTransactions' => $recentTransactions,
                'alerts' => $alerts
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }
}
