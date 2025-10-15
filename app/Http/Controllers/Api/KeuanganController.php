<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /**
     * Show financial mutation details
     */
    public function show($id)
    {
        try {
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial mutation not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $keuangan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching financial mutation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get financial mutation by invoice number
     */
    public function getByInvoice($invoiceNumber)
    {
        try {
            $keuangan = Keuangan::where('referensi', $invoiceNumber)->first();
            
            if (!$keuangan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial mutation not found for invoice: ' . $invoiceNumber
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $keuangan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching financial mutation: ' . $e->getMessage()
            ], 500);
        }
    }
}
