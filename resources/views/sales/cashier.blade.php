@extends('layouts.app')

@section('title', 'Cashier System')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-cash-register text-primary me-2"></i>
                    Cashier System
                </h2>
                <div class="text-muted mt-1">Scan products and process transactions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <span class="d-none d-sm-inline">
                        <div class="text-muted text-end">
                            <div class="text-h3 text-primary" id="currentTime"></div>
                            <div class="text-muted" id="currentDate"></div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Product Scanner Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header bg-gradient-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="ti ti-scan me-2"></i>Product Scanner
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-label h4 mb-3">Barcode / Product Code</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">
                                            <i class="ti ti-barcode"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg" id="barcode" 
                                               placeholder="Scan barcode or enter product code manually" autofocus>
                                        <button class="btn btn-primary btn-lg px-4" type="button" id="scanBtn">
                                            <i class="ti ti-search me-2"></i>Scan
                                        </button>
                                    </div>
                                    <div class="form-hint">Press Enter after scanning or typing</div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="alert alert-info border-0 mb-0">
                                    <i class="ti ti-info-circle fa-2x mb-2"></i>
                                    <p class="mb-0">Use barcode scanner or type manually</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Information Display -->
                        <div id="productInfo" class="mt-4" style="display: none;">
                            <div class="card bg-success-lt border-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="ti ti-check-circle text-success fa-2x me-3"></i>
                                                <h4 class="mb-0 text-success">Product Found!</h4>
                                            </div>
                                            <div id="productDetails"></div>
                                        </div>
                                        <div class="col-lg-4 text-center">
                                            <div class="form-group mb-3">
                                                <label class="form-label h6">Quantity</label>
                                                <input type="number" class="form-control form-control-lg text-center" 
                                                       id="quantity" value="1" min="1" style="width: 120px;">
                                            </div>
                                            <button class="btn btn-success btn-lg btn-block w-100" id="addToCart">
                                                <i class="ti ti-plus me-2"></i>Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shopping Cart Section -->
        <div class="row">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header bg-gradient-success text-white">
                        <h3 class="card-title mb-0">
                            <i class="ti ti-shopping-cart me-2"></i>Shopping Cart
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Empty Cart State -->
                        <div id="cartItems">
                            <div class="text-center py-5">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="ti ti-shopping-cart fa-4x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No items in cart</p>
                                    <p class="empty-subtitle text-muted">
                                        Scan products to add them to your cart
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cart Summary -->
                        <div id="cartSummary" style="display: none;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h4 class="card-title text-center mb-4">
                                                <i class="ti ti-receipt me-2"></i>Transaction Summary
                                            </h4>
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="text-muted mb-1">Subtotal</div>
                                                    <div class="text-h4 text-primary" id="subtotal">Rp 0</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-muted mb-1">Tax (11%)</div>
                                                    <div class="text-h4 text-warning" id="tax">Rp 0</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-muted mb-1">Total</div>
                                                    <div class="text-h4 text-success" id="total">Rp 0</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex flex-column h-100 justify-content-center">
                                        <button class="btn btn-danger btn-lg btn-block mb-3" id="clearCart">
                                            <i class="ti ti-trash me-2"></i>Clear Cart
                                        </button>
                                        <button class="btn btn-success btn-lg btn-block" id="checkoutBtn">
                                            <i class="ti ti-credit-card me-2"></i>Checkout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal modal-blur fade" id="checkoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title">
                    <i class="ti ti-credit-card me-2"></i>Complete Transaction
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Payment Method -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="ti ti-credit-card me-2"></i>Payment Method
                                </h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select form-select-lg" id="paymentMethod">
                                    <option value="Tunai">💵 Cash</option>
                                    <option value="TRF Rek BCA">🏦 BCA Transfer</option>
                                    <option value="Debit via EDC BCA">💳 Debit Card</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Customer Information -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="ti ti-user me-2"></i>Customer Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control form-control-lg" id="customerName" 
                                           placeholder="Enter customer name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Customer Phone</label>
                                    <input type="text" class="form-control form-control-lg" id="customerPhone" 
                                           placeholder="Enter customer phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Payment Details -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="ti ti-calculator me-2"></i>Payment Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="modalCartSummary" class="mb-4"></div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">Amount Received</label>
                                    <input type="number" class="form-control form-control-lg" id="amountReceived" 
                                           placeholder="Enter amount received">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Change</label>
                                    <input type="text" class="form-control form-control-lg bg-light" id="changeAmount" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="ti ti-x me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="completeTransaction">
                    <i class="ti ti-check me-2"></i>Complete Sale
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let cart = [];
let products = {};

