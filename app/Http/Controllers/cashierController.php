<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class cashierController extends Controller
{
    /**
     * Display cashier interface
     */
    public function index(Request $request)
    {
        // Get some featured products or recent products for initial display
        $featuredProducts = Produk::inStock()
            ->select([
                'kd_produk as id',
                'nama_produk as name', 
                'harga_jual as price',
                'kd_int',
                'kd_ext',
                'jenis as category',
                'stok_total as stock'
            ])
            ->limit(8)
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
                    'stock' => $product->stock
                ];
            });

        // Check if continuing an existing transaction
        $continueTransactionId = $request->get('continue');
        $startNewTransaction = $request->get('new');
        $continueTransaction = null;
        
        if ($continueTransactionId) {
            $userId = auth()->user()->username ?? auth()->user()->name ?? 'system';
            $continueTransaction = \App\Models\Penjualan::with(['penjualanDetails', 'pelanggan'])
                ->where('kd_penjualan', $continueTransactionId)
                ->where('dibuat_oleh', $userId)
                ->where('status_bayar', 'Belum Lunas')
                ->first();
            
            // Debug logging
            \Log::info('Continue Transaction Debug', [
                'transaction_id' => $continueTransactionId,
                'user_id' => $userId,
                'transaction_found' => $continueTransaction ? true : false,
                'transaction_data' => $continueTransaction ? $continueTransaction->toArray() : null,
                'penjualan_details_count' => $continueTransaction ? $continueTransaction->penjualanDetails->count() : 0,
                'first_detail' => $continueTransaction && $continueTransaction->penjualanDetails->count() > 0 ? $continueTransaction->penjualanDetails->first()->toArray() : null
            ]);
        }

        return view('cashier.cashier', [
            'featuredProducts' => $featuredProducts,
            'continueTransaction' => $continueTransaction,
            'startNewTransaction' => $startNewTransaction
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
