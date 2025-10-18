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
        
        // Fetch both completed and incomplete sales data from database
        $sales = $this->getMySalesData($user->name ?? $user->username);
        
        return view('sales.my-sales', compact('sales', 'user'));
    }
    
    /**
     * Show all cashiers sales report
     */
    public function allSales(Request $request)
    {
        $user = auth()->user();
        
        // For DataTable server-side processing, we'll load all data and let DataTable handle pagination
        // This is more efficient for large datasets
        $sales = $this->getRealSalesDataPaginated(null, 'all', 1000, 1); // Load up to 1000 records
        
        return view('sales.all-sales', compact('sales', 'user'));
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
}

