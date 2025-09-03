<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display the sales index page
     */
    public function index()
    {
        return view('sales.index');
    }

    /**
     * Get sales with pagination and search
     */
    public function getSales(Request $request)
    {
        try {
            $query = DB::table('penjualan as p')
                ->select([
                    'p.kd_penjualan',
                    'p.no_faktur_penjualan',
                    'p.kd_pelanggan',
                    'p.date_created',
                    'p.sub_total',
                    'p.pajak',
                    'p.total_harga',
                    'p.status_bayar',
                    'p.keuangan_kotak as metode_pembayaran',
                    DB::raw('COUNT(pd.kd_penjualan_detail) as total_items')
                ])
                ->leftJoin('penjualan_detail as pd', 'p.kd_penjualan', '=', 'pd.kd_penjualan')
                ->groupBy('p.kd_penjualan', 'p.no_faktur_penjualan', 'p.kd_pelanggan', 'p.date_created', 
                         'p.sub_total', 'p.pajak', 'p.total_harga', 'p.status_bayar', 'p.keuangan_kotak');

            // Search functionality
            $search = $request->get('search');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('p.no_faktur_penjualan', 'like', "%{$search}%");
                });
            }

            // Date range filter
            $dateRange = $request->get('date_range', 'all');
            switch ($dateRange) {
                case 'all':
                    // No date filter - show all sales
                    break;
                case 'today':
                    $query->whereDate('p.date_created', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('p.date_created', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('p.date_created', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('p.date_created', Carbon::now()->month);
                    break;
            }

            // Status filter
            $status = $request->get('status');
            if ($status && $status !== 'all') {
                $query->where('p.status_bayar', $status);
            }

            $perPage = $request->get('per_page', 20);
            $sales = $query->orderBy('p.date_created', 'desc')
                          ->paginate($perPage);

            return response()->json([
                'success' => true,
                'sales' => $sales->items(),
                'current_page' => $sales->currentPage(),
                'last_page' => $sales->lastPage(),
                'per_page' => $sales->perPage(),
                'total' => $sales->total()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load sales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sale details with items
     */
    public function getSaleDetails($id)
    {
        try {
            $sale = DB::table('penjualan')
                ->where('kd_penjualan', $id)
                ->first();

            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale not found'
                ], 404);
            }

            $details = DB::table('penjualan_detail as pd')
                ->select([
                    'pd.kd_penjualan_detail',
                    'pd.kd_penjualan',
                    'pd.kd_produk',
                    'pd.nama_produk',
                    'pd.qty',
                    'pd.harga_jual',
                    'pd.diskon',
                    'pd.sub_total',
                    'pd.laba'
                ])
                ->where('pd.kd_penjualan', $id)
                ->get();

            return response()->json([
                'success' => true,
                'sale' => $sale,
                'details' => $details
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load sale details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sale item
     */
    public function updateSaleItem(Request $request, $id)
    {
        try {
            $request->validate([
                'qty' => 'required|integer|min:1',
                'harga_jual' => 'required|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0'
            ]);

            $item = DB::table('penjualan_detail')
                ->where('kd_penjualan_detail', $id)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale item not found'
                ], 404);
            }

            $qty = $request->input('qty');
            $hargaJual = $request->input('harga_jual');
            $diskon = $request->input('diskon', 0);
            $subTotal = ($hargaJual * $qty) - $diskon;

            // Get product cost for profit calculation
            $product = DB::table('produk')
                ->where('kd_produk', $item->kd_produk)
                ->first();

            $laba = $product ? (($hargaJual - $product->hpp) * $qty) - $diskon : 0;

            DB::table('penjualan_detail')
                ->where('kd_penjualan_detail', $id)
                ->update([
                    'qty' => $qty,
                    'harga_jual' => $hargaJual,
                    'diskon' => $diskon,
                    'sub_total' => $subTotal,
                    'laba' => $laba,
                    'date_updated' => Carbon::now()
                ]);

            // Update sale totals
            $this->updateSaleTotals($item->kd_penjualan);

            return response()->json([
                'success' => true,
                'message' => 'Sale item updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sale item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete sale item
     */
    public function deleteSaleItem($id)
    {
        try {
            $item = DB::table('penjualan_detail')
                ->where('kd_penjualan_detail', $id)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale item not found'
                ], 404);
            }

            DB::table('penjualan_detail')
                ->where('kd_penjualan_detail', $id)
                ->delete();

            // Update sale totals
            $this->updateSaleTotals($item->kd_penjualan);

            return response()->json([
                'success' => true,
                'message' => 'Sale item deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sale item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export sales
     */
    public function exportSales(Request $request)
    {
        try {
            $query = DB::table('penjualan as p')
                ->select([
                    'p.no_faktur_penjualan',
                    'p.kd_pelanggan',
                    'p.date_created',
                    'p.sub_total',
                    'p.pajak',
                    'p.total_harga',
                    'p.status_bayar',
                    'p.keuangan_kotak as metode_pembayaran'
                ]);

            // Apply same filters as getSales
            $search = $request->get('search');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('p.no_faktur_penjualan', 'like', "%{$search}%");
                });
            }

            $dateRange = $request->get('date_range', 'all');
            switch ($dateRange) {
                case 'all':
                    // No date filter - show all sales
                    break;
                case 'today':
                    $query->whereDate('p.date_created', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('p.date_created', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('p.date_created', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('p.date_created', Carbon::now()->month);
                    break;
            }

            $status = $request->get('status');
            if ($status && $status !== 'all') {
                $query->where('p.status_bayar', $status);
            }

            $sales = $query->orderBy('p.date_created', 'desc')->get();

            // Generate CSV
            $filename = 'sales_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($sales) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Invoice', 'Customer ID', 'Date', 'Subtotal', 'Tax', 'Total', 'Status', 'Payment Method']);
                
                foreach ($sales as $sale) {
                    fputcsv($file, [
                        $sale->no_faktur_penjualan,
                        $sale->kd_pelanggan,
                        $sale->date_created,
                        $sale->sub_total,
                        $sale->pajak,
                        $sale->total_harga,
                        $sale->status_bayar,
                        $sale->metode_pembayaran
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export sales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sale totals after item changes
     */
    private function updateSaleTotals($saleId)
    {
        $totals = DB::table('penjualan_detail')
            ->where('kd_penjualan', $saleId)
            ->selectRaw('SUM(sub_total) as sub_total, SUM(laba) as total_laba')
            ->first();

        $subTotal = $totals->sub_total ?? 0;
        $pajak = $subTotal * 0.11; // 11% tax
        $totalHarga = $subTotal + $pajak;

        DB::table('penjualan')
            ->where('kd_penjualan', $saleId)
            ->update([
                'sub_total' => $subTotal,
                'pajak' => $pajak,
                'total_harga' => $totalHarga,
                'date_updated' => Carbon::now()
            ]);
    }
}
