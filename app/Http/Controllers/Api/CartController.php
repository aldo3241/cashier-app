<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Penjualan;
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
            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
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
                'cart_id' => 'nullable|integer',
            ]);

            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
            $customerId = $request->get('customer_id', 1);
            $productId = $request->product_id;
            $qty = $request->qty;
            $cartId = $request->get('cart_id');

            $cartDetails = $this->cartService->addToCart($userId, $customerId, $productId, $qty, $cartId);

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
                'cart_id' => 'nullable|integer',
            ]);

            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
            $customerId = $request->get('customer_id', 1);
            $cartId = $request->get('cart_id');
            $productId = $request->product_id;
            $qty = $request->qty;

            $cartDetails = $this->cartService->updateCartItem($userId, $customerId, $productId, $qty, $cartId);

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
                'cart_id' => 'nullable|integer',
            ]);

            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
            $customerId = $request->get('customer_id', 1);
            $cartId = $request->get('cart_id');
            $productId = $request->product_id;

            $cartDetails = $this->cartService->removeFromCart($userId, $customerId, $productId, $cartId);

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
            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
            $customerId = $request->get('customer_id', 1);
            $cartId = $request->get('cart_id');

            $cartDetails = $this->cartService->clearCart($userId, $customerId, $cartId);

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
                'cart_id' => 'nullable|integer',
                'catatan' => 'nullable|string',
                'status_barang' => 'required|string|in:diterima langsung,dikirimkan ekspedisi',
            ]);

            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
            $customerId = $request->get('customer_id', 1);
            $cartId = $request->get('cart_id');
            $paymentMethod = $request->payment_method;
            $totalBayar = $request->total_bayar;
            $catatan = $request->get('catatan');
            $statusBarang = $request->get('status_barang', 'diterima langsung');

            $result = $this->cartService->checkoutCart($userId, $customerId, $paymentMethod, $totalBayar, $catatan, $statusBarang, $cartId);

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
            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
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
            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
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
     * Create a fresh new transaction (for new transactions)
     */
    public function createFreshTransaction(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $userId = $user->username ?? $user->name ?? $user->email ?? 'system';
            $customerId = $request->get('customer_id', 1);

            // Create a fresh cart (don't look for existing ones)
            $cart = $this->cartService->createFreshCart($userId, $customerId);

            return response()->json([
                'success' => true,
                'data' => $cart,
                'message' => 'Fresh transaction created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating fresh transaction: ' . $e->getMessage()
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

            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
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

            $user = Auth::user();
            $userId = $user ? ($user->username ?? $user->name ?? $user->email ?? 'system') : 'system';
            
            // Debug logging for checkout
            if ($request->isMethod('post') && $request->routeIs('api.cart.checkout')) {
                \Log::info('Checkout user debug', [
                    'user_authenticated' => Auth::check(),
                    'user' => $user ? $user->toArray() : null,
                    'user_name' => $user->name ?? 'null',
                    'user_nama' => $user->nama ?? 'null',
                    'user_username' => $user->username ?? 'null',
                    'user_email' => $user->email ?? 'null',
                    'final_user_id' => $userId
                ]);
            }
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

    /**
     * Continue an incomplete transaction
     */
    public function continueTransaction($transactionId)
    {
        try {
            $userId = Auth::user()->username ?? Auth::user()->name ?? 'system';
            
            // Find the transaction
            $transaction = Penjualan::with(['penjualanDetails', 'pelanggan'])
                ->where('kd_penjualan', $transactionId)
                ->where('dibuat_oleh', $userId)
                ->where('status_bayar', 'Belum Lunas')
                ->first();

            if (!$transaction) {
                // Return a redirect response instead of JSON for better UX
                return redirect()->route('sales.my-sales')->with('error', 'Transaction not found or already completed');
            }

            // Redirect to cashier with the transaction loaded
            return redirect()->route('cashier.index', ['continue' => $transactionId]);

        } catch (\Exception $e) {
            // Return a redirect response instead of JSON for better UX
            return redirect()->route('sales.my-sales')->with('error', 'Error continuing transaction: ' . $e->getMessage());
        }
    }
}
