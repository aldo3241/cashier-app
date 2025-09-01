<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    public function index()
    {
        return view('sales.cashier');
    }

    public function scanProduct(Request $request)
    {
        $barcode = $request->input('barcode');
        
        // Debug: Log the search query
        \Log::info('Cashier scan request:', ['barcode' => $barcode]);
        
        // Search by barcode first (exact match)
        $product = Product::where('barcode', $barcode)->with('productType')->first();
        
        // If not found by barcode, try partial matches on product name
        if (!$product && strlen($barcode) >= 3) {
            $product = Product::where('nama_produk', 'LIKE', '%' . $barcode . '%')
                ->with('productType')
                ->first();
        }
        
        // If still not found, try by ID (if barcode is numeric)
        if (!$product && is_numeric($barcode)) {
            $product = Product::where('kd_produk', $barcode)->with('productType')->first();
        }
        
        // Debug: Log the search result
        \Log::info('Product search result:', ['found' => $product ? true : false, 'product_id' => $product ? $product->kd_produk : null]);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'debug' => [
                    'searched_barcode' => $barcode,
                    'product_count' => Product::count(),
                    'sample_products' => Product::take(3)->pluck('nama_produk', 'kd_produk')
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->kd_produk,
                'name' => $product->nama_produk,
                'barcode' => $product->barcode,
                'price' => (float) $product->harga_jual,
                'cost' => (float) $product->hpp,
                'stock' => (int) $product->stok_total,
                'type' => $product->productType ? $product->productType->nama : 'Unknown',
                'supplier' => $product->pemasok
            ]
        ]);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:produk,kd_produk',
            'items.*.quantity' => 'required|integer|min:1',
            'customer.name' => 'nullable|string|max:255',
            'customer.phone' => 'nullable|string|max:20',
            'payment.method' => 'required|string',
            'payment.amountReceived' => 'required|numeric|min:0',
            'totals.subtotal' => 'required|numeric|min:0',
            'totals.tax' => 'required|numeric|min:0',
            'totals.total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Generate invoice number in format: PJ250723110833
            $invoiceNumber = 'PJ' . date('ymd') . date('His');
            
            // Create sales record in penjualan table
            $saleId = DB::table('penjualan')->insertGetId([
                'no_faktur_penjualan' => $invoiceNumber,
                'kd_pelanggan' => 1, // Default customer ID
                'sub_total' => $request->input('totals.subtotal'),
                'pajak' => $request->input('totals.tax'),
                'total_harga' => $request->input('totals.total'),
                'total_bayar' => $request->input('payment.amountReceived'),
                'lebih_bayar' => $request->input('payment.amountReceived') - $request->input('totals.total'),
                'status_bayar' => 'Lunas',
                'keuangan_kotak' => $request->input('payment.method'),
                'catatan' => $request->input('customer.name') ?: 'Walk-in Customer',
                'status_barang' => 'diterima langsung',
                'date_created' => now(),
                'date_updated' => now(),
                'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
            ]);

            // Create sales detail records in penjualan_detail table
            foreach ($request->input('items') as $item) {
                $product = Product::where('kd_produk', $item['id'])->first();
                
                if (!$product) continue;
                
                // Calculate profit
                $profit = ($product->harga_jual - $product->hpp) * $item['quantity'];
                
                                 DB::table('penjualan_detail')->insert([
                     'kd_penjualan' => $saleId,
                     'kd_produk' => $item['id'],
                     'nama_produk' => $product->nama_produk,
                     'produk_jenis' => $product->productType ? $product->productType->nama : 'Unknown',
                     'kd_pemasok' => $product->kd_pemasok ?? 1,
                     'pemasok' => $product->pemasok ?? 'Unknown',
                     'sistem_bayar' => $request->input('payment.method'),
                     'hpp' => $product->hpp,
                     'harga_jual' => $product->harga_jual,
                     'qty' => $item['quantity'],
                     'diskon' => 0,
                     'sub_total' => $item['quantity'] * $product->harga_jual,
                     'laba' => $profit,
                     'status_bayar' => 'Lunas',
                     'catatan' => $invoiceNumber,
                     'date_created' => now(),
                     'date_updated' => now(),
                     'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
                 ]);

                // Update stock
                $product->decrement('stok_total', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully',
                'invoice_number' => $invoiceNumber,
                'sale_id' => $saleId
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProducts(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::where('nama_produk', 'LIKE', '%' . $search . '%')
            ->orWhere('barcode', 'LIKE', '%' . $search . '%')
            ->with('productType')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->kd_produk,
                    'name' => $product->nama_produk,
                    'barcode' => $product->barcode,
                    'price' => $product->harga_jual,
                    'stock' => $product->stok_total,
                    'type' => $product->productType ? $product->productType->nama : 'Unknown'
                ];
            })
        ]);
    }

    public function getProductTypes()
    {
        $types = ProductType::all();
        
        return response()->json([
            'success' => true,
            'types' => $types
        ]);
    }

    /**
     * Create a new pending sale
     */
    public function createPendingSale(Request $request)
    {
        try {
            DB::beginTransaction();

            // Generate invoice number in format: PJ250723110833
            $invoiceNumber = 'PJ' . date('ymd') . date('His');
            
            // Create pending sales record
            $saleId = DB::table('penjualan')->insertGetId([
                'no_faktur_penjualan' => $invoiceNumber,
                'kd_pelanggan' => 1, // Default customer ID
                'sub_total' => 0,
                'pajak' => 0,
                'total_harga' => 0,
                'total_bayar' => 0,
                'lebih_bayar' => 0,
                'status_bayar' => 'Pending',
                'keuangan_kotak' => 'Pending',
                'catatan' => 'Pending sale - Cashier System',
                'status_barang' => 'pending',
                'date_created' => now(),
                'date_updated' => now(),
                'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pending sale created',
                'sale_id' => $saleId,
                'invoice_number' => $invoiceNumber
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create pending sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to existing sale
     */
    public function addItemToSale(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:penjualan,kd_penjualan',
            'product_id' => 'required|exists:produk,kd_produk',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::where('kd_produk', $request->product_id)->first();
            if (!$product) {
                throw new \Exception('Product not found');
            }

            // Check stock
            if ($product->stok_total < $request->quantity) {
                throw new \Exception('Insufficient stock');
            }

            // Calculate profit
            $profit = ($product->harga_jual - $product->hpp) * $request->quantity;

            // Add item to penjualan_detail
            DB::table('penjualan_detail')->insert([
                'kd_penjualan' => $request->sale_id,
                'kd_produk' => $request->product_id,
                'nama_produk' => $product->nama_produk,
                'produk_jenis' => $product->productType ? $product->productType->nama : 'Unknown',
                'kd_pemasok' => $product->kd_pemasok ?? 1,
                'pemasok' => $product->pemasok ?? 'Unknown',
                'sistem_bayar' => 'Pending',
                'hpp' => $product->hpp,
                'harga_jual' => $product->harga_jual,
                'qty' => $request->quantity,
                'diskon' => 0,
                'sub_total' => $request->quantity * $product->harga_jual,
                'laba' => $profit,
                'status_bayar' => 'Pending',
                'catatan' => 'Added via Cashier',
                'date_created' => now(),
                'date_updated' => now(),
                'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
            ]);

            // Update sale totals
            $sale = DB::table('penjualan')->where('kd_penjualan', $request->sale_id)->first();
            $newSubtotal = $sale->sub_total + ($request->quantity * $product->harga_jual);
            $tax = $newSubtotal * 0.11; // 11% tax
            $total = $newSubtotal + $tax;

            DB::table('penjualan')->where('kd_penjualan', $request->sale_id)->update([
                'sub_total' => $newSubtotal,
                'pajak' => $tax,
                'total_harga' => $total,
                'date_updated' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item added to sale',
                'sale_id' => $request->sale_id,
                'new_totals' => [
                    'subtotal' => $newSubtotal,
                    'tax' => $tax,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sale details from penjualan_detail
     */
    public function getSaleDetails(Request $request, $saleId)
    {
        try {
            // Get the main sale record
            $sale = DB::table('penjualan')->where('kd_penjualan', $saleId)->first();
            
            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale not found'
                ], 404);
            }

            // Get all detail items for this sale
            $details = DB::table('penjualan_detail')
                ->where('kd_penjualan', $saleId)
                ->orderBy('kd_penjualan_detail', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'sale' => $sale,
                'details' => $details,
                'total_items' => $details->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get sale details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update item in penjualan_detail
     */
    public function updateSaleItem(Request $request, $detailId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Get the detail item
            $detail = DB::table('penjualan_detail')->where('kd_penjualan_detail', $detailId)->first();
            if (!$detail) {
                throw new \Exception('Sale detail not found');
            }

            // Get product info for calculations
            $product = Product::where('kd_produk', $detail->kd_produk)->first();
            if (!$product) {
                throw new \Exception('Product not found');
            }

            $oldQty = $detail->qty;
            $newQty = $request->qty;
            $newPrice = $request->harga_jual;
            $discount = $request->diskon ?? 0;

            // Check stock availability for quantity changes
            if ($newQty > $oldQty) {
                $stockNeeded = $newQty - $oldQty;
                if ($product->stok_total < $stockNeeded) {
                    throw new \Exception('Insufficient stock for quantity increase');
                }
            }

            // Calculate new values
            $newSubtotal = ($newQty * $newPrice) - $discount;
            $newProfit = ($newPrice - $product->hpp) * $newQty;

            // Update the detail item
            DB::table('penjualan_detail')
                ->where('kd_penjualan_detail', $detailId)
                ->update([
                    'qty' => $newQty,
                    'harga_jual' => $newPrice,
                    'diskon' => $discount,
                    'sub_total' => $newSubtotal,
                    'laba' => $newProfit,
                    'date_updated' => now()
                ]);

            // Update stock (adjust for quantity change)
            if ($newQty != $oldQty) {
                $stockAdjustment = $oldQty - $newQty; // Positive if reducing, negative if increasing
                $product->increment('stok_total', $stockAdjustment);
            }

            // Recalculate sale totals
            $this->recalculateSaleTotals($detail->kd_penjualan);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale item updated successfully',
                'updated_item' => [
                    'qty' => $newQty,
                    'harga_jual' => $newPrice,
                    'diskon' => $discount,
                    'sub_total' => $newSubtotal,
                    'laba' => $newProfit
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sale item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from penjualan_detail
     */
    public function removeSaleItem(Request $request, $detailId)
    {
        try {
            DB::beginTransaction();

            // Get the detail item
            $detail = DB::table('penjualan_detail')->where('kd_penjualan_detail', $detailId)->first();
            if (!$detail) {
                throw new \Exception('Sale detail not found');
            }

            $saleId = $detail->kd_penjualan;
            $qty = $detail->qty;
            $productId = $detail->kd_produk;

            // Delete the detail item
            DB::table('penjualan_detail')->where('kd_penjualan_detail', $detailId)->delete();

            // Restore stock
            $product = Product::where('kd_produk', $productId)->first();
            if ($product) {
                $product->increment('stok_total', $qty);
            }

            // Recalculate sale totals
            $this->recalculateSaleTotals($saleId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale item removed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove sale item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all sales with their details
     */
    public function getAllSalesWithDetails(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);
            $search = $request->input('search', '');
            $status = $request->input('status', '');

            // Build the base query
            $query = DB::table('penjualan');

            // Add search conditions
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('no_faktur_penjualan', 'LIKE', "%{$search}%")
                      ->orWhere('catatan', 'LIKE', "%{$search}%");
                });
            }

            if ($status) {
                $query->where('status_bayar', $status);
            }

            // Get total count for pagination
            $total = $query->count();
            
            // Get paginated results
            $offset = ($page - 1) * $perPage;
            $sales = $query->orderBy('date_created', 'desc')
                          ->offset($offset)
                          ->limit($perPage)
                          ->get();

            // Get additional data for each sale
            $salesWithDetails = $sales->map(function($sale) {
                $details = DB::table('penjualan_detail')
                    ->where('kd_penjualan', $sale->kd_penjualan)
                    ->get();
                
                $sale->total_items = $details->count();
                $sale->total_profit = $details->sum('laba');
                
                return $sale;
            });

            $lastPage = ceil($total / $perPage);

            return response()->json([
                'success' => true,
                'sales' => $salesWithDetails,
                'pagination' => [
                    'current_page' => (int)$page,
                    'last_page' => $lastPage,
                    'per_page' => (int)$perPage,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get sales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to recalculate sale totals
     */
    private function recalculateSaleTotals($saleId)
    {
        $details = DB::table('penjualan_detail')->where('kd_penjualan', $saleId)->get();
        
        $subtotal = $details->sum('sub_total');
        $tax = $subtotal * 0.11; // 11% tax
        $total = $subtotal + $tax;

        DB::table('penjualan')->where('kd_penjualan', $saleId)->update([
            'sub_total' => $subtotal,
            'pajak' => $tax,
            'total_harga' => $total,
            'date_updated' => now()
        ]);
    }
}