// Initialize cashier system
document.addEventListener('DOMContentLoaded', function() {
    // Focus on barcode input
    document.getElementById('barcode').focus();
    
    // Update current time and date
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Handle barcode input
    document.getElementById('barcode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            scanProduct();
        }
    });
    
    // Event listeners
    document.getElementById('scanBtn').addEventListener('click', scanProduct);
    document.getElementById('addToCart').addEventListener('click', addToCart);
    document.getElementById('clearCart').addEventListener('click', clearCart);
    document.getElementById('checkoutBtn').addEventListener('click', showCheckoutModal);
    document.getElementById('completeTransaction').addEventListener('click', completeTransaction);
    document.getElementById('amountReceived').addEventListener('input', calculateChange);
});

function updateDateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    const dateString = now.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    document.getElementById('currentTime').textContent = timeString;
    document.getElementById('currentDate').textContent = dateString;
}

function scanProduct() {
    const barcode = document.getElementById('barcode').value.trim();
    if (!barcode) return;
    
    lookupProduct(barcode).then(product => {
        if (product) {
            // Immediately add to cart instead of showing product info
            addToCartDirectly(product);
        } else {
            showError('Product not found');
            document.getElementById('barcode').value = '';
            document.getElementById('barcode').focus();
        }
    });
}

async function lookupProduct(barcode) {
    try {
        const response = await fetch('/cashier/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ barcode: barcode })
        });
        
        const data = await response.json();
        
        if (data.success) {
            return data.product;
        } else {
            showError(data.message);
            return null;
        }
    } catch (error) {
        showError('Network error: ' + error.message);
        return null;
    }
}

function showProductInfo(product) {
    products[product.id] = product;
    
    const productDetails = document.getElementById('productDetails');
    productDetails.innerHTML = `
        <div class="row">
            <div class="col-12">
                <h3 class="text-primary mb-2">${product.name}</h3>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Barcode:</strong> ${product.barcode || 'N/A'}</p>
                        <p class="mb-1"><strong>Type:</strong> ${product.type}</p>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-success mb-1">Rp ${product.price.toLocaleString()}</h2>
                        <p class="mb-0">
                            <strong>Stock:</strong> 
                            <span class="badge bg-${product.stock > 10 ? 'success' : product.stock > 0 ? 'warning' : 'danger'}">
                                ${product.stock}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('productInfo').style.display = 'block';
    document.getElementById('quantity').value = 1;
    
    // Focus back to barcode input for quick next scan
    document.getElementById('barcode').focus();
}

function addToCart() {
    const productId = Object.keys(products)[0];
    const product = products[productId];
    const quantity = parseInt(document.getElementById('quantity').value);
    
    if (!product || quantity < 1) return;
    
    // Check if product already in cart
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += quantity;
        existingItem.subtotal = existingItem.quantity * existingItem.price;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: quantity,
            subtotal: product.price * quantity
        });
    }
    
    updateCart();
    resetProductInfo();
    document.getElementById('barcode').value = '';
    document.getElementById('barcode').focus();
}

function addToCartDirectly(product) {
    // Store product in products object for cart reference
    products[product.id] = product;
    
    // Check if product already in cart
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += 1; // Default quantity is 1
        existingItem.subtotal = existingItem.quantity * existingItem.price;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1, // Default quantity is 1
            subtotal: product.price * 1
        });
    }
    
    updateCart();
    document.getElementById('barcode').value = '';
    document.getElementById('barcode').focus();
}

function resetProductInfo() {
    document.getElementById('productInfo').style.display = 'none';
    document.getElementById('productDetails').innerHTML = '';
    products = {};
}

function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const cartSummary = document.getElementById('cartSummary');
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="text-center py-5">
                <div class="empty">
                    <div class="empty-img">
                        <i class="ti ti-shopping-cart fa-4x text-muted"></i>
                    </div>
                    <p class="empty-title">No items in cart</p>
                    <p class="empty-subtitle text-muted">
                        Scan products to add them to your cart
                    </p>
                </div>
            </div>
        `;
        cartSummary.style.display = 'none';
        return;
    }
    
    let cartHtml = `
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th class="h5">Product</th>
                        <th class="h5 text-center">Price</th>
                        <th class="h5 text-center">Qty</th>
                        <th class="h5 text-center">Subtotal</th>
                        <th class="h5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    cart.forEach((item, index) => {
        cartHtml += `
            <tr>
                <td class="h6">${item.name}</td>
                <td class="h6 text-center">Rp ${item.price.toLocaleString()}</td>
                <td class="h6 text-center">${item.quantity}</td>
                <td class="h6 text-success text-center">Rp ${item.subtotal.toLocaleString()}</td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    cartHtml += '</tbody></table></div>';
    cartItems.innerHTML = cartHtml;
    
    updateCartSummary();
    cartSummary.style.display = 'block';
}

