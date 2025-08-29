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

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Create sales record
            $saleId = DB::table('penjualan')->insertGetId([
                'no_faktur_penjualan' => $invoiceNumber,
                'kd_pelanggan' => $request->input('customer.name') ?: 'Walk-in Customer',
                'sub_total' => $request->input('totals.subtotal'),
                'pajak' => $request->input('totals.tax'),
                'total_harga' => $request->input('totals.total'),
                'total_bayar' => $request->input('payment.amountReceived'),
                'lebih_bayar' => $request->input('payment.amountReceived') - $request->input('totals.total'),
                'status_bayar' => 'Lunas',
                'keuangan_kotak' => $request->input('payment.method'),
                'catatan' => 'Transaction via Cashier System',
                'status_barang' => 'diterima langsung',
                'date_created' => now(),
                'date_updated' => now(),
                'dibuat_oleh' => auth()->user()->name ?? 'Cashier'
            ]);

            // Create sales detail records
            foreach ($request->input('items') as $item) {
                $product = Product::where('kd_produk', $item['id'])->first();
                
                if (!$product) continue;
                
                DB::table('penjualan_detail')->insert([
                    'id_penjualan' => $saleId,
                    'id_produk' => $item['id'],
                    'nama_produk' => $product->nama_produk,
                    'jenis_produk' => $product->productType ? $product->productType->nama : 'Unknown',
                    'id_pemasok' => $product->kd_pemasok ?? 1,
                    'pemasok' => $product->pemasok ?? 'Unknown',
                    'keterangan_bayar' => 'Cashier Sale',
                    'hpp' => $product->hpp,
                    'harga_jual' => $product->harga_jual,
                    'qty' => $item['quantity'],
                    'sub_total' => $item['quantity'] * $product->harga_jual,
                    'diskon' => 0,
                    'status_bayar' => 'Lunas',
                    'data_created' => now(),
                    'data_updated' => now(),
                    'dibuat_oleh' => auth()->user()->name ?? 'Cashier',
                    'no_faktur_penjualan' => $invoiceNumber
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
}
