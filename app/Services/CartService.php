<?php

namespace App\Services;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartService
{
    /**
     * Get or create active cart for user and customer
     */
    public function getActiveCart($userId, $customerId = 1)
    {
        // Look for existing draft cart
        $cart = Penjualan::where('dibuat_oleh', $userId)
            ->where('kd_pelanggan', $customerId)
            ->where('status_bayar', 'Belum Lunas')
            ->where('status_barang', 'Draft')
            ->with(['penjualanDetails.produk'])
            ->first();

        return $cart;
    }

    /**
     * Create a new fresh cart (for new transactions)
     */
    public function createFreshCart($userId, $customerId = 1)
    {
        // Delete any existing draft cart for this user/customer combination
        Penjualan::where('dibuat_oleh', $userId)
            ->where('kd_pelanggan', $customerId)
            ->where('status_bayar', 'Belum Lunas')
            ->where('status_barang', 'Draft')
            ->delete();

        // Return null, forcing lazy creation on first item add
        return null;
    }

    /**
     * Get specific cart by ID for continuing transactions
     */
    public function getCartById($cartId, $userId)
    {
        $cart = Penjualan::where('kd_penjualan', $cartId)
            ->where('dibuat_oleh', $userId)
            ->where('status_bayar', 'Belum Lunas')
            ->with(['penjualanDetails.produk', 'pelanggan'])
            ->first();

        if (!$cart) {
            throw new \Exception('Transaction not found or already completed');
        }

        return $cart;
    }

