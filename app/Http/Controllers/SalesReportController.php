<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    /**
     * Show my sales report
     */
    public function mySales()
    {
        $user = auth()->user();

        // Calculate stats for the dashboard
        $stats = $this->calculateMySalesStats($user->name ?? $user->username);

        return view('sales.my-sales', compact('stats', 'user'));
    }

    /**
     * Show all cashiers sales report
     */
    public function allSales(Request $request)
    {
        $user = auth()->user();

        // Calculate stats for the dashboard
        $stats = $this->calculateAllSalesStats();

        return view('sales.all-sales', compact('stats', 'user'));
    }

    /**
     * Get my sales data (both completed and incomplete)
     */
    private function getMySalesData($userId)
    {
        $query = Penjualan::with(['penjualanDetails.produk', 'pelanggan'])
            ->where('dibuat_oleh', $userId)
            ->whereIn('status_bayar', ['Lunas', 'Belum Lunas']); // Include both completed and incomplete

        $sales = $query->orderBy('date_created', 'desc')->get();

        return $sales->map(function ($sale) {
            $itemCount = $sale->penjualanDetails->sum('qty');
            $totalAmount = $sale->penjualanDetails->sum(function ($item) {
                return ($item->harga_jual * $item->qty) - $item->diskon;
            });

            // Get financial mutation ID for completed sales
            $keuanganId = null;
            if ($sale->status_bayar === 'Lunas' && $sale->no_faktur_penjualan) {
                $keuangan = \App\Models\Keuangan::where('referensi', $sale->no_faktur_penjualan)->first();
                $keuanganId = $keuangan ? $keuangan->kd_keuangan : null;
            }

            return [
                'id' => $sale->kd_penjualan,
                'date' => $sale->date_created->format('Y-m-d'),
                'time' => $sale->date_created->format('H:i:s'),
                'datetime' => $sale->date_created->format('Y-m-d H:i:s'),
                'cashier' => $sale->dibuat_oleh,
                'items' => $itemCount,
                'amount' => $totalAmount,
                'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'payment_method' => $sale->keuangan_kotak ?? 'Tunai',
                'status' => $sale->status_bayar === 'Lunas' ? 'Completed' : 'Belum Lunas',
                'status_bayar' => $sale->status_bayar,
                'customer' => $sale->pelanggan ? $sale->pelanggan->nama_lengkap : 'Walk-in Customer',
                'invoice_number' => $sale->no_faktur_penjualan,
                'keuangan_id' => $keuanganId
            ];
        });
    }

    /**
     * Get real sales data from database
     */
    private function getRealSalesData($userId = null, $type = 'my')
    {
        $query = Penjualan::with(['penjualanDetails.produk', 'pelanggan'])
            ->where('status_bayar', 'Lunas') // Only completed sales
            ->where('status_barang', 'diterima langsung'); // Only completed deliveries

        // Filter by user for "my sales"
        if ($type === 'my' && $userId) {
            $query->where('dibuat_oleh', $userId);
        }

        $sales = $query->orderBy('date_created', 'desc')->get();

        return $sales->map(function ($sale) {
            $itemCount = $sale->penjualanDetails->sum('qty');
            $totalAmount = $sale->penjualanDetails->sum(function ($item) {
                return ($item->harga_jual * $item->qty) - $item->diskon;
            });

            return [
                'id' => $sale->kd_penjualan,
                'date' => $sale->date_created->format('Y-m-d'),
                'time' => $sale->date_created->format('H:i:s'),
                'datetime' => $sale->date_created->format('Y-m-d H:i:s'),
                'cashier' => $sale->dibuat_oleh,
                'items' => $itemCount,
                'amount' => $totalAmount,
                'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'payment_method' => $sale->keuangan_kotak ?? 'Tunai',
                'status' => $sale->status_bayar === 'Lunas' ? 'Completed' : 'Pending',
                'customer' => $sale->pelanggan ? $sale->pelanggan->nama_lengkap : 'Walk-in Customer'
            ];
        });
    }

    /**
     * Get real sales data from database with pagination for better performance
     */
    private function getRealSalesDataPaginated($userId = null, $type = 'all', $perPage = 50, $page = 1)
    {
        $query = Penjualan::with(['penjualanDetails', 'pelanggan'])
            ->whereIn('status_bayar', ['Lunas', 'Belum Lunas']); // Include both completed and incomplete sales

        // Filter by user for "my sales"
        if ($type === 'my' && $userId) {
            $query->where('dibuat_oleh', $userId);
        }

        // Get paginated results
        $sales = $query->orderBy('date_created', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform the data
        $transformedSales = $sales->getCollection()->map(function ($sale) {
            $itemCount = $sale->penjualanDetails->sum('qty');
            $totalAmount = $sale->penjualanDetails->sum(function ($item) {
                return ($item->harga_jual * $item->qty) - $item->diskon;
            });

            // Get financial mutation ID for completed sales
            $keuanganId = null;
            if ($sale->status_bayar === 'Lunas' && $sale->no_faktur_penjualan) {
                $keuangan = \App\Models\Keuangan::where('referensi', $sale->no_faktur_penjualan)->first();
                $keuanganId = $keuangan ? $keuangan->kd_keuangan : null;
            }

            return [
                'id' => $sale->kd_penjualan,
                'date' => $sale->date_created->format('Y-m-d'),
                'time' => $sale->date_created->format('H:i:s'),
                'datetime' => $sale->date_created->format('Y-m-d H:i:s'),
                'cashier' => $sale->dibuat_oleh,
                'items' => $itemCount,
                'amount' => $totalAmount,
                'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'payment_method' => $sale->keuangan_kotak ?? 'Tunai',
                'status' => $sale->status_bayar === 'Lunas' ? 'Completed' : 'Belum Lunas',
                'status_bayar' => $sale->status_bayar,
                'customer' => $sale->pelanggan ? $sale->pelanggan->nama_lengkap : 'Walk-in Customer',
                'invoice_number' => $sale->no_faktur_penjualan,
                'keuangan_id' => $keuanganId
            ];
        });

        // Return paginated collection
        return $sales->setCollection($transformedSales);
    }

    /**
     * Show transaction details page
     */
    public function transactionDetails($id)
    {
        $user = auth()->user();

        // Get the transaction
        $transaction = Penjualan::with(['penjualanDetails.produk', 'pelanggan'])
            ->where('kd_penjualan', $id)
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Get financial mutation
        $keuangan = \App\Models\Keuangan::where('referensi', $transaction->no_faktur_penjualan)->first();

        // Get stock mutations
        $stokMutations = \App\Models\Stok::with('produk')
            ->where('no_ref', $transaction->no_faktur_penjualan)
            ->orderBy('date_created', 'desc')
            ->get();

        return view('sales.transaction-details', compact('transaction', 'keuangan', 'stokMutations', 'user'));
    }

    /**
     * Clear sales stats cache (call this when transactions are updated)
     */
    public static function clearStatsCache($userId = null)
    {
        if ($userId) {
            cache()->forget("my_sales_stats_{$userId}");
        }
        cache()->forget("all_sales_stats");
    }

    /**
     * API endpoint for My Sales DataTable server-side processing
     */
    public function mySalesData(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userId = $user->name ?? $user->username;

        $query = Penjualan::with(['penjualanDetails', 'pelanggan'])
            ->where('dibuat_oleh', $userId)
            ->whereIn('status_bayar', ['Lunas', 'Belum Lunas']);

        // Apply search filter
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('no_faktur_penjualan', 'like', "%{$searchValue}%")
                  ->orWhere('dibuat_oleh', 'like', "%{$searchValue}%")
                  ->orWhere('keuangan_kotak', 'like', "%{$searchValue}%")
                  ->orWhereHas('pelanggan', function($subQ) use ($searchValue) {
                      $subQ->where('nama_lengkap', 'like', "%{$searchValue}%");
                  });
            });
        }

        // Get total count before pagination (more efficient)
        $totalRecords = $query->count();

        // Apply pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 25;

        $sales = $query->orderBy('date_created', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        $data = $sales->map(function ($sale) {
            $itemCount = $sale->penjualanDetails->sum('qty');
            $totalAmount = $sale->penjualanDetails->sum(function ($item) {
                return ($item->harga_jual * $item->qty) - $item->diskon;
            });

            return [
                'id' => $sale->kd_penjualan,
                'invoice_number' => $sale->no_faktur_penjualan,
                'date' => $sale->date_created->format('Y-m-d'),
                'time' => $sale->date_created->format('H:i:s'),
                'items' => $itemCount,
                'amount' => $totalAmount,
                'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'payment_method' => $sale->keuangan_kotak ?? 'Tunai',
                'customer' => $sale->pelanggan ? $sale->pelanggan->nama_lengkap : 'Walk-in Customer',
                'status' => $sale->status_bayar === 'Lunas' ? 'Completed' : 'Belum Lunas',
                'status_bayar' => $sale->status_bayar
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * API endpoint for All Sales DataTable server-side processing
     */
    public function allSalesData(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = Penjualan::with(['penjualanDetails', 'pelanggan'])
            ->whereIn('status_bayar', ['Lunas', 'Belum Lunas']);

        // Apply search filter
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('no_faktur_penjualan', 'like', "%{$searchValue}%")
                  ->orWhere('dibuat_oleh', 'like', "%{$searchValue}%")
                  ->orWhere('keuangan_kotak', 'like', "%{$searchValue}%")
                  ->orWhereHas('pelanggan', function($subQ) use ($searchValue) {
                      $subQ->where('nama_lengkap', 'like', "%{$searchValue}%");
                  });
            });
        }

        // Get total count before pagination (more efficient)
        $totalRecords = $query->count();

        // Apply pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 50;

        $sales = $query->orderBy('date_created', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        $data = $sales->map(function ($sale) {
            $itemCount = $sale->penjualanDetails->sum('qty');
            $totalAmount = $sale->penjualanDetails->sum(function ($item) {
                return ($item->harga_jual * $item->qty) - $item->diskon;
            });

            return [
                'id' => $sale->kd_penjualan,
                'invoice_number' => $sale->no_faktur_penjualan,
                'date' => $sale->date_created->format('Y-m-d'),
                'time' => $sale->date_created->format('H:i:s'),
                'cashier' => $sale->dibuat_oleh,
                'items' => $itemCount,
                'amount' => $totalAmount,
                'formatted_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'payment_method' => $sale->keuangan_kotak ?? 'Tunai',
                'customer' => $sale->pelanggan ? $sale->pelanggan->nama_lengkap : 'Walk-in Customer',
                'status' => $sale->status_bayar === 'Lunas' ? 'Completed' : 'Belum Lunas',
                'status_bayar' => $sale->status_bayar
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Calculate stats for My Sales page with caching
     */
    private function calculateMySalesStats($userId)
    {
        $cacheKey = "my_sales_stats_{$userId}";

        return cache()->remember($cacheKey, 300, function() use ($userId) { // Cache for 5 minutes
            $query = Penjualan::with(['penjualanDetails'])
                ->where('dibuat_oleh', $userId)
                ->whereIn('status_bayar', ['Lunas', 'Belum Lunas']);

            $totalTransactions = $query->count();

            // Fetch all sales data once for all-time calculations
            $allSales = $query->get();

            // Calculate All Time Revenue for Average Sale calculation
            $allTimeRevenue = $allSales->reduce(function ($carry, $sale) {
                $saleTotal = $sale->penjualanDetails->sum(function ($item) {
                    return ($item->harga_jual * $item->qty) - $item->diskon;
                });
                return $carry + $saleTotal;
            }, 0);

            // Calculate Today's Revenue
            $today = Carbon::today();
            $todayQuery = Penjualan::with(['penjualanDetails'])
                ->where('dibuat_oleh', $userId)
                ->whereIn('status_bayar', ['Lunas', 'Belum Lunas'])
                ->whereDate('date_created', $today);

            $todayRevenueData = $todayQuery->get()->reduce(function ($carry, $sale) {
                $saleTotal = $sale->penjualanDetails->sum(function ($item) {
                    return ($item->harga_jual * $item->qty) - $item->diskon;
                });
                return $carry + $saleTotal;
            }, 0);

            $totalItems = $allSales->sum(function ($sale) {
                return $sale->penjualanDetails->sum('qty');
            });

            $averageSale = $totalTransactions > 0 ? $allTimeRevenue / $totalTransactions : 0;

            return [
                'total_transactions' => $totalTransactions,
                'total_revenue' => $todayRevenueData, // Use today's revenue
                'average_sale' => $averageSale,
                'total_items' => $totalItems
            ];
        });
    }

    /**
     * Calculate stats for All Sales page with caching
     */
    private function calculateAllSalesStats()
    {
        $cacheKey = "all_sales_stats";

        return cache()->remember($cacheKey, 300, function() { // Cache for 5 minutes
            $query = Penjualan::with(['penjualanDetails'])
                ->whereIn('status_bayar', ['Lunas', 'Belum Lunas']);

            $totalTransactions = $query->count();

            // Fetch all sales data once for all-time calculations
            $allSales = $query->get();

            // Calculate All Time Revenue for Average Sale calculation
            $allTimeRevenue = $allSales->reduce(function ($carry, $sale) {
                $saleTotal = $sale->penjualanDetails->sum(function ($item) {
                    return ($item->harga_jual * $item->qty) - $item->diskon;
                });
                return $carry + $saleTotal;
            }, 0);

            // Calculate Today's Revenue
            $today = Carbon::today();
            $todayQuery = Penjualan::with(['penjualanDetails'])
                ->whereIn('status_bayar', ['Lunas', 'Belum Lunas'])
                ->whereDate('date_created', $today);

            $todayRevenueData = $todayQuery->get()->reduce(function ($carry, $sale) {
                $saleTotal = $sale->penjualanDetails->sum(function ($item) {
                    return ($item->harga_jual * $item->qty) - $item->diskon;
                });
                return $carry + $saleTotal;
            }, 0);

            $totalItems = $allSales->sum(function ($sale) {
                return $sale->penjualanDetails->sum('qty');
            });

            $averageSale = $totalTransactions > 0 ? $allTimeRevenue / $totalTransactions : 0;

            return [
                'total_transactions' => $totalTransactions,
                'total_revenue' => $todayRevenueData, // Use today's revenue
                'average_sale' => $averageSale,
                'total_items' => $totalItems
            ];
        });
    }
}

