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
        
        // Fetch real sales data from database
        $sales = $this->getRealSalesData($user->name ?? $user->username, 'my');
        
        return view('sales.my-sales', compact('sales', 'user'));
    }
    
    /**
     * Show all cashiers sales report
     */
    public function allSales(Request $request)
    {
        $user = auth()->user();
        
        // Get pagination parameters
        $perPage = $request->get('per_page', 50); // Default 50 records per page
        $page = $request->get('page', 1);
        
        // Fetch real sales data from database with pagination
        $sales = $this->getRealSalesDataPaginated(null, 'all', $perPage, $page);
        
        return view('sales.all-sales', compact('sales', 'user'));
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
            ->where('status_bayar', 'Lunas') // Only completed sales
            ->where('status_barang', 'diterima langsung'); // Only completed deliveries
        
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
        
        // Return paginated collection
        return $sales->setCollection($transformedSales);
    }
}

