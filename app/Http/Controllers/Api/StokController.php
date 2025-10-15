<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Get stock mutations by invoice number
     */
    public function getByInvoice($invoiceNumber)
    {
        try {
            $mutations = Stok::with('produk')
                ->where('no_ref', $invoiceNumber)
                ->orderBy('date_created', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $mutations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stock mutations: ' . $e->getMessage()
            ], 500);
        }
    }
}
