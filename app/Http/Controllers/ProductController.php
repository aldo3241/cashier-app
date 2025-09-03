<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with('productType');

            // Filter by product type
            if ($request->filled('type')) {
                $query->where('kd_produk_jenis', $request->type);
            }

            // Filter by stock status
            if ($request->filled('stock')) {
                switch ($request->stock) {
                    case 'in_stock':
                        $query->where('stok_total', '>', 10);
                        break;
                    case 'low_stock':
                        $query->where('stok_total', '>', 0)->where('stok_total', '<=', 10);
                        break;
                    case 'out_of_stock':
                        $query->where('stok_total', '<=', 0);
                        break;
                }
            }

            // Search by product name
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('nama_produk', 'like', '%' . $request->search . '%')
                      ->orWhere('barcode', 'like', '%' . $request->search . '%');
                });
            }

            // Sort products
            $sortBy = $request->get('sort', 'kd_produk');
            $sortOrder = $request->get('order', 'desc');
            
            if (in_array($sortBy, ['nama_produk', 'harga_jual', 'stok_total', 'date_created', 'kd_produk'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $products = $query->paginate(20);
            $productTypes = ProductType::orderBy('nama')->get();

            // Debug information
            \Log::info('Products loaded: ' . $products->count());
            \Log::info('Product types loaded: ' . $productTypes->count());

            return view('products.index', compact('products', 'productTypes'));
        } catch (\Exception $e) {
            \Log::error('Error in ProductController@index: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productTypes = ProductType::orderBy('nama')->get();
        return view('products.create', compact('productTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kd_produk_jenis' => 'required|exists:produk_jenis,kd_produk_jenis',
            'kd_pemasok' => 'nullable|integer',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'material' => 'nullable|string|max:255',
            'spesifik' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:100',
            'satuan' => 'nullable|string|max:50',
            'berat' => 'nullable|integer',
            'stok_total' => 'required|integer|min:0',
            'hpp' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'pemasok' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:produk,barcode',
        ], [
            'hpp.required' => 'Cost price (HPP) is required.',
            'hpp.numeric' => 'Cost price must be a valid number.',
            'hpp.min' => 'Cost price must be greater than or equal to 0.',
            'harga_jual.required' => 'Selling price is required.',
            'harga_jual.numeric' => 'Selling price must be a valid number.',
            'harga_jual.min' => 'Selling price must be greater than or equal to 0.',
            'stok_total.required' => 'Initial stock is required.',
            'stok_total.integer' => 'Stock must be a whole number.',
            'stok_total.min' => 'Stock must be greater than or equal to 0.',
            'barcode.unique' => 'This barcode is already in use.',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('gambar_produk')) {
                $image = $request->file('gambar_produk');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
            }

            // Validate selling price is >= cost price
            if ($request->harga_jual < $request->hpp) {
                return back()->withInput()
                    ->with('error', 'Selling price must be greater than or equal to cost price (HPP).');
            }

            // Create product
            $productData = [
                'kd_produk_jenis' => $request->kd_produk_jenis,
                'kd_pemasok' => $request->kd_pemasok ?? 1,
                'nama_produk' => $request->nama_produk,
                'gambar_produk' => $imagePath,
                'material' => $request->material,
                'spesifik' => $request->spesifik,
                'ukuran' => $request->ukuran,
                'satuan' => $request->satuan,
                'berat' => $request->berat,
                'stok_total' => $request->stok_total,
                'hpp' => $request->hpp,
                'harga_jual' => $request->harga_jual,
                'pemasok' => $request->pemasok,
                'barcode' => $request->barcode,
                'dibuat_oleh' => auth()->user()->name ?? 'Admin',
                'date_created' => now(),
                'date_updated' => now(),
            ];

            // Remove any null values to avoid issues
            $productData = array_filter($productData, function($value) {
                return $value !== null;
            });

            // Debug: Log the data being inserted
            \Log::info('Product data to insert:', $productData);

            // Use DB::table() directly to avoid Eloquent timestamp issues
            $productId = DB::table('produk')->insertGetId($productData);
            $product = Product::find($productId);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating product: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('productType')->findOrFail($id);
        
        // Calculate stock from stok table
        $stockIn = DB::table('stok')->where('kd_produk', $id)->sum('masuk');
        $stockOut = DB::table('stok')->where('kd_produk', $id)->sum('keluar');
        $calculatedStock = $stockIn - $stockOut;
        
        // Add stock information to the product object
        $product->stok_masuk = $stockIn;
        $product->stok_keluar = $stockOut;
        $product->calculated_stock = $calculatedStock;
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $productTypes = ProductType::orderBy('nama')->get();
        return view('products.edit', compact('product', 'productTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kd_produk_jenis' => 'required|exists:produk_jenis,kd_produk_jenis',
            'kd_pemasok' => 'nullable|integer',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'material' => 'nullable|string|max:255',
            'spesifik' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:100',
            'satuan' => 'nullable|string|max:50',
            'berat' => 'nullable|integer',
            'stok_total' => 'required|integer|min:0',
            'hpp' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'pemasok' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:produk,barcode,' . $id . ',kd_produk',
        ], [
            'hpp.required' => 'Cost price (HPP) is required.',
            'hpp.numeric' => 'Cost price must be a valid number.',
            'hpp.min' => 'Cost price must be greater than or equal to 0.',
            'harga_jual.required' => 'Selling price is required.',
            'harga_jual.numeric' => 'Selling price must be a valid number.',
            'harga_jual.min' => 'Selling price must be greater than or equal to 0.',
            'stok_total.required' => 'Current stock is required.',
            'stok_total.integer' => 'Stock must be a whole number.',
            'stok_total.min' => 'Stock must be greater than or equal to 0.',
            'barcode.unique' => 'This barcode is already in use.',
        ]);

        try {
            DB::beginTransaction();

            // Validate selling price is >= cost price
            if ($request->harga_jual < $request->hpp) {
                return back()->withInput()
                    ->with('error', 'Selling price must be greater than or equal to cost price (HPP).');
            }

            // Handle image upload
            $imagePath = $product->gambar_produk;
            if ($request->hasFile('gambar_produk')) {
                // Delete old image
                if ($product->gambar_produk && Storage::disk('public')->exists($product->gambar_produk)) {
                    Storage::disk('public')->delete($product->gambar_produk);
                }
                
                $image = $request->file('gambar_produk');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
            }

            // Update product
            $updateData = [
                'kd_produk_jenis' => $request->kd_produk_jenis,
                'kd_pemasok' => $request->kd_pemasok ?? 1,
                'nama_produk' => $request->nama_produk,
                'gambar_produk' => $imagePath,
                'material' => $request->material,
                'spesifik' => $request->spesifik,
                'ukuran' => $request->ukuran,
                'satuan' => $request->satuan,
                'berat' => $request->berat,
                'stok_total' => $request->stok_total,
                'hpp' => $request->hpp,
                'harga_jual' => $request->harga_jual,
                'pemasok' => $request->pemasok,
                'barcode' => $request->barcode,
                'date_updated' => now(),
            ];

            // Remove any null values
            $updateData = array_filter($updateData, function($value) {
                return $value !== null;
            });

            DB::table('produk')->where('kd_produk', $id)->update($updateData);
            
            // Refresh the product model
            $product->refresh();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating product: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete product image if exists
            if ($product->gambar_produk && Storage::disk('public')->exists($product->gambar_produk)) {
                Storage::disk('public')->delete($product->gambar_produk);
            }
            
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductData(Request $request)
    {
        try {
            // Create cache key based on search parameters
            $cacheKey = 'products_' . md5($request->get('search', '') . '_' . $request->get('per_page', 20) . '_' . $request->get('page', 1));
            
            // Check if we have cached results
            $cachedResults = cache()->get($cacheKey);
            if ($cachedResults) {
                return response()->json($cachedResults);
            }
            
            $query = Product::with('productType')
                ->select('kd_produk', 'nama_produk', 'harga_jual', 'stok_total', 'gambar_produk', 'kd_produk_jenis', 'barcode', 'pemasok');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_produk', 'like', '%' . $search . '%')
                      ->orWhere('barcode', 'like', '%' . $search . '%')
                      ->orWhere('kd_produk', 'like', '%' . $search . '%');
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 20);
            $products = $query->paginate($perPage);

            // Optimize stock calculation with a single query
            $productsWithStock = $products->items();
            $productIds = collect($productsWithStock)->pluck('kd_produk')->toArray();
            
            if (!empty($productIds)) {
                // Get all stock data in one query
                $stockData = DB::table('stok')
                    ->select('kd_produk', 
                        DB::raw('SUM(masuk) as total_in'), 
                        DB::raw('SUM(keluar) as total_out'))
                    ->whereIn('kd_produk', $productIds)
                    ->groupBy('kd_produk')
                    ->get()
                    ->keyBy('kd_produk');
                
                // Update products with calculated stock
                foreach ($productsWithStock as $product) {
                    if ($stockData->has($product->kd_produk)) {
                        $stock = $stockData->get($product->kd_produk);
                        $product->stok_total = ($stock->total_in ?? 0) - ($stock->total_out ?? 0);
                    } else {
                        // No stock records exist, use existing stok_total
                        $product->stok_total = $product->stok_total ?? 0;
                    }
                }
            }

            $result = [
                'success' => true,
                'products' => $productsWithStock,
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ];
            
            // Cache results for 2 minutes
            cache()->put($cacheKey, $result, 120);
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load products: ' . $e->getMessage()
            ], 500);
        }
    }
}
