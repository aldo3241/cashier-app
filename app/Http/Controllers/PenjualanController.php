<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\KeuanganKotak;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Create a new sale from cart data
     */
    public function createSale(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $request->validate([
                'cart' => 'required|array|min:1',
                'cart.*.kd_produk' => 'required|string',
                'cart.*.qty' => 'required|integer|min:1',
                'cart.*.harga' => 'required|numeric|min:0',
                'customer_id' => 'required|string',
                'payment_method' => 'required|string',
                'total_bayar' => 'required|numeric|min:0',
                'catatan' => 'nullable|string',
            ]);

            $cart = $request->cart;
            $customerId = $request->customer_id;
            $paymentMethod = $request->payment_method;
            $totalBayar = $request->total_bayar;
            $catatan = $request->catatan;

            // Generate invoice number and sale ID
            $invoiceNumber = Penjualan::generateInvoiceNumber();
            $saleId = Penjualan::generateSaleId();

            // Calculate totals
            $subTotal = 0;
            $pajak = 0; // Assuming no tax for now

            foreach ($cart as $item) {
                $itemSubtotal = ($item['harga'] * $item['qty']) - ($item['diskon'] ?? 0);
                $subTotal += $itemSubtotal;
            }

            $totalHarga = $subTotal + $pajak;
            $lebihBayar = $totalBayar - $totalHarga;

            // Create the sale
            $penjualan = Penjualan::create([
                'kd_penjualan' => $saleId,
                'no_faktur_penjualan' => $invoiceNumber,
                'kd_pelanggan' => $customerId,
                'sub_total' => $subTotal,
                'pajak' => $pajak,
                'total_harga' => $totalHarga,
                'total_bayar' => $totalBayar,
                'lebih_bayar' => $lebihBayar,
                'status_bayar' => 'Lunas',
                'keuangan_kotak' => $paymentMethod,
                'catatan' => $catatan,
                'status_barang' => 'diterima langsung',
                'dibuat_oleh' => auth()->user()->name ?? 'system',
                'date_created' => now(),
                'date_updated' => now(),
            ]);

            // Create sale details and update stock
            foreach ($cart as $item) {
                PenjualanDetail::createFromCartItem($saleId, $item, $invoiceNumber);
            }

            // Recalculate totals to ensure accuracy
            $penjualan->calculateTotals();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully',
                'data' => [
                    'sale_id' => $saleId,
                    'invoice_number' => $invoiceNumber,
                    'total_harga' => $totalHarga,
                    'total_bayar' => $totalBayar,
                    'lebih_bayar' => $lebihBayar,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all sales with pagination
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $search = $request->get('search', '');
            $statusBayar = $request->get('status_bayar', '');
            $startDate = $request->get('start_date', '');
            $endDate = $request->get('end_date', '');

            $query = Penjualan::with(['pelanggan', 'keuanganKotak', 'penjualanDetails.produk'])
                ->orderByDate();

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('no_faktur_penjualan', 'LIKE', "%{$search}%")
                      ->orWhere('kd_pelanggan', 'LIKE', "%{$search}%")
                      ->orWhereHas('pelanggan', function($q) use ($search) {
                          $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                            ->orWhere('nama_lembaga', 'LIKE', "%{$search}%");
                      });
                });
            }

            if ($statusBayar) {
                $query->where('status_bayar', $statusBayar);
            }

            if ($startDate && $endDate) {
                $query->dateRange($startDate, $endDate);
            }

            $sales = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $sales->map(function($sale) {
                    return [
                        'kd_penjualan' => $sale->kd_penjualan,
                        'no_faktur_penjualan' => $sale->no_faktur_penjualan,
                        'customer' => [
                            'id' => $sale->pelanggan->kd_pelanggan ?? null,
                            'name' => $sale->pelanggan->nama_lengkap ?? 'Unknown',
                            'organization' => $sale->pelanggan->nama_lembaga ?? null,
                        ],
                        'sub_total' => $sale->sub_total,
                        'pajak' => $sale->pajak,
                        'total_harga' => $sale->total_harga,
                        'total_bayar' => $sale->total_bayar,
                        'lebih_bayar' => $sale->lebih_bayar,
                        'status_bayar' => $sale->status_bayar,
                        'payment_method' => $sale->keuangan_kotak,
                        'catatan' => $sale->catatan,
                        'status_barang' => $sale->status_barang,
                        'dibuat_oleh' => $sale->dibuat_oleh,
                        'date_created' => $sale->date_created,
                        'items_count' => $sale->penjualanDetails->count(),
                        'items' => $sale->penjualanDetails->map(function($detail) {
                            return [
                                'kd_produk' => $detail->kd_produk,
                                'nama_produk' => $detail->nama_produk,
                                'qty' => $detail->qty,
                                'harga_jual' => $detail->harga_jual,
                                'diskon' => $detail->diskon,
                                'subtotal' => $detail->subtotal,
                            ];
                        })
                    ];
                }),
                'pagination' => [
                    'total' => $sales->total(),
                    'per_page' => $sales->perPage(),
                    'current_page' => $sales->currentPage(),
                    'last_page' => $sales->lastPage(),
                    'from' => $sales->firstItem(),
                    'to' => $sales->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting sales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sale by ID
     */
    public function show($id)
    {
        try {
            $sale = Penjualan::with(['pelanggan', 'keuanganKotak', 'penjualanDetails.produk'])
                ->find($id);

            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'kd_penjualan' => $sale->kd_penjualan,
                    'no_faktur_penjualan' => $sale->no_faktur_penjualan,
                    'customer' => [
                        'id' => $sale->pelanggan->kd_pelanggan ?? null,
                        'name' => $sale->pelanggan->nama_lengkap ?? 'Unknown',
                        'organization' => $sale->pelanggan->nama_lembaga ?? null,
                        'phone' => $sale->pelanggan->telp ?? null,
                        'address' => $sale->pelanggan->alamat ?? null,
                    ],
                    'sub_total' => $sale->sub_total,
                    'pajak' => $sale->pajak,
                    'total_harga' => $sale->total_harga,
                    'total_bayar' => $sale->total_bayar,
                    'lebih_bayar' => $sale->lebih_bayar,
                    'status_bayar' => $sale->status_bayar,
                    'payment_method' => $sale->keuangan_kotak,
                    'catatan' => $sale->catatan,
                    'status_barang' => $sale->status_barang,
                    'dibuat_oleh' => $sale->dibuat_oleh,
                    'date_created' => $sale->date_created,
                    'items' => $sale->penjualanDetails->map(function($detail) {
                        return [
                            'kd_penjualan_detail' => $detail->kd_penjualan_detail,
                            'kd_produk' => $detail->kd_produk,
                            'nama_produk' => $detail->nama_produk,
                            'produk_jenis' => $detail->produk_jenis,
                            'qty' => $detail->qty,
                            'hpp' => $detail->hpp,
                            'harga_jual' => $detail->harga_jual,
                            'diskon' => $detail->diskon,
                            'subtotal' => $detail->subtotal,
                            'laba' => $detail->laba,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment methods
     */
    public function getPaymentMethods()
    {
        try {
            $methods = KeuanganKotak::orderByName()->get();

            return response()->json([
                'success' => true,
                'data' => $methods->map(function($method) {
                    return [
                        'id' => $method->kd_keuangan_kotak,
                        'name' => $method->nama,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting payment methods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales statistics
     */
    public function getStats(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfDay());
            $endDate = $request->get('end_date', now()->endOfDay());

            $query = Penjualan::dateRange($startDate, $endDate);

            $totalSales = $query->count();
            $totalRevenue = $query->sum('total_harga');
            $totalProfit = $query->with('penjualanDetails')->get()->sum(function($sale) {
                return $sale->penjualanDetails->sum('laba');
            });

            $paymentMethods = $query->select('keuangan_kotak', DB::raw('count(*) as count'), DB::raw('sum(total_harga) as revenue'))
                ->groupBy('keuangan_kotak')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_sales' => $totalSales,
                    'total_revenue' => $totalRevenue,
                    'total_profit' => $totalProfit,
                    'average_sale' => $totalSales > 0 ? $totalRevenue / $totalSales : 0,
                    'payment_methods' => $paymentMethods->map(function($method) {
                        return [
                            'method' => $method->keuangan_kotak,
                            'count' => $method->count,
                            'revenue' => $method->revenue,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting sales statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
