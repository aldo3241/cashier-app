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

    public function getProductData()
    {
        $products = Product::with('productType')
            ->select('kd_produk', 'nama_produk', 'harga_jual', 'stok_total', 'gambar_produk', 'kd_produk_jenis')
            ->get();

        return response()->json([
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->kd_produk,
                    'name' => $product->nama_produk,
                    'price' => $product->harga_jual,
                    'stock' => $product->stok_total,
                    'image' => $product->gambar_produk,
                    'type' => $product->productType ? $product->productType->nama : 'N/A',
                    'stock_status' => $product->stock_status,
                    'stock_class' => $product->stock_status_class
                ];
            })
        ]);
    }
}