    /**
     * Create a new draft cart
     */
    public function createDraftCart($userId, $customerId = 1)
    {
        // Generate invoice number (kd_penjualan will be auto-generated)
        $invoiceNumber = \App\Models\Penjualan::generateNewInvoiceNumber($userId);

        return Penjualan::create([
            'no_faktur_penjualan' => $invoiceNumber,
            'kd_pelanggan' => $customerId,
            'sub_total' => 0,
            'pajak' => 0,
            'total_harga' => 0,
            'total_bayar' => 0,
            'lebih_bayar' => 0,
            'status_bayar' => 'Belum Lunas',
            'keuangan_kotak' => null,
            'catatan' => null,
            'status_barang' => 'Draft',
            'dibuat_oleh' => $userId,
            'date_created' => now(),
            'date_updated' => now(),
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart($userId, $customerId, $productId, $qty = 1, $cartId = null)
    {
        return DB::transaction(function() use ($userId, $customerId, $productId, $qty, $cartId) {

            // Get specific cart if provided, otherwise get active cart
            if ($cartId) {
                $cart = $this->getCartById($cartId, $userId);
            } else {
                $cart = $this->getActiveCart($userId, $customerId);
            }

            // If no cart exists (because getActiveCart returned null), create a new draft cart (lazy creation).
            if (!$cart) {
                $cart = $this->createDraftCart($userId, $customerId);
            }

            // Lock the product for stock validation to prevent race conditions
            $produk = Produk::lockForUpdate()->find($productId);
            if (!$produk) {
                throw new \Exception('Product not found');
            }

            // Check stock availability using the locked product
            if ($produk->stok_total < $qty) {
                throw new \Exception("Insufficient stock. Available: {$produk->stok_total}, Requested: {$qty}");
            }

            // Check if item already exists in cart
            $existingItem = PenjualanDetail::where('kd_penjualan', $cart->kd_penjualan)
                ->where('kd_produk', $productId)
                ->first();

            if ($existingItem) {
                // Update existing item
                $newQty = $existingItem->qty + $qty;

                // Check stock again with new total using locked product
                if ($produk->stok_total < $newQty) {
                    throw new \Exception("Insufficient stock. Available: {$produk->stok_total}, Requested: {$newQty}");
                }

                $existingItem->qty = $newQty;
                $existingItem->sub_total = ($existingItem->harga_jual * $newQty) - $existingItem->diskon; // Calculate sub_total
                $existingItem->laba = ($existingItem->harga_jual - $existingItem->hpp) * $newQty;
                $existingItem->date_updated = now();
                $existingItem->save();
            } else {
                // Create new item
                PenjualanDetail::create([
                    'kd_penjualan' => $cart->kd_penjualan,
                    'kd_produk' => $productId,
                    'nama_produk' => $produk->nama_produk,
                    'produk_jenis' => $produk->kd_produk_jenis,
                    'kd_pemasok' => $produk->kd_pemasok,
                    'pemasok' => $produk->pemasok,
                    'sistem_bayar' => null,
                    'hpp' => $produk->hpp ?? 0,
                    'harga_jual' => $produk->harga_jual,
                    'qty' => $qty,
                    'diskon' => 0,
                    'sub_total' => ($produk->harga_jual * $qty) - 0, // Calculate sub_total (assuming 0 discount initially)
                    'laba' => ($produk->harga_jual - ($produk->hpp ?? 0)) * $qty,
                    'status_bayar' => 'Belum Lunas',
                    'catatan' => null,
                    'dibuat_oleh' => $userId,
                    'no_faktur_penjualan' => $cart->no_faktur_penjualan,
                    'date_created' => now(),
                    'date_updated' => now(),
                ]);
            }

            // Recalculate cart totals
            $this->recalculateCart($cart->kd_penjualan);

            return $this->getCartDetails($cart->kd_penjualan);
        });
    }

    /**
     * Update item quantity in cart
     */
    public function updateCartItem($userId, $customerId, $productId, $qty, $cartId = null)
    {
        return DB::transaction(function() use ($userId, $customerId, $productId, $qty, $cartId) {

            // Get specific cart if provided, otherwise get active cart
            if ($cartId) {
                $cart = $this->getCartById($cartId, $userId);
            } else {
                $cart = $this->getActiveCart($userId, $customerId);
            }

            if ($qty <= 0) {
                return $this->removeFromCart($userId, $customerId, $productId, $cartId);
            }

            // Lock the product for stock validation to prevent race conditions
            $produk = Produk::lockForUpdate()->find($productId);
            if (!$produk) {
                throw new \Exception('Product not found');
            }

            // Check stock availability using the locked product
            if ($produk->stok_total < $qty) {
                throw new \Exception("Insufficient stock. Available: {$produk->stok_total}, Requested: {$qty}");
            }

            $item = PenjualanDetail::where('kd_penjualan', $cart->kd_penjualan)
                ->where('kd_produk', $productId)
                ->first();

            if (!$item) {
                throw new \Exception('Item not found in cart');
            }

            $item->qty = $qty;
            $item->sub_total = ($item->harga_jual * $qty) - $item->diskon; // Calculate sub_total
            $item->laba = ($item->harga_jual - $item->hpp) * $qty;
            $item->date_updated = now();
            $item->save();

            // Recalculate cart totals
            $this->recalculateCart($cart->kd_penjualan);

            return $this->getCartDetails($cart->kd_penjualan);
        });
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($userId, $customerId, $productId, $cartId = null)
    {
        return DB::transaction(function() use ($userId, $customerId, $productId, $cartId) {

            // Get specific cart if provided, otherwise get active cart
            if ($cartId) {
                $cart = $this->getCartById($cartId, $userId);
            } else {
                $cart = $this->getActiveCart($userId, $customerId);
            }

            $item = PenjualanDetail::where('kd_penjualan', $cart->kd_penjualan)
                ->where('kd_produk', $productId)
                ->first();

            if ($item) {
                $item->delete();
            }

            // Recalculate cart totals
            $this->recalculateCart($cart->kd_penjualan);

            return $this->getCartDetails($cart->kd_penjualan);
        });
    }

    /**
     * Clear entire cart
     */
    public function clearCart($userId, $customerId, $cartId = null)
    {
        try {
            DB::beginTransaction();

            // Get specific cart if provided, otherwise get active cart
            if ($cartId) {
                $cart = $this->getCartById($cartId, $userId);
            } else {
                $cart = $this->getActiveCart($userId, $customerId);
            }

            if (!$cart) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'No active cart found to clear.',
                    'cart_id' => null,
                    'items' => []
                ];
            }

            // Delete all cart items first
            PenjualanDetail::where('kd_penjualan', $cart->kd_penjualan)->delete();

            // Delete the cart record itself
            $cart->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_id' => null,
                'items' => []
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Recalculate cart totals
     */
    public function recalculateCart($cartId)
    {
        $cart = Penjualan::find($cartId);
        if (!$cart) return;

        // Calculate subtotal manually since database doesn't have subtotal column
        $details = PenjualanDetail::where('kd_penjualan', $cartId)->get();
        $subTotal = $details->sum(function($item) {
            return ($item->harga_jual * $item->qty) - $item->diskon;
        });
        $pajak = 0; // No tax for now
        $totalHarga = $subTotal + $pajak;

        $cart->sub_total = $subTotal;
        $cart->pajak = $pajak;
        $cart->total_harga = $totalHarga;
        $cart->total_bayar = $totalHarga; // Set total_bayar equal to total_harga
        $cart->lebih_bayar = 0; // Set lebih_bayar to 0
        $cart->date_updated = now();
        $cart->save();
    }

    /**
     * Get cart details with items
     */
    public function getCartDetails($cartId)
    {
        $cart = Penjualan::with(['pelanggan', 'penjualanDetails.produk'])
            ->find($cartId);

        if (!$cart) {
            return null;
        }

        return [
            'cart_id' => $cart->kd_penjualan,
            'invoice_number' => $cart->no_faktur_penjualan,
            'customer' => [
                'id' => $cart->pelanggan->kd_pelanggan ?? null,
                'name' => $cart->pelanggan->nama_lengkap ?? 'Unknown',
                'organization' => $cart->pelanggan->nama_lembaga ?? null,
            ],
            'sub_total' => $cart->sub_total,
            'pajak' => $cart->pajak,
            'total_harga' => $cart->total_harga,
            'total_bayar' => $cart->total_bayar,
            'lebih_bayar' => $cart->lebih_bayar,
            'item_count' => $cart->penjualanDetails->count(),
            'items' => $cart->penjualanDetails->map(function($item) {
                return [
                    'kd_produk' => $item->kd_produk,
                    'nama_produk' => $item->nama_produk,
                    'qty' => $item->qty,
                    'harga_jual' => $item->harga_jual,
                    'diskon' => $item->diskon,
                    'subtotal' => $item->subtotal,
                    'laba' => $item->laba,
                ];
            }),
            'created_at' => $cart->date_created,
            'updated_at' => $cart->date_updated,
        ];
    }

    /**
     * Convert cart to completed sale
     */
    public function checkoutCart($userId, $customerId, $paymentMethod, $totalBayar, $catatan = null, $statusBarang = 'diterima langsung', $cartId = null)
    {
        return DB::transaction(function() use ($userId, $customerId, $paymentMethod, $totalBayar, $catatan, $statusBarang, $cartId) {

            // 1. Get specific cart if provided, otherwise get active cart
            if ($cartId) {
                $cart = $this->getCartById($cartId, $userId);
            } else {
                $cart = $this->getActiveCart($userId, $customerId);
            }

            // 2. Reuse the existing draft invoice number. This number was generated
            //    when the first item was added (CartService::createDraftCart).
            $finalInvoiceNumber = $cart->no_faktur_penjualan;

            // 3. No need to lock for ID or generate ID based invoice number anymore.

            if ($cart->penjualanDetails->count() == 0) {
                throw new \Exception('Cart is empty');
            }

            // 4. Validate and update stock for all items with locking
            foreach ($cart->penjualanDetails as $item) {
                // Lock the product for stock update to prevent race conditions
                $product = Produk::lockForUpdate()->find($item->kd_produk);

                if (!$product) {
                    throw new \Exception("Product not found: {$item->kd_produk}");
                }

                // Check stock availability
                if ($product->stok_total < $item->qty) {
                    throw new \Exception("Insufficient stock for product {$product->nama_produk}. Available: {$product->stok_total}, Required: {$item->qty}");
                }

                // Update stock atomically
                $product->stok_total -= $item->qty;
                $product->date_updated = now();
                $product->save();

                // Create stock mutation record
                Stok::reduceStock(
                    $item->kd_produk,
                    $item->qty,
                    'Penjualan',
                    $finalInvoiceNumber,
                    "Sale: {$finalInvoiceNumber}",
                    $userId
                );
            }

            // 5. Update cart to completed sale
            $cart->no_faktur_penjualan = $finalInvoiceNumber;
            $cart->total_bayar = $cart->total_harga; // total_bayar must be equal to total_harga
            $cart->lebih_bayar = 0; // lebih_bayar must be 0
            $cart->status_bayar = 'Lunas';
            $cart->keuangan_kotak = $paymentMethod;
            $cart->catatan = $catatan;
            $cart->status_barang = $statusBarang;
            $cart->date_updated = now();
            $cart->save();

            // 6. Update all detail items
            PenjualanDetail::where('kd_penjualan', $cart->kd_penjualan)
                ->update([
                    'status_bayar' => 'Lunas',
                    'no_faktur_penjualan' => $finalInvoiceNumber,
                    'date_updated' => now(),
                ]);

            // 7. Create financial mutation (mutasi keuangan)
            \App\Models\Keuangan::createSaleMutation($cart);

            return [
                'success' => true,
                'sale_id' => $cart->kd_penjualan,
                'invoice_number' => $finalInvoiceNumber,
                'total_harga' => $cart->total_harga,
                'total_bayar' => $cart->total_bayar,
                'lebih_bayar' => $cart->lebih_bayar,
            ];
        });
    }

    /**
     * Clean up old draft carts (older than 24 hours)
     */
    public function cleanupOldCarts()
    {
        $cutoffTime = now()->subHours(24);

        $oldCarts = Penjualan::where('status_bayar', 'Belum Lunas')
            ->where('status_barang', 'Draft')
            ->where('date_created', '<', $cutoffTime)
            ->get();

        foreach ($oldCarts as $cart) {
            // Delete cart items first
            PenjualanDetail::where('kd_penjualan', $cart->kd_penjualan)->delete();
            // Delete cart
            $cart->delete();
        }

        return $oldCarts->count();
    }

    /**
     * Get all draft transactions for a user
     */
    public function getDraftTransactions($userId)
    {
        $drafts = Penjualan::where('dibuat_oleh', $userId)
            ->where('status_bayar', 'Belum Lunas')
            ->where('status_barang', 'Draft')
            ->with(['penjualanDetails.produk', 'pelanggan'])
            ->orderBy('date_created', 'desc')
            ->get();

        return $drafts->map(function ($draft) {
            $itemCount = $draft->penjualanDetails->sum('qty');
            $subTotal = $draft->penjualanDetails->sum(function ($item) {
                return ($item->harga_jual * $item->qty) - $item->diskon;
            });

            return [
                'id' => $draft->kd_penjualan,
                'invoice_number' => $draft->no_faktur_penjualan,
                'customer_name' => $draft->pelanggan ? $draft->pelanggan->nama_lengkap : 'Pelanggan Umum',
                'customer_code' => $draft->pelanggan ? $draft->pelanggan->kd_pelanggan : 1,
                'item_count' => $itemCount,
                'sub_total' => $subTotal,
                'total_harga' => $draft->total_harga,
                'created_at' => $draft->date_created,
                'updated_at' => $draft->date_updated,
                'items' => $draft->penjualanDetails->map(function ($item) {
                    return [
                        'kd_produk' => $item->kd_produk,
                        'nama_produk' => $item->nama_produk,
                        'harga_jual' => $item->harga_jual,
                        'qty' => $item->qty,
                        'subtotal' => ($item->harga_jual * $item->qty) - $item->diskon,
                    ];
                })
            ];
        });
    }

    /**
     * Switch to a specific draft transaction
     */
    public function switchToDraft($userId, $draftId)
    {
        try {
            DB::beginTransaction();

            // Verify the draft belongs to the user
            $draft = Penjualan::where('kd_penjualan', $draftId)
                ->where('dibuat_oleh', $userId)
                ->where('status_bayar', 'Belum Lunas')
                ->where('status_barang', 'Draft')
                ->first();

            if (!$draft) {
                throw new \Exception('Draft transaction not found or access denied');
            }

            // Get the cart details
            $cartDetails = $this->getCartDetails($draftId);

            DB::commit();

            return $cartDetails;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a draft transaction
     */
    public function deleteDraft($userId, $draftId)
    {
        try {
            DB::beginTransaction();

            // Verify the draft belongs to the user
            $draft = Penjualan::where('kd_penjualan', $draftId)
                ->where('dibuat_oleh', $userId)
                ->where('status_bayar', 'Belum Lunas')
                ->where('status_barang', 'Draft')
                ->first();

            if (!$draft) {
                throw new \Exception('Draft transaction not found or access denied');
            }

            // Delete all related details first
            PenjualanDetail::where('kd_penjualan', $draftId)->delete();

            // Delete the draft
            $draft->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

