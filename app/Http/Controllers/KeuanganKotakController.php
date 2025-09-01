<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KeuanganKotakController extends Controller
{
    /**
     * Display a listing of payment methods
     */
    public function index()
    {
        return view('keuangan-kotak.index');
    }

    /**
     * Get all payment methods for AJAX
     */
    public function getAllPaymentMethods(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);
            $search = $request->input('search', '');

            $query = DB::table('keuangan_kotak');

            if ($search) {
                $query->where('nama', 'LIKE', "%{$search}%");
            }

            $total = $query->count();
            $offset = ($page - 1) * $perPage;
            
            $paymentMethods = $query->orderBy('nama', 'asc')
                                   ->offset($offset)
                                   ->limit($perPage)
                                   ->get();

            $lastPage = ceil($total / $perPage);

            return response()->json([
                'success' => true,
                'payment_methods' => $paymentMethods,
                'pagination' => [
                    'current_page' => (int)$page,
                    'last_page' => $lastPage,
                    'per_page' => (int)$perPage,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment methods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created payment method
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:keuangan_kotak,nama'
            ], [
                'nama.required' => 'Payment method name is required',
                'nama.unique' => 'This payment method name already exists'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $paymentMethodId = DB::table('keuangan_kotak')->insertGetId([
                'nama' => $request->nama,
                'date_created' => now(),
                'date_updated' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method created successfully',
                'payment_method' => [
                    'kd_keuangan_kotak' => $paymentMethodId,
                    'nama' => $request->nama,
                    'date_created' => now(),
                    'date_updated' => now()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment method: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific payment method for editing
     */
    public function show($id)
    {
        try {
            $paymentMethod = DB::table('keuangan_kotak')
                               ->where('kd_keuangan_kotak', $id)
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
                'message' => 'Failed to get payment method: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified payment method
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:keuangan_kotak,nama,' . $id . ',kd_keuangan_kotak'
            ], [
                'nama.required' => 'Payment method name is required',
                'nama.unique' => 'This payment method name already exists'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updated = DB::table('keuangan_kotak')
                         ->where('kd_keuangan_kotak', $id)
                         ->update([
                             'nama' => $request->nama,
                             'date_updated' => now()
                         ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found or no changes made'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment method updated successfully',
                'payment_method' => [
                    'kd_keuangan_kotak' => $id,
                    'nama' => $request->nama,
                    'date_updated' => now()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment method: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified payment method
     */
    public function destroy($id)
    {
        try {
            // Check if payment method is being used in sales
            $usageCount = DB::table('penjualan')
                            ->where('keuangan_kotak', $id)
                            ->count();

            if ($usageCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete payment method. It is being used in ' . $usageCount . ' sales record(s).'
                ], 422);
            }

            $deleted = DB::table('keuangan_kotak')
                         ->where('kd_keuangan_kotak', $id)
                         ->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found'
                ], 404);
            }

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

    /**
     * Get all payment methods for dropdowns (no pagination)
     */
    public function getAllForDropdown()
    {
        try {
            $paymentMethods = DB::table('keuangan_kotak')
                               ->orderBy('nama', 'asc')
                               ->get(['kd_keuangan_kotak', 'nama']);

            return response()->json([
                'success' => true,
                'payment_methods' => $paymentMethods
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment methods: ' . $e->getMessage()
            ], 500);
        }
    }
}

