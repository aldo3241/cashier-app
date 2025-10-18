<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display the receipt for printing
     */
    public function print($id)
    {
        $transaction = Penjualan::with(['penjualanDetails.produk', 'pelanggan'])
            ->where('kd_penjualan', $id)
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('receipt.print', compact('transaction'));
    }

    /**
     * Print receipt for a specific invoice number
     */
    public function printByInvoice($invoiceNumber)
    {
        $transaction = Penjualan::with(['penjualanDetails.produk', 'pelanggan'])
            ->where('no_faktur_penjualan', $invoiceNumber)
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('receipt.print', compact('transaction'));
    }

    /**
     * Print the latest transaction for a user
     */
    public function printLatest(Request $request)
    {
        $userId = auth()->id();
        
        $transaction = Penjualan::with(['penjualanDetails.produk', 'pelanggan'])
            ->where('dibuat_oleh', $userId)
            ->where('status_bayar', 'Lunas')
            ->latest('date_created')
            ->first();

        if (!$transaction) {
            abort(404, 'No completed transaction found');
        }

        return view('receipt.print', compact('transaction'));
    }
}
