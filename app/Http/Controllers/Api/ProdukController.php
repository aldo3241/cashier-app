<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Search products for cashier
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $limit = $request->get('limit', 10);

            $products = Produk::search($search)
                ->inStock()
                ->select([
                    'kd_produk as id',
                    'nama_produk as name', 
                    'harga_jual as price',
                    'kd_int',
                    'kd_ext',
                    'jenis as category',
                    'stok_total as stock',
                    'satuan as unit',
                    'gambar_produk as image'
                ])
                ->limit($limit)
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => (float) $product->price,
                        'barcode_int' => $product->kd_int,
                        'barcode_ext' => $product->kd_ext,
                        'barcode' => $product->kd_int ?: $product->kd_ext, // Primary barcode
                        'category' => $product->category ?? 'General',
                        'stock' => $product->stock,
                        'unit' => $product->unit ?? 'pcs',
                        'image_url' => $product->image_url
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product by barcode
     */
    public function getByBarcode(Request $request)
    {
        try {
            $barcode = $request->get('barcode');
            
            $product = Produk::where('kd_int', $barcode)
                ->orWhere('kd_ext', $barcode)
                ->orWhere('kd_produk', $barcode)
                ->inStock()
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->kd_produk,
                    'name' => $product->nama_produk,
                    'price' => (float) $product->harga_jual,
                    'barcode_int' => $product->kd_int,
                    'barcode_ext' => $product->kd_ext,
                    'barcode' => $product->kd_int ?: $product->kd_ext, // Primary barcode
                    'category' => $product->jenis ?? 'General',
                    'stock' => $product->stok_total,
                    'unit' => $product->satuan ?? 'pcs',
                    'image_url' => $product->image_url
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        try {
            $categories = Produk::select('jenis as category')
                ->whereNotNull('jenis')
                ->where('jenis', '!=', '')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product stock (when item is sold)
     */
    public function updateStock(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required',
                'quantity' => 'required|integer|min:1',
                'type' => 'in:masuk,keluar'
            ]);

            $product = Produk::find($request->product_id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Check stock availability for 'keluar' type
            if ($request->type === 'keluar' && !$product->hasEnoughStock($request->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock'
                ], 400);
            }

            $product->updateStock($request->quantity, $request->type);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => [
                    'product_id' => $product->kd_produk,
                    'new_stock' => $product->stok_total
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating stock: ' . $e->getMessage()
            ], 500);
        }
    }
}
