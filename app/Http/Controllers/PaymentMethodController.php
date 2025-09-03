<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentMethodController extends Controller
{
    /**
     * Get payment methods with pagination and search
     */
    public function getPaymentMethods(Request $request)
    {
        try {
            $query = DB::table('metode_pembayaran');

            // Search functionality
            $search = $request->get('search');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_metode_pembayaran', 'like', "%{$search}%")
                      ->orWhere('jenis_pembayaran', 'like', "%{$search}%")
                      ->orWhere('nama_bank', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 20);
            $paymentMethods = $query->orderBy('created_at', 'desc')
                                   ->paginate($perPage);

            return response()->json([
                'success' => true,
                'payment_methods' => $paymentMethods->items(),
                'current_page' => $paymentMethods->currentPage(),
                'last_page' => $paymentMethods->lastPage(),
                'per_page' => $paymentMethods->perPage(),
                'total' => $paymentMethods->total()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load payment methods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single payment method
     */
    public function getPaymentMethod($id)
    {
        try {
            $paymentMethod = DB::table('metode_pembayaran')
                ->where('kd_metode_pembayaran', $id)
                ->first();

            if (!$paymentMethod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'payment_method' => $paymentMethod
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load payment method: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new payment method
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_metode_pembayaran' => 'required|string|max:255',
                'jenis_pembayaran' => 'required|string|max:100',
                'nomor_rekening' => 'nullable|string|max:50',
                'nama_bank' => 'nullable|string|max:100',
                'deskripsi' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            // Check if payment method name already exists
            $existing = DB::table('metode_pembayaran')
                ->where('nama_metode_pembayaran', $request->input('nama_metode_pembayaran'))
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method name already exists'
                ], 422);
            }

            $id = DB::table('metode_pembayaran')->insertGetId([
                'nama_metode_pembayaran' => $request->input('nama_metode_pembayaran'),
                'jenis_pembayaran' => $request->input('jenis_pembayaran'),
                'nomor_rekening' => $request->input('nomor_rekening'),
                'nama_bank' => $request->input('nama_bank'),
                'deskripsi' => $request->input('deskripsi'),
                'is_active' => $request->input('is_active', true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method created successfully',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment method: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment method
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_metode_pembayaran' => 'required|string|max:255',
                'jenis_pembayaran' => 'required|string|max:100',
                'nomor_rekening' => 'nullable|string|max:50',
                'nama_bank' => 'nullable|string|max:100',
                'deskripsi' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            $paymentMethod = DB::table('metode_pembayaran')
                ->where('kd_metode_pembayaran', $id)
                ->first();

            if (!$paymentMethod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found'
                ], 404);
            }

            // Check if payment method name already exists (excluding current one)
            $existing = DB::table('metode_pembayaran')
                ->where('nama_metode_pembayaran', $request->input('nama_metode_pembayaran'))
                ->where('kd_metode_pembayaran', '!=', $id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method name already exists'
                ], 422);
            }

            DB::table('metode_pembayaran')
                ->where('kd_metode_pembayaran', $id)
                ->update([
                    'nama_metode_pembayaran' => $request->input('nama_metode_pembayaran'),
                    'jenis_pembayaran' => $request->input('jenis_pembayaran'),
                    'nomor_rekening' => $request->input('nomor_rekening'),
                    'nama_bank' => $request->input('nama_bank'),
                    'deskripsi' => $request->input('deskripsi'),
                    'is_active' => $request->input('is_active', true),
                    'updated_at' => Carbon::now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment method: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete payment method
     */
    public function destroy($id)
    {
        try {
            $paymentMethod = DB::table('metode_pembayaran')
                ->where('kd_metode_pembayaran', $id)
                ->first();

            if (!$paymentMethod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found'
                ], 404);
            }

            // Check if payment method is being used in sales
            $usedInSales = DB::table('penjualan')
                ->where('metode_pembayaran', $paymentMethod->nama_metode_pembayaran)
                ->exists();

            if ($usedInSales) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete payment method that is being used in sales transactions'
                ], 422);
            }

            DB::table('metode_pembayaran')
                ->where('kd_metode_pembayaran', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment method deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment method: ' . $e->getMessage()
            ], 500);
        }
    }
}