function removeFromCart(index) {
    if (confirm('Remove this item from cart?')) {
        cart.splice(index, 1);
        updateCart();
    }
}

function updateCartSummary() {
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const tax = subtotal * 0.11; // 11% tax
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById('tax').textContent = `Rp ${tax.toLocaleString()}`;
    document.getElementById('total').textContent = `Rp ${total.toLocaleString()}`;
}

function clearCart() {
    if (confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        updateCart();
        resetProductInfo();
    }
}

function showCheckoutModal() {
    if (cart.length === 0) {
        showError('Cart is empty');
        return;
    }
    
    document.getElementById('modalCartSummary').innerHTML = document.getElementById('cartSummary').innerHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    modal.show();
}

function calculateChange() {
    const total = parseFloat(document.getElementById('total').textContent.replace('Rp ', '').replace(',', ''));
    const received = parseFloat(document.getElementById('amountReceived').value) || 0;
    const change = received - total;
    
    document.getElementById('changeAmount').value = change >= 0 ? `Rp ${change.toLocaleString()}` : 'Insufficient amount';
}

async function completeTransaction() {
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
    const total = parseFloat(document.getElementById('total').textContent.replace('Rp ', '').replace(',', ''));
    
    if (amountReceived < total) {
        showError('Amount received is insufficient');
        return;
    }
    
    const transactionData = {
        items: cart,
        customer: {
            name: document.getElementById('customerName').value,
            phone: document.getElementById('customerPhone').value
        },
        payment: {
            method: document.getElementById('paymentMethod').value,
            amountReceived: amountReceived,
            change: amountReceived - total
        },
        totals: {
            subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('Rp ', '').replace(',', '')),
            tax: parseFloat(document.getElementById('tax').textContent.replace('Rp ', '').replace(',', '')),
            total: total
        }
    };
    
    try {
        const response = await fetch('/cashier/transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(transactionData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess(`Transaction completed successfully! Invoice: ${data.invoice_number}`);
            
            // Close modal and reset
            const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
            modal.hide();
            clearCart();
            
            // Reset form
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('amountReceived').value = '';
            document.getElementById('changeAmount').value = '';
        } else {
            showError('Transaction failed: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

function showError(message) {
    // Create a better notification using Tabler's toast
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-danger border-0';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="ti ti-alert-circle me-2"></i>
                <strong>Error:</strong> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    container.appendChild(toast);
    document.body.appendChild(container);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (container.parentNode) {
            container.parentNode.removeChild(container);
        }
    }, 5000);
}

function showSuccess(message) {
    // Create a success notification
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="ti ti-check-circle me-2"></i>
                <strong>Success:</strong> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    container.appendChild(toast);
    document.body.appendChild(container);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (container.parentNode) {
            container.parentNode.removeChild(container);
        }
    }, 5000);
}
</script>

<style>
/* Custom styles for Tabler UI */
.card-cashier {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card-cashier:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
}

/* Tablet-friendly styles */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .form-control-lg {
        height: 3.5rem;
        font-size: 1.1rem;
    }
    
    .modal-xl {
        max-width: 95%;
        margin: 1rem auto;
    }
}

/* Touch-friendly button sizes */
.btn {
    min-height: 44px;
}

.btn-lg {
    min-height: 54px;
}

/* Better spacing for tablets */
.card {
    margin-bottom: 1.5rem;
}

/* Improved form styling */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Better alert styling */
.alert {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Custom empty state styling */
.empty {
    padding: 3rem 0;
}

.empty-img {
    height: 8rem;
    margin-bottom: 2rem;
    opacity: 0.4;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-subtitle {
    font-size: 1rem;
    margin-bottom: 0;
}
</style>
@endsection
