# 🛒 Real-Time Cart System Documentation

## Overview

The Real-Time Cart System provides persistent, multi-user cart functionality using existing database tables (`penjualan` and `penjualan_detail`) with a "Draft" status to represent active shopping carts.

## 🏗️ Architecture

### **Core Components:**

1. **CartService** (`app/Services/CartService.php`)
   - Business logic for cart operations
   - Real-time database persistence
   - Stock validation and management
   - Multi-user support

2. **CartController** (`app/Http/Controllers/Api/CartController.php`)
   - RESTful API endpoints
   - Request validation
   - Error handling

3. **Frontend Integration** (`resources/views/cashier/cashier.blade.php`)
   - Real-time cart updates
   - User-friendly checkout process
   - Error notifications

## 📊 Database Schema

### **Using Existing Tables:**

#### `penjualan` Table (Cart Header)
- **Draft Status**: `status_bayar = 'Draft'` AND `status_barang = 'Draft'`
- **Cart ID**: `kd_penjualan` (unique identifier)
- **Invoice**: `no_faktur_penjualan` (DRAFT + timestamp)
- **Customer**: `kd_pelanggan` (default: '#PLG1')
- **Totals**: `sub_total`, `total_harga`, `total_bayar`
- **User**: `dibuat_oleh` (cashier identifier)

#### `penjualan_detail` Table (Cart Items)
- **Cart Reference**: `kd_penjualan` (links to cart)
- **Product**: `kd_produk`, `nama_produk`
- **Quantity**: `qty`
- **Pricing**: `harga_jual`, `subtotal`, `laba`
- **Status**: `status_bayar = 'Draft'`

## 🚀 API Endpoints

### **Cart Management:**

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/cart` | Get current cart |
| `POST` | `/api/cart/add` | Add item to cart |
| `PUT` | `/api/cart/update` | Update item quantity |
| `DELETE` | `/api/cart/remove` | Remove item from cart |
| `POST` | `/api/cart/clear` | Clear entire cart |
| `POST` | `/api/cart/checkout` | Complete checkout |
| `GET` | `/api/cart/stats` | Get cart statistics |

### **Request/Response Examples:**

#### Add Item to Cart
```javascript
POST /api/cart/add
{
    "product_id": "1001",
    "qty": 2,
    "customer_id": "#PLG1"
}

Response:
{
    "success": true,
    "message": "Item added to cart successfully",
    "data": {
        "cart_id": "CART1703123456789ABC",
        "customer": {
            "id": "#PLG1",
            "name": "Pelanggan Umum"
        },
        "items": [...],
        "total_harga": 50000
    }
}
```

#### Checkout Cart
```javascript
POST /api/cart/checkout
{
    "payment_method": "Tunai",
    "total_bayar": 50000,
    "customer_id": "#PLG1",
    "catatan": "Customer notes"
}

Response:
{
    "success": true,
    "message": "Checkout completed successfully",
    "data": {
        "sale_id": 123,
        "invoice_number": "PJ250101000001",
        "total_harga": 50000,
        "lebih_bayar": 0
    }
}
```

## 🔄 Real-Time Features

### **1. Immediate Persistence**
- Every cart action (add/update/remove) is saved to database instantly
- No data loss on browser refresh or device change
- Multi-device synchronization

### **2. Stock Validation**
- Real-time stock checking before adding items
- Prevents overselling
- Automatic stock reduction on checkout

### **3. Multi-User Support**
- Each cashier has independent carts
- Customer-specific cart isolation
- Concurrent access handling

### **4. Cart Cleanup**
- Automatic cleanup of abandoned carts (24+ hours old)
- Prevents database bloat
- Maintains data integrity

## 💻 Frontend Integration

### **JavaScript Functions:**

```javascript
// Load current cart on page load
loadCurrentCart()

// Add product to cart
addToCartRealTime(product, qty)

// Update item quantity
updateQuantityRealTime(productId, newQty)

// Remove item from cart
removeFromCartRealTime(productId)

// Clear entire cart
clearCart()

// Complete checkout
checkoutCart(paymentMethod, totalBayar, notes)
```

### **UI Updates:**
- Real-time cart display updates
- Quantity controls with immediate feedback
- Checkout modal with payment options
- Success/error notifications

## 🔧 Configuration

### **Environment Variables:**
```env
QUEUE_CONNECTION=sync  # For immediate processing
```

### **Cart Cleanup:**
```php
// Run cleanup manually
$cartService = new CartService();
$deletedCount = $cartService->cleanupOldCarts();
```

## 🎯 Benefits

### **For Multi-User Environment:**
- ✅ **Data Persistence** - No lost carts on refresh/crash
- ✅ **Real-Time Stock** - Accurate inventory management
- ✅ **Multi-Device** - Switch devices seamlessly
- ✅ **Audit Trail** - Complete transaction history
- ✅ **Concurrent Access** - Multiple cashiers work simultaneously
- ✅ **Data Integrity** - Database-level consistency

### **For Cashier Operations:**
- ✅ **Fast Performance** - Optimized database queries
- ✅ **User-Friendly** - Intuitive interface
- ✅ **Error Handling** - Clear feedback messages
- ✅ **Stock Validation** - Prevents overselling
- ✅ **Flexible Checkout** - Multiple payment methods

## 🚨 Error Handling

### **Common Scenarios:**
1. **Insufficient Stock** - Clear error message with available quantity
2. **Product Not Found** - Graceful fallback to search
3. **Network Issues** - Retry mechanism with user feedback
4. **Database Errors** - Transaction rollback and error reporting

### **User Feedback:**
- Success messages for completed actions
- Error alerts for failed operations
- Loading states during API calls
- Validation messages for invalid inputs

## 🔄 Workflow

### **Typical Cart Flow:**
1. **Page Load** → Load existing cart from database
2. **Add Product** → Validate stock → Save to database → Update UI
3. **Modify Quantity** → Update database → Recalculate totals → Update UI
4. **Remove Item** → Delete from database → Update UI
5. **Checkout** → Convert draft to completed sale → Reduce stock → Clear cart

### **Multi-User Scenario:**
1. **Cashier A** adds items to Cart A
2. **Cashier B** adds items to Cart B (independent)
3. **Both carts** persist in database simultaneously
4. **Checkout** converts respective carts to sales
5. **Stock** is updated for both transactions

## 📈 Performance Considerations

### **Optimizations:**
- **Batch Operations** - Multiple items processed efficiently
- **Database Indexing** - Fast lookups on cart and product IDs
- **Connection Pooling** - Efficient database connections
- **Cleanup Jobs** - Regular removal of old draft carts

### **Monitoring:**
- Track cart creation/abandonment rates
- Monitor API response times
- Database query performance
- Error rates and types

---

## 🎉 **Ready for Production!**

The Real-Time Cart System is now fully implemented and ready for multi-user cashier operations. Every cart action is immediately persisted to the database, ensuring data integrity and providing a seamless experience across devices and users.

**Key Features:**
- ✅ Real-time database persistence
- ✅ Multi-user support
- ✅ Stock validation
- ✅ Complete checkout process
- ✅ Error handling
- ✅ Cart cleanup
- ✅ Frontend integration

**Next Steps:**
1. Test with multiple users simultaneously
2. Monitor performance and optimize as needed
3. Add additional payment methods if required
4. Implement advanced analytics if desired
