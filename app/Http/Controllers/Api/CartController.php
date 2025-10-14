<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get current cart
     */
    public function getCart(Request $request)
    {
        try {
            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);

            $cart = $this->cartService->getActiveCart($userId, $customerId);

            return response()->json([
                'success' => true,
                'data' => $this->cartService->getCartDetails($cart->kd_penjualan)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required',
                'qty' => 'required|integer|min:1',
                'customer_id' => 'nullable',
            ]);

            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);
            $productId = $request->product_id;
            $qty = $request->qty;

            $cartDetails = $this->cartService->addToCart($userId, $customerId, $productId, $qty);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => $cartDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update item quantity in cart
     */
    public function updateCartItem(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required',
                'qty' => 'required|integer|min:0',
                'customer_id' => 'nullable',
            ]);

            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);
            $productId = $request->product_id;
            $qty = $request->qty;

            $cartDetails = $this->cartService->updateCartItem($userId, $customerId, $productId, $qty);

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'data' => $cartDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required',
                'customer_id' => 'nullable',
            ]);

            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);
            $productId = $request->product_id;

            $cartDetails = $this->cartService->removeFromCart($userId, $customerId, $productId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'data' => $cartDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing from cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart(Request $request)
    {
        try {
            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);

            $cartDetails = $this->cartService->clearCart($userId, $customerId);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'data' => $cartDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Checkout cart (convert to completed sale)
     */
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'payment_method' => 'required|string',
                'total_bayar' => 'required|numeric|min:0',
                'customer_id' => 'nullable',
                'catatan' => 'nullable|string',
                'status_barang' => 'required|string|in:diterima langsung,dikirimkan ekspedisi',
            ]);

            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);
            $paymentMethod = $request->payment_method;
            $totalBayar = $request->total_bayar;
            $catatan = $request->get('catatan');
            $statusBarang = $request->get('status_barang', 'diterima langsung');

            $result = $this->cartService->checkoutCart($userId, $customerId, $paymentMethod, $totalBayar, $catatan, $statusBarang);

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart statistics
     */
    public function getStats(Request $request)
    {
        try {
            $userId = Auth::user()->name ?? 'system';
            $customerId = $request->get('customer_id', 1);

            $cart = $this->cartService->getActiveCart($userId, $customerId);
            $cartDetails = $this->cartService->getCartDetails($cart->kd_penjualan);

            return response()->json([
                'success' => true,
                'data' => [
                    'item_count' => $cartDetails['item_count'],
                    'sub_total' => $cartDetails['sub_total'],
                    'total_harga' => $cartDetails['total_harga'],
                    'cart_id' => $cartDetails['cart_id'],
                    'customer' => $cartDetails['customer'],
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting cart stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all draft transactions for current user
     */
    public function getDraftTransactions()
    {
        try {
            $userId = Auth::user()->name ?? 'system';
            $drafts = $this->cartService->getDraftTransactions($userId);

            return response()->json([
                'success' => true,
                'data' => $drafts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting draft transactions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Switch to a specific draft transaction
     */
    public function switchToDraft(Request $request)
    {
        try {
            $request->validate([
                'draft_id' => 'required|integer',
            ]);

            $userId = Auth::user()->name ?? 'system';
            $draftId = $request->draft_id;

            $cartDetails = $this->cartService->switchToDraft($userId, $draftId);

            return response()->json([
                'success' => true,
                'message' => 'Switched to draft transaction',
                'data' => $cartDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error switching to draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a draft transaction
     */
    public function deleteDraft(Request $request)
    {
        try {
            $request->validate([
                'draft_id' => 'required|integer',
            ]);

            $userId = Auth::user()->name ?? 'system';
            $draftId = $request->draft_id;

            $this->cartService->deleteDraft($userId, $draftId);

            return response()->json([
                'success' => true,
                'message' => 'Draft transaction deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting draft: ' . $e->getMessage()
            ], 500);
        }
    }
}
