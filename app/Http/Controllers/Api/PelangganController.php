<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Search customers for cashier
     * Database: pelanggan (kd_pelanggan, panggilan, nama_lengkap, nama_lembaga, telp, alamat, kecamatan, kotakab, provinsi, negara, kode_pos, catatan, date_updated, dibuat_oleh, date_created)
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $limit = $request->get('limit', 10);

            if (empty($search)) {
                // If no search query, return all customers
                $customers = Pelanggan::orderByName()
                    ->limit($limit)
                    ->get();
            } else {
                // If search query provided, use search scope
                $customers = Pelanggan::search($search)
                    ->orderByName()
                    ->limit($limit)
                    ->get();
            }

            $customers = $customers->map(function($customer) {
                    return [
                        'id' => $customer->kd_pelanggan,
                        'kd_pelanggan' => $customer->kd_pelanggan,
                        'panggilan' => $customer->panggilan,
                        'nama_lengkap' => $customer->nama_lengkap,
                        'nama_lembaga' => $customer->nama_lembaga,
                        'telp' => $customer->telp,
                        'alamat' => $customer->alamat,
                        'kecamatan' => $customer->kecamatan,
                        'kotakab' => $customer->kotakab,
                        'provinsi' => $customer->provinsi,
                        'negara' => $customer->negara,
                        'kode_pos' => $customer->kode_pos,
                        'catatan' => $customer->catatan,

                        // Computed fields for compatibility
                        'code' => $customer->kd_pelanggan,
                        'name' => $customer->nama_lengkap,
                        'display_name' => $customer->display_name,
                        'title' => $customer->panggilan,
                        'organization' => $customer->nama_lembaga,
                        'phone' => $customer->telp,
                        'formatted_phone' => $customer->formatted_phone,
                        'full_address' => $customer->full_address,
                        'type' => $customer->type,
                        'identifier' => $customer->identifier
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $customers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer by ID
     */
    public function getById(Request $request)
    {
        try {
            $id = $request->get('id');

            $customer = Pelanggan::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    // Direct database fields
                    'id' => $customer->kd_pelanggan,
                    'kd_pelanggan' => $customer->kd_pelanggan,
                    'panggilan' => $customer->panggilan,
                    'nama_lengkap' => $customer->nama_lengkap,
                    'nama_lembaga' => $customer->nama_lembaga,
                    'telp' => $customer->telp,
                    'alamat' => $customer->alamat,
                    'kecamatan' => $customer->kecamatan,
                    'kotakab' => $customer->kotakab,
                    'provinsi' => $customer->provinsi,
                    'negara' => $customer->negara,
                    'kode_pos' => $customer->kode_pos,
                    'catatan' => $customer->catatan,
                    'dibuat_oleh' => $customer->dibuat_oleh,
                    'date_created' => $customer->date_created,
                    'date_updated' => $customer->date_updated,

                    // Computed fields for compatibility
                    'code' => $customer->kd_pelanggan,
                    'name' => $customer->nama_lengkap,
                    'display_name' => $customer->display_name,
                    'title' => $customer->panggilan,
                    'organization' => $customer->nama_lembaga,
                    'phone' => $customer->telp,
                    'formatted_phone' => $customer->formatted_phone,
                    'full_address' => $customer->full_address,
                    'type' => $customer->type,
                    'identifier' => $customer->identifier
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all customers (with pagination)
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 50);
            $search = $request->get('search', '');

            $query = Pelanggan::orderByName();

            if ($search) {
                $query->search($search);
            }

            $customers = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $customers->map(function($customer) {
                    return [
                        // Direct database fields
                        'id' => $customer->kd_pelanggan,
                        'kd_pelanggan' => $customer->kd_pelanggan,
                        'panggilan' => $customer->panggilan,
                        'nama_lengkap' => $customer->nama_lengkap,
                        'nama_lembaga' => $customer->nama_lembaga,
                        'telp' => $customer->telp,
                        'alamat' => $customer->alamat,
                        'kecamatan' => $customer->kecamatan,
                        'kotakab' => $customer->kotakab,
                        'provinsi' => $customer->provinsi,
                        'negara' => $customer->negara,
                        'kode_pos' => $customer->kode_pos,
                        'catatan' => $customer->catatan,

                        // Computed fields for compatibility
                        'code' => $customer->kd_pelanggan,
                        'name' => $customer->nama_lengkap,
                        'display_name' => $customer->display_name,
                        'organization' => $customer->nama_lembaga,
                        'phone' => $customer->formatted_phone,
                        'full_address' => $customer->full_address,
                        'type' => $customer->type,
                        'identifier' => $customer->identifier
                    ];
                }),
                'pagination' => [
                    'total' => $customers->total(),
                    'per_page' => $customers->perPage(),
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'from' => $customers->firstItem(),
                    'to' => $customers->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer statistics
     */
    public function stats()
    {
        try {
            $totalCustomers = Pelanggan::count();
            $organizationCustomers = Pelanggan::whereNotNull('nama_lembaga')
                ->where('nama_lembaga', '!=', '')
                ->count();
            $personalCustomers = $totalCustomers - $organizationCustomers;

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $totalCustomers,
                    'organization' => $organizationCustomers,
                    'personal' => $personalCustomers
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting customer statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get default walk-in customer (#PLG1)
     */
    public function getDefault()
    {
        try {
            $customer = Pelanggan::getDefaultCustomer();

            return response()->json([
                'success' => true,
                'data' => [
                    // Direct database fields
                    'id' => $customer->kd_pelanggan,
                    'kd_pelanggan' => $customer->kd_pelanggan,
                    'panggilan' => $customer->panggilan,
                    'nama_lengkap' => $customer->nama_lengkap,
                    'nama_lembaga' => $customer->nama_lembaga,
                    'telp' => $customer->telp,
                    'alamat' => $customer->alamat,
                    'kecamatan' => $customer->kecamatan,
                    'kotakab' => $customer->kotakab,
                    'provinsi' => $customer->provinsi,
                    'negara' => $customer->negara,
                    'kode_pos' => $customer->kode_pos,
                    'catatan' => $customer->catatan,
                    'dibuat_oleh' => $customer->dibuat_oleh,
                    'date_created' => $customer->date_created,
                    'date_updated' => $customer->date_updated,

                    // Computed fields for compatibility
                    'code' => $customer->kd_pelanggan,
                    'name' => $customer->nama_lengkap,
                    'display_name' => $customer->display_name,
                    'title' => $customer->panggilan,
                    'organization' => $customer->nama_lembaga,
                    'phone' => $customer->telp,
                    'formatted_phone' => $customer->formatted_phone,
                    'full_address' => $customer->full_address,
                    'type' => $customer->type,
                    'identifier' => $customer->identifier,

                    // Additional flag
                    'is_default' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting default customer: ' . $e->getMessage()
            ], 500);
        }
    }
}

