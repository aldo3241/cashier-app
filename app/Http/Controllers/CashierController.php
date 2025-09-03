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
        
        // Calculate current stock from stok table
        $stockIn = DB::table('stok')->where('kd_produk', $product->kd_produk)->sum('masuk');
        $stockOut = DB::table('stok')->where('kd_produk', $product->kd_produk)->sum('keluar');
        $currentStock = $stockIn - $stockOut;
        
        // If no stock records exist, use the stok_total from produk table
        if ($stockIn == 0 && $stockOut == 0) {
            $currentStock = $product->stok_total ?? 0;
        }
        


        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->kd_produk,
                'name' => $product->nama_produk,
                'barcode' => $product->barcode,
                'price' => (float) $product->harga_jual,
                'cost' => (float) $product->hpp,
                'stock' => (int) $currentStock,
                'type' => $product->productType ? $product->productType->nama : 'Unknown',
                'supplier' => $product->pemasok,
                'image_url' => $product->image_url ?? null
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
        
        // Optimize stock calculation with a single query
        $productIds = $products->pluck('kd_produk')->toArray();
        $stockData = collect();
        
        if (!empty($productIds)) {
            $stockData = DB::table('stok')
                ->select('kd_produk', 
                    DB::raw('SUM(masuk) as total_in'), 
                    DB::raw('SUM(keluar) as total_out'))
                ->whereIn('kd_produk', $productIds)
                ->groupBy('kd_produk')
                ->get()
                ->keyBy('kd_produk');
        }
        
        return response()->json([
            'success' => true,
            'products' => $products->map(function($product) use ($stockData) {
                $currentStock = 0;
                
                if ($stockData->has($product->kd_produk)) {
                    $stock = $stockData->get($product->kd_produk);
                    $currentStock = ($stock->total_in ?? 0) - ($stock->total_out ?? 0);
                } else {
                    // No stock records exist, use the stok_total from produk table
                    $currentStock = $product->stok_total ?? 0;
                }
                
                return [
                    'id' => $product->kd_produk,
                    'name' => $product->nama_produk,
                    'barcode' => $product->barcode,
                    'price' => $product->harga_jual,
                    'stock' => $currentStock,
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

                                     // Check stock from stok table
            $stockIn = DB::table('stok')->where('kd_produk', $request->product_id)->sum('masuk');
            $stockOut = DB::table('stok')->where('kd_produk', $request->product_id)->sum('keluar');
            $currentStock = $stockIn - $stockOut;
            
            // If no stock records exist, use the stok_total from produk table
            if ($stockIn == 0 && $stockOut == 0) {
                $currentStock = $product->stok_total ?? 0;
            }
            

            
            if ($currentStock < $request->quantity) {
                throw new \Exception('Insufficient stock. Available: ' . $currentStock);
            }

            // Check if product already exists in this sale
            $existingItem = DB::table('penjualan_detail')
                ->where('kd_penjualan', $request->sale_id)
                ->where('kd_produk', $request->product_id)
                ->first();

            if ($existingItem) {
                // Product already exists, update quantity
                $newQty = $existingItem->qty + $request->quantity;
                $newSubtotal = $newQty * $product->harga_jual;
                $newProfit = ($product->harga_jual - $product->hpp) * $newQty;

                DB::table('penjualan_detail')
                    ->where('kd_penjualan_detail', $existingItem->kd_penjualan_detail)
                    ->update([
                        'qty' => $newQty,
                        'sub_total' => $newSubtotal,
                        'laba' => $newProfit,
                        'date_updated' => now()
                    ]);

                $message = 'Product quantity updated in sale!';
            } else {
                // Product doesn't exist, add new item
                $profit = ($product->harga_jual - $product->hpp) * $request->quantity;

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

                $message = 'Product added to sale!';
            }

                         // Handle stock management
            if ($existingItem) {
                // For existing items, we only need to adjust stock for the additional quantity
                $additionalQty = $request->quantity; // Only the new quantity added
            } else {
                // For new items, use the full quantity
                $additionalQty = $request->quantity;
            }
            
            // Check if this product has existing stock records
            $existingStockRecords = DB::table('stok')->where('kd_produk', $request->product_id)->count();
            
            if ($existingStockRecords > 0) {
                // Product has stock records, create stock movement record
                DB::table('stok')->insert([
                    'kd_produk' => $request->product_id,
                    'masuk' => 0,
                    'keluar' => $additionalQty,
                    'klasifikasi' => 'Penjualan',
                    'no_ref' => $request->sale_id,
                    'catatan' => $existingItem ? 'Cashier Sale - Quantity Updated' : 'Cashier Sale - Pending',
                    'date_created' => now(),
                    'date_updated' => now(),
                    'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
                ]);
                
                // Update product stock total based on stok table
                $this->updateProductStockTotal($request->product_id);
            } else {
                // Product has no stock records, directly update the produk table
                $newStock = $product->stok_total - $additionalQty;
                DB::table('produk')->where('kd_produk', $request->product_id)->update([
                    'stok_total' => $newStock,
                    'date_updated' => now()
                ]);
            }

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
                'message' => $message,
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
                 $stockIn = DB::table('stok')->where('kd_produk', $detail->kd_produk)->sum('masuk');
                 $stockOut = DB::table('stok')->where('kd_produk', $detail->kd_produk)->sum('keluar');
                 $currentStock = $stockIn - $stockOut;
                 
                 // If no stock records exist, use the stok_total from produk table
                 if ($stockIn == 0 && $stockOut == 0) {
                     $currentStock = $product->stok_total ?? 0;
                 }
                 
                 if ($currentStock < $stockNeeded) {
                     throw new \Exception('Insufficient stock for quantity increase. Available: ' . $currentStock);
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
                 
                 // Check if this product has existing stock records
                 $existingStockRecords = DB::table('stok')->where('kd_produk', $detail->kd_produk)->count();
                 
                 if ($existingStockRecords > 0) {
                     // Product has stock records, create stock adjustment record
                     if ($stockAdjustment > 0) {
                         // Stock is being restored (quantity reduced)
                         DB::table('stok')->insert([
                             'kd_produk' => $detail->kd_produk,
                             'masuk' => $stockAdjustment,
                             'keluar' => 0,
                             'klasifikasi' => 'Penyesuaian Penjualan',
                             'no_ref' => $detail->kd_penjualan,
                             'catatan' => "Quantity reduced from {$oldQty} to {$newQty} - Stock restored",
                             'date_created' => now(),
                             'date_updated' => now(),
                             'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
                         ]);
                     } else {
                         // Stock is being reduced (quantity increased)
                         DB::table('stok')->insert([
                             'kd_produk' => $detail->kd_produk,
                             'masuk' => 0,
                             'keluar' => abs($stockAdjustment),
                             'klasifikasi' => 'Penyesuaian Penjualan',
                             'no_ref' => $detail->kd_penjualan,
                             'catatan' => "Quantity increased from {$oldQty} to {$newQty} - Additional stock used",
                             'date_created' => now(),
                             'date_updated' => now(),
                             'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
                         ]);
                     }
                     
                     // Update product stock total based on stok table
                     $this->updateProductStockTotal($detail->kd_produk);
                 } else {
                     // Product has no stock records, directly update the produk table
                     $product = Product::where('kd_produk', $detail->kd_produk)->first();
                     if ($product) {
                         $newStock = $product->stok_total + $stockAdjustment; // + because stockAdjustment is positive when reducing
                         DB::table('produk')->where('kd_produk', $detail->kd_produk)->update([
                             'stok_total' => $newStock,
                             'date_updated' => now()
                         ]);
                     }
                 }
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

             // Check if this product has existing stock records
             $existingStockRecords = DB::table('stok')->where('kd_produk', $productId)->count();
             
             if ($existingStockRecords > 0) {
                 // Product has stock records, create stock restoration record
                 DB::table('stok')->insert([
                     'kd_produk' => $productId,
                     'masuk' => $qty,
                     'keluar' => 0,
                     'klasifikasi' => 'Pembatalan Penjualan',
                     'no_ref' => $saleId,
                     'catatan' => 'Item removed from sale - Stock restored',
                     'date_created' => now(),
                     'date_updated' => now(),
                     'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
                 ]);
                 
                 // Update product stock total based on stok table
                 $this->updateProductStockTotal($productId);
             } else {
                 // Product has no stock records, directly update the produk table
                 $product = Product::where('kd_produk', $productId)->first();
                 if ($product) {
                     $newStock = $product->stok_total + $qty;
                     DB::table('produk')->where('kd_produk', $productId)->update([
                         'stok_total' => $newStock,
                         'date_updated' => now()
                     ]);
                 }
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
     * Complete a pending sale
     */
    public function completeSale(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:penjualan,kd_penjualan',
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

            $saleId = $request->input('sale_id');
            
            // Get the current sale
            $sale = DB::table('penjualan')->where('kd_penjualan', $saleId)->first();
            if (!$sale) {
                throw new \Exception('Sale not found');
            }

            if ($sale->status_bayar !== 'Pending') {
                throw new \Exception('Sale is not in pending status');
            }

            // Update the sale record to complete it
            DB::table('penjualan')->where('kd_penjualan', $saleId)->update([
                'sub_total' => $request->input('totals.subtotal'),
                'pajak' => $request->input('totals.tax'),
                'total_harga' => $request->input('totals.total'),
                'total_bayar' => $request->input('payment.amountReceived'),
                'lebih_bayar' => $request->input('payment.amountReceived') - $request->input('totals.total'),
                'status_bayar' => 'Lunas',
                'keuangan_kotak' => $request->input('payment.method'),
                'catatan' => $request->input('customer.name') ?: 'Walk-in Customer',
                'status_barang' => 'diterima langsung',
                'date_updated' => now()
            ]);

                         // Update all detail records to complete status
             DB::table('penjualan_detail')
                 ->where('kd_penjualan', $saleId)
                 ->update([
                     'sistem_bayar' => $request->input('payment.method'),
                     'status_bayar' => 'Lunas',
                     'date_updated' => now()
                 ]);

             // Update stock records to mark sale as completed
             DB::table('stok')
                 ->where('no_ref', $saleId)
                 ->where('klasifikasi', 'Penjualan')
                 ->update([
                     'catatan' => 'Cashier Sale - Completed',
                     'date_updated' => now()
                 ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'sale_id' => $saleId,
                'invoice_number' => $sale->no_faktur_penjualan
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete sale: ' . $e->getMessage()
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

    /**
     * Helper method to update product stock total based on stok table
     */
    private function updateProductStockTotal($productId)
    {
        // Calculate current stock from stok table
        $stockIn = DB::table('stok')->where('kd_produk', $productId)->sum('masuk');
        $stockOut = DB::table('stok')->where('kd_produk', $productId)->sum('keluar');
        $currentStock = $stockIn - $stockOut;

        // Update the stok_total in produk table
        DB::table('produk')->where('kd_produk', $productId)->update([
            'stok_total' => $currentStock,
            'date_updated' => now()
        ]);
    }

    /**
     * Handle stock adjustment when produk table is updated
     */
    public function adjustStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,kd_produk',
            'new_stock' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $productId = $request->input('product_id');
            $newStock = $request->input('new_stock');
            $reason = $request->input('reason', 'Manual Stock Adjustment');
            $reference = $request->input('reference', 'Manual Edit');

            // Get current stock from stok table
            $stockIn = DB::table('stok')->where('kd_produk', $productId)->sum('masuk');
            $stockOut = DB::table('stok')->where('kd_produk', $productId)->sum('keluar');
            $currentStock = $stockIn - $stockOut;

            // Calculate the difference
            $stockDifference = $newStock - $currentStock;

            if ($stockDifference != 0) {
                // Create stock adjustment record
                if ($stockDifference > 0) {
                    // Stock is being increased
                    DB::table('stok')->insert([
                        'kd_produk' => $productId,
                        'masuk' => $stockDifference,
                        'keluar' => 0,
                        'klasifikasi' => 'Penyesuaian Manual',
                        'no_ref' => $reference,
                        'catatan' => $reason . " - Stock increased from {$currentStock} to {$newStock}",
                        'date_created' => now(),
                        'date_updated' => now(),
                        'dibuat_oleh' => auth()->user()->name ?? 'System'
                    ]);
                } else {
                    // Stock is being decreased
                    DB::table('stok')->insert([
                        'kd_produk' => $productId,
                        'masuk' => 0,
                        'keluar' => abs($stockDifference),
                        'klasifikasi' => 'Penyesuaian Manual',
                        'no_ref' => $reference,
                        'catatan' => $reason . " - Stock decreased from {$currentStock} to {$newStock}",
                        'date_created' => now(),
                        'date_updated' => now(),
                        'dibuat_oleh' => auth()->user()->name ?? 'System'
                    ]);
                }

                // Update the produk table
                DB::table('produk')->where('kd_produk', $productId)->update([
                    'stok_total' => $newStock,
                    'date_updated' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully',
                'product_id' => $productId,
                'old_stock' => $currentStock,
                'new_stock' => $newStock,
                'adjustment' => $stockDifference
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to adjust stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock history for a product
     */
    public function getStockHistory(Request $request, $productId)
    {
        try {
            $stockHistory = DB::table('stok')
                ->where('kd_produk', $productId)
                ->orderBy('date_created', 'desc')
                ->get();

            // Calculate running stock
            $runningStock = 0;
            $stockHistory = $stockHistory->map(function($record) use (&$runningStock) {
                $runningStock += $record->masuk - $record->keluar;
                $record->running_stock = $runningStock;
                return $record;
            });

            return response()->json([
                'success' => true,
                'product_id' => $productId,
                'stock_history' => $stockHistory
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get stock history: ' . $e->getMessage()
            ], 500);
        }
    }
}
