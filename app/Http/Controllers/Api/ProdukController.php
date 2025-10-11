<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProdukJenis;
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

            $products = Produk::with('produkJenis')
                ->search($search)
                ->inStock()
                ->limit($limit)
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->kd_produk,
                        'name' => $product->nama_produk,
                        'price' => (float) $product->harga_jual,
                        'barcode_int' => $product->kd_int,
                        'barcode_ext' => $product->kd_ext,
                        'barcode' => $product->kd_int ?: $product->kd_ext, // Primary barcode
                        'category' => $product->category_name,
                        'stock' => $product->stok_total,
                        'unit' => $product->satuan ?? 'pcs',
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
            
            $product = Produk::with('produkJenis')
                ->where(function($query) use ($barcode) {
                    $query->where('kd_int', $barcode)
                        ->orWhere('kd_ext', $barcode)
                        ->orWhere('kd_produk', $barcode);
                })
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
                    'category' => $product->category_name,
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
     * Get all categories from produk_jenis table
     */
    public function getCategories()
    {
        try {
            // Get categories from produk_jenis table with product count
            $categories = ProdukJenis::withCount(['produks' => function($query) {
                    $query->where('stok_total', '>', 0); // Only count products in stock
                }])
                ->orderByName()
                ->get()
                ->map(function($jenis) {
                    return [
                        'id' => $jenis->kd_produk_jenis,
                        'name' => $jenis->nama,
                        'product_count' => $jenis->produks_count
                    ];
                })
                ->filter(function($category) {
                    return $category['product_count'] > 0; // Only return categories with products
                })
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
