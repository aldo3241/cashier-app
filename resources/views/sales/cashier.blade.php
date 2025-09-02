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
        <!-- Cashier Session Control -->
        <div class="row mb-4" id="sessionControl">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header bg-gradient-info text-white">
                        <h3 class="card-title mb-0">
                            <i class="ti ti-cash-register me-2"></i>Cashier Session
                        </h3>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div id="noSessionState">
                            <div class="empty">
                                <div class="empty-img">
                                    <i class="ti ti-cash-register fa-4x text-muted"></i>
                                </div>
                                <p class="empty-title">No Active Session</p>
                                <p class="empty-subtitle text-muted">
                                    Start a new cashier session to begin scanning products
                                </p>
                                <button class="btn btn-primary btn-lg" id="startSessionBtn">
                                    <i class="ti ti-play me-2"></i>Start Cashier Session
                                </button>
                            </div>
                        </div>
                        <div id="activeSessionState" style="display: none;">
                            <div class="row align-items-center">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="ti ti-check-circle text-success fa-2x"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-1 text-success fw-bold">Session Active</h4>
                                            <p class="mb-0 text-muted">
                                                <i class="ti ti-receipt me-1"></i>
                                                Invoice: <span id="currentInvoice" class="fw-semibold text-dark"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end justify-content-start">
                                        <button class="btn btn-info btn-sm" id="showPendingSalesBtn">
                                            <i class="ti ti-clock me-1"></i>Other Sales
                                        </button>
                                        <button class="btn btn-warning btn-sm" id="resumeSessionBtn">
                                            <i class="ti ti-refresh me-1"></i>Resume
                                        </button>
                                        <button class="btn btn-danger btn-sm" id="endSessionBtn">
                                            <i class="ti ti-x me-1"></i>End
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Sales Management -->
        <div class="row mb-4" id="pendingSalesSection" style="display: none;">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header bg-gradient-info text-white">
                        <h3 class="card-title mb-0">
                            <i class="ti ti-clock me-2"></i>Pending Sales Management
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button class="btn btn-outline-primary" id="loadPendingSalesBtn">
                                    <i class="ti ti-refresh me-2"></i>Load All Pending Sales
                                </button>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-outline-secondary" id="hidePendingSalesBtn">
                                    <i class="ti ti-x me-2"></i>Hide Pending Sales
                                </button>
                            </div>
                        </div>
                        
                        <div id="pendingSalesList">
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="ti ti-clock fa-3x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No pending sales loaded</p>
                                    <p class="empty-subtitle text-muted">
                                        Click "Load All Pending Sales" to view and edit other pending sales
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Scanner Section -->
        <div class="row mb-4" id="scannerSection" style="display: none;">
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

<!-- Edit Item Modal -->
<div class="modal modal-blur fade" id="editItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning text-white">
                <h4 class="modal-title">
                    <i class="ti ti-edit me-2"></i>Edit Sale Item
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="ti ti-package me-2"></i>Product Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control form-control-lg" id="editProductName" readonly>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Price (Rp)</label>
                                            <input type="number" class="form-control form-control-lg" id="editPrice" 
                                                   min="0" step="100">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control form-control-lg" id="editQuantity" 
                                                   min="1" step="1">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">Discount (Rp)</label>
                                    <input type="number" class="form-control form-control-lg" id="editDiscount" 
                                           min="0" step="100" value="0">
                                </div>
                                
                                <div class="alert alert-info border-0">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="text-muted mb-1">Subtotal</div>
                                            <div class="text-h5 text-primary" id="editSubtotal">Rp 0</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted mb-1">Discount</div>
                                            <div class="text-h5 text-warning" id="editDiscountDisplay">Rp 0</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted mb-1">Final Total</div>
                                            <div class="text-h5 text-success" id="editFinalTotal">Rp 0</div>
                                        </div>
                                    </div>
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
                <button type="button" class="btn btn-warning btn-lg" id="saveEditItem">
                    <i class="ti ti-check me-2"></i>Save Changes
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
let currentSaleId = null;
let currentInvoice = null;
let editingItemId = null;

// Initialize cashier system
document.addEventListener('DOMContentLoaded', function() {
    // Update current time and date
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Check for existing pending sales on page load
    checkForPendingSales();
    
    // Event listeners for session management
    document.getElementById('startSessionBtn').addEventListener('click', startNewSession);
    document.getElementById('resumeSessionBtn').addEventListener('click', resumeSession);
    document.getElementById('endSessionBtn').addEventListener('click', endSession);
    document.getElementById('showPendingSalesBtn').addEventListener('click', showPendingSalesSection);
    document.getElementById('loadPendingSalesBtn').addEventListener('click', loadAllPendingSales);
    document.getElementById('hidePendingSalesBtn').addEventListener('click', hidePendingSalesSection);
    
    // Event listeners for scanning (only when session is active)
    document.getElementById('barcode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && currentSaleId) {
            e.preventDefault();
            scanProduct();
        }
    });
    
    document.getElementById('scanBtn').addEventListener('click', scanProduct);
    document.getElementById('addToCart').addEventListener('click', addToCart);
    document.getElementById('clearCart').addEventListener('click', clearCart);
    document.getElementById('checkoutBtn').addEventListener('click', showCheckoutModal);
    document.getElementById('completeTransaction').addEventListener('click', completeTransaction);
    document.getElementById('amountReceived').addEventListener('input', calculateChange);
    document.getElementById('saveEditItem').addEventListener('click', saveEditItem);
    
    // Edit modal event listeners
    document.getElementById('editPrice').addEventListener('input', calculateEditTotals);
    document.getElementById('editQuantity').addEventListener('input', calculateEditTotals);
    document.getElementById('editDiscount').addEventListener('input', calculateEditTotals);
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

// Session Management Functions
async function checkForPendingSales() {
    try {
        const response = await fetch('/cashier/sales-with-details?status=Pending&per_page=1', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.sales.length > 0) {
            const pendingSale = data.sales[0];
            currentSaleId = pendingSale.kd_penjualan;
            currentInvoice = pendingSale.no_faktur_penjualan;
            
            // Show active session state
            showActiveSession();
            
            // Load existing items
            await loadExistingSaleItems();
        } else {
            showNoSession();
        }
    } catch (error) {
        console.error('Error checking for pending sales:', error);
        showNoSession();
    }
}

async function startNewSession() {
    const btn = document.getElementById('startSessionBtn');
    const originalText = btn.innerHTML;
    
    try {
        // Show loading state
        btn.classList.add('loading');
        btn.innerHTML = '<i class="ti ti-loader-2 me-2"></i>Starting...';
        btn.disabled = true;
        
        const response = await fetch('/cashier/pending-sale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentSaleId = data.sale_id;
            currentInvoice = data.invoice_number;
            
            showActiveSession();
            showSuccess('New cashier session started!');
        } else {
            showError('Failed to start session: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    } finally {
        // Reset button state
        btn.classList.remove('loading');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function resumeSession() {
    if (!currentSaleId) return;
    
    try {
        await loadExistingSaleItems();
        showSuccess('Session resumed successfully!');
    } catch (error) {
        showError('Failed to resume session: ' + error.message);
    }
}

async function endSession() {
    if (confirm('Are you sure you want to end this session? All unsaved items will be lost.')) {
        currentSaleId = null;
        currentInvoice = null;
        cart = [];
        products = {};
        
        showNoSession();
        updateCart();
        showSuccess('Session ended successfully!');
    }
}

function showActiveSession() {
    document.getElementById('noSessionState').style.display = 'none';
    document.getElementById('activeSessionState').style.display = 'block';
    document.getElementById('scannerSection').style.display = 'block';
    document.getElementById('currentInvoice').textContent = currentInvoice;
    
    // Focus on barcode input
    document.getElementById('barcode').focus();
}

function showNoSession() {
    document.getElementById('noSessionState').style.display = 'block';
    document.getElementById('activeSessionState').style.display = 'none';
    document.getElementById('scannerSection').style.display = 'none';
    
    // Clear cart
    cart = [];
    products = {};
    updateCart();
}

// Pending Sales Management Functions
function showPendingSalesSection() {
    document.getElementById('pendingSalesSection').style.display = 'block';
    loadAllPendingSales();
}

function hidePendingSalesSection() {
    document.getElementById('pendingSalesSection').style.display = 'none';
}

async function loadAllPendingSales() {
    try {
        const response = await fetch('/cashier/sales-with-details?status=Pending&per_page=50', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayPendingSales(data.sales);
        } else {
            showError('Failed to load pending sales: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

function displayPendingSales(sales) {
    const pendingSalesList = document.getElementById('pendingSalesList');
    
    if (sales.length === 0) {
        pendingSalesList.innerHTML = `
            <div class="text-center py-4">
                <div class="empty">
                    <div class="empty-img">
                        <i class="ti ti-check-circle fa-3x text-success"></i>
                    </div>
                    <p class="empty-title">No pending sales found</p>
                    <p class="empty-subtitle text-muted">
                        All sales have been completed
                    </p>
                </div>
            </div>
        `;
        return;
    }
    
    let salesHtml = `
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th class="h5">Invoice</th>
                        <th class="h5 text-center">Date</th>
                        <th class="h5 text-center">Items</th>
                        <th class="h5 text-center">Total</th>
                        <th class="h5 text-center">Status</th>
                        <th class="h5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    sales.forEach(sale => {
        const isCurrentSale = sale.kd_penjualan == currentSaleId;
        const statusClass = isCurrentSale ? 'success' : 'warning';
        const statusText = isCurrentSale ? 'Current Session' : 'Pending';
        
        salesHtml += `
            <tr class="${isCurrentSale ? 'table-success' : ''}">
                <td class="h6">
                    <strong>${sale.no_faktur_penjualan}</strong>
                    ${isCurrentSale ? '<br><small class="text-success">Current Session</small>' : ''}
                </td>
                <td class="h6 text-center">
                    ${new Date(sale.date_created).toLocaleDateString('id-ID')}
                    <br><small class="text-muted">${new Date(sale.date_created).toLocaleTimeString('id-ID')}</small>
                </td>
                <td class="h6 text-center">
                    <span class="badge bg-primary">${sale.total_items || 0} items</span>
                </td>
                <td class="h6 text-center">
                    <strong>Rp ${parseFloat(sale.total_harga || 0).toLocaleString()}</strong>
                </td>
                <td class="h6 text-center">
                    <span class="badge bg-${statusClass}">${statusText}</span>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm" onclick="switchToSale(${sale.kd_penjualan}, '${sale.no_faktur_penjualan}')" title="Switch to this sale">
                            <i class="ti ti-arrow-right"></i>
                        </button>
                        <button class="btn btn-info btn-sm" onclick="viewSaleDetails(${sale.kd_penjualan})" title="View details">
                            <i class="ti ti-eye"></i>
                        </button>
                        <button class="btn btn-success btn-sm" onclick="completePendingSale(${sale.kd_penjualan})" title="Complete sale">
                            <i class="ti ti-check"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    salesHtml += '</tbody></table></div>';
    pendingSalesList.innerHTML = salesHtml;
}

async function switchToSale(saleId, invoiceNumber) {
    if (confirm(`Switch to sale ${invoiceNumber}? This will load its items into the current session.`)) {
        currentSaleId = saleId;
        currentInvoice = invoiceNumber;
        
        // Update the session display
        document.getElementById('currentInvoice').textContent = invoiceNumber;
        
        // Load the sale items
        await loadExistingSaleItems();
        
        // Hide pending sales section
        hidePendingSalesSection();
        
        showSuccess(`Switched to sale ${invoiceNumber}`);
    }
}

async function viewSaleDetails(saleId) {
    try {
        const response = await fetch(`/cashier/sale/${saleId}/details`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSaleDetailsModal(data.sale, data.details);
        } else {
            showError('Failed to load sale details: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

function showSaleDetailsModal(sale, details) {
    let detailsHtml = `
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sale Details - ${sale.no_faktur_penjualan}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Invoice:</strong> ${sale.no_faktur_penjualan}<br>
                        <strong>Date:</strong> ${new Date(sale.date_created).toLocaleString('id-ID')}<br>
                        <strong>Status:</strong> <span class="badge bg-warning">${sale.status_bayar}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Subtotal:</strong> Rp ${parseFloat(sale.sub_total || 0).toLocaleString()}<br>
                        <strong>Tax:</strong> Rp ${parseFloat(sale.pajak || 0).toLocaleString()}<br>
                        <strong>Total:</strong> Rp ${parseFloat(sale.total_harga || 0).toLocaleString()}
                    </div>
                </div>
    `;
    
    if (details.length > 0) {
        detailsHtml += `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        details.forEach(detail => {
            detailsHtml += `
                <tr>
                    <td>${detail.nama_produk}</td>
                    <td class="text-center">Rp ${parseFloat(detail.harga_jual).toLocaleString()}</td>
                    <td class="text-center">${detail.qty}</td>
                    <td class="text-center">Rp ${parseFloat(detail.sub_total).toLocaleString()}</td>
                </tr>
            `;
        });
        
        detailsHtml += '</tbody></table></div>';
    } else {
        detailsHtml += '<div class="alert alert-info">No items in this sale yet.</div>';
    }
    
    detailsHtml += '</div></div>';
    
    // Create and show modal
    const modalHtml = `
        <div class="modal fade" id="saleDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sale Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${detailsHtml}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('saleDetailsModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add new modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
    modal.show();
}

async function completePendingSale(saleId) {
    if (confirm('Complete this pending sale? This will mark it as finished and cannot be undone.')) {
        try {
            // For now, just show a message - you can implement actual completion logic
            showSuccess('Sale completion feature will be implemented');
        } catch (error) {
            showError('Failed to complete sale: ' + error.message);
        }
    }
}

async function loadExistingSaleItems() {
    if (!currentSaleId) return;
    
    try {
        const response = await fetch(`/cashier/sale/${currentSaleId}/details`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Convert sale details to cart format
            cart = data.details.map(detail => ({
                id: detail.kd_produk,
                name: detail.nama_produk,
                price: parseFloat(detail.harga_jual),
                quantity: parseInt(detail.qty),
                subtotal: parseFloat(detail.sub_total),
                detailId: detail.kd_penjualan_detail
            }));
            
            updateCart();
        }
    } catch (error) {
        console.error('Error loading existing items:', error);
    }
}

async function scanProduct() {
    if (!currentSaleId) {
        showError('Please start a cashier session first');
        return;
    }
    
    const barcode = document.getElementById('barcode').value.trim();
    if (!barcode) return;
    
    const scanBtn = document.getElementById('scanBtn');
    const originalText = scanBtn.innerHTML;
    
    try {
        // Show loading state
        scanBtn.classList.add('loading');
        scanBtn.innerHTML = '<i class="ti ti-loader-2 me-2"></i>Scanning...';
        scanBtn.disabled = true;
        
        const product = await lookupProduct(barcode);
        
        if (product) {
            // Add directly to sale instead of local cart
            await addToSaleDirectly(product);
        } else {
            showError('Product not found');
            document.getElementById('barcode').value = '';
            document.getElementById('barcode').focus();
        }
    } catch (error) {
        showError('Scan failed: ' + error.message);
    } finally {
        // Reset button state
        scanBtn.classList.remove('loading');
        scanBtn.innerHTML = originalText;
        scanBtn.disabled = false;
    }
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

async function addToSaleDirectly(product) {
    if (!currentSaleId) {
        showError('No active session');
        return;
    }
    
    try {
        const response = await fetch('/cashier/add-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                sale_id: currentSaleId,
                product_id: product.id,
                quantity: 1
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload the cart to show updated items
            await loadExistingSaleItems();
            showSuccess('Product added to sale!');
        } else {
            showError('Failed to add product: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
    
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
                    <div class="btn-group" role="group">
                        <button class="btn btn-warning btn-sm" onclick="editSaleItem(${item.detailId || index}, '${item.name}', ${item.price}, ${item.quantity})" title="Edit Item">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="removeFromSale(${item.detailId || index})" title="Remove Item">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    cartHtml += '</tbody></table></div>';
    cartItems.innerHTML = cartHtml;
    
    updateCartSummary();
    cartSummary.style.display = 'block';
}

async function removeFromSale(detailId) {
    if (!confirm('Remove this item from sale?')) return;
    
    try {
        const response = await fetch(`/cashier/sale-item/${detailId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload the cart to show updated items
            await loadExistingSaleItems();
            showSuccess('Item removed from sale!');
        } else {
            showError('Failed to remove item: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
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
    if (!currentSaleId) {
        showError('No active session to complete');
        return;
    }
    
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
    const total = parseFloat(document.getElementById('total').textContent.replace('Rp ', '').replace(',', ''));
    
    if (amountReceived < total) {
        showError('Amount received is insufficient');
        return;
    }
    
    const transactionData = {
        sale_id: currentSaleId,
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
        const response = await fetch('/cashier/complete-sale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(transactionData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess(`Transaction completed successfully! Invoice: ${currentInvoice}`);
            
            // Close modal and reset session
            const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
            modal.hide();
            
            // End the session
            currentSaleId = null;
            currentInvoice = null;
            cart = [];
            products = {};
            
            showNoSession();
            updateCart();
            
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

// Edit Item Functions
function editSaleItem(detailId, productName, currentPrice, currentQuantity) {
    editingItemId = detailId;
    
    // Populate the edit modal
    document.getElementById('editProductName').value = productName;
    document.getElementById('editPrice').value = currentPrice;
    document.getElementById('editQuantity').value = currentQuantity;
    document.getElementById('editDiscount').value = 0;
    
    // Calculate initial totals
    calculateEditTotals();
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('editItemModal'));
    modal.show();
}

function calculateEditTotals() {
    const price = parseFloat(document.getElementById('editPrice').value) || 0;
    const quantity = parseInt(document.getElementById('editQuantity').value) || 0;
    const discount = parseFloat(document.getElementById('editDiscount').value) || 0;
    
    const subtotal = price * quantity;
    const finalTotal = subtotal - discount;
    
    document.getElementById('editSubtotal').textContent = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById('editDiscountDisplay').textContent = `Rp ${discount.toLocaleString()}`;
    document.getElementById('editFinalTotal').textContent = `Rp ${finalTotal.toLocaleString()}`;
}

async function saveEditItem() {
    if (!editingItemId) {
        showError('No item selected for editing');
        return;
    }
    
    const price = parseFloat(document.getElementById('editPrice').value);
    const quantity = parseInt(document.getElementById('editQuantity').value);
    const discount = parseFloat(document.getElementById('editDiscount').value) || 0;
    
    if (!price || price <= 0) {
        showError('Please enter a valid price');
        return;
    }
    
    if (!quantity || quantity <= 0) {
        showError('Please enter a valid quantity');
        return;
    }
    
    try {
        const response = await fetch(`/cashier/sale-item/${editingItemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qty: quantity,
                harga_jual: price,
                diskon: discount
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Item updated successfully!');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
            modal.hide();
            
            // Reload the cart to show updated items
            await loadExistingSaleItems();
            
            // Reset editing state
            editingItemId = null;
        } else {
            showError('Failed to update item: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

function showError(message) {
    // Create an enhanced notification
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-danger border-0 shadow-lg';
    toast.setAttribute('role', 'alert');
    toast.style.borderRadius = '1rem';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <div class="me-3">
                    <i class="ti ti-alert-circle fa-2x"></i>
                </div>
                <div>
                    <div class="fw-bold mb-1">Error</div>
                    <div class="small">${message}</div>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    container.appendChild(toast);
    document.body.appendChild(container);
    
    // Add entrance animation
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });
    bsToast.show();
    
    // Auto-remove after animation
    setTimeout(() => {
        if (container.parentNode) {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (container.parentNode) {
                    container.parentNode.removeChild(container);
                }
            }, 300);
        }
    }, 5000);
}

function showSuccess(message) {
    // Create an enhanced success notification
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0 shadow-lg';
    toast.setAttribute('role', 'alert');
    toast.style.borderRadius = '1rem';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <div class="me-3">
                    <i class="ti ti-check-circle fa-2x"></i>
                </div>
                <div>
                    <div class="fw-bold mb-1">Success</div>
                    <div class="small">${message}</div>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    container.appendChild(toast);
    document.body.appendChild(container);
    
    // Add entrance animation
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
    
    // Auto-remove after animation
    setTimeout(() => {
        if (container.parentNode) {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (container.parentNode) {
                    container.parentNode.removeChild(container);
                }
            }, 300);
        }
    }, 4000);
}
</script>

<style>
/* Custom styles for Tabler UI */
.card-cashier {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.card-cashier:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.12);
    border-color: rgba(0, 0, 0, 0.1);
}

.card-cashier .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 1rem 1rem 0 0 !important;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 249, 250, 0.9) 100%);
}

.card-cashier .card-body {
    padding: 2rem;
    background: #ffffff;
}

/* Session control specific styling */
#sessionControl .card-cashier {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid rgba(40, 167, 69, 0.1);
}

#sessionControl .card-cashier:hover {
    border-color: rgba(40, 167, 69, 0.2);
    transform: translateY(-2px);
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

.bg-gradient-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
}

/* Enhanced responsive design */
@media (max-width: 1200px) {
    .card-cashier .card-body {
        padding: 1.5rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .card-cashier .card-body {
        padding: 1.25rem;
    }
    
    .card-cashier .card-header {
        padding: 1.25rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        min-height: 48px;
    }
    
    .form-control-lg {
        height: 3.5rem;
        font-size: 1rem;
        padding: 0.875rem 1rem;
    }
    
    .modal-xl {
        max-width: 95%;
        margin: 1rem auto;
    }
    
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .text-h4 {
        font-size: 1.5rem !important;
    }
    
    .text-h5 {
        font-size: 1.25rem !important;
    }
    
    /* Fix button layout on mobile */
    .d-flex.flex-wrap.gap-2 {
        flex-direction: column;
        align-items: stretch;
    }
    
    .d-flex.flex-wrap.gap-2 .btn {
        margin-bottom: 0.5rem;
        margin-left: 0 !important;
    }
    
    .d-flex.flex-wrap.gap-2 .btn:last-child {
        margin-bottom: 0;
    }
}

@media (max-width: 576px) {
    .card-cashier .card-body {
        padding: 1rem;
    }
    
    .card-cashier .card-header {
        padding: 1rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        min-height: 44px;
    }
    
    .form-control-lg {
        height: 3rem;
        font-size: 0.95rem;
        padding: 0.75rem 0.875rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 0.5rem !important;
        margin-bottom: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-bottom: 0;
    }
}

/* Enhanced button styles */
.btn {
    min-height: 44px;
    border-radius: 0.75rem;
    font-weight: 600;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

/* Gap utility for flexbox */
.gap-2 > * + * {
    margin-left: 0.5rem;
}

.gap-3 > * + * {
    margin-left: 1rem;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-lg {
    min-height: 54px;
    padding: 0.875rem 2rem;
    font-size: 1.1rem;
    border-radius: 1rem;
}

.btn-sm {
    min-height: 36px;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Better button spacing and alignment */
.d-flex.flex-wrap.gap-2 {
    align-items: center;
}

.d-flex.flex-wrap.gap-2 .btn {
    flex-shrink: 0;
}

/* Button variants with enhanced styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
}

/* Better spacing for tablets */
.card {
    margin-bottom: 1.5rem;
}

/* Enhanced form styling */
.form-control {
    border-radius: 0.75rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 500;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    transform: translateY(-1px);
}

.form-control-lg {
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    font-size: 1.1rem;
}

.input-group .form-control {
    border-right: none;
}

.input-group .input-group-text {
    border-radius: 0.75rem 0 0 0.75rem;
    border: 2px solid #e9ecef;
    border-right: none;
    background: #f8f9fa;
    font-weight: 600;
}

.input-group .btn {
    border-radius: 0 0.75rem 0.75rem 0;
    border-left: none;
}

/* Loading states */
.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Pulse animation for important elements */
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Better alert styling */
.alert {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Enhanced empty state styling */
.empty {
    padding: 3rem 0;
    animation: fadeInUp 0.6s ease-out;
}

.empty-img {
    height: 8rem;
    margin-bottom: 2rem;
    opacity: 0.4;
    transition: all 0.3s ease;
}

.empty:hover .empty-img {
    opacity: 0.6;
    transform: scale(1.05);
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.empty-subtitle {
    font-size: 1rem;
    margin-bottom: 0;
    color: #6c757d;
}

/* Enhanced animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Smooth transitions for all interactive elements */
* {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Remove transitions for performance on non-interactive elements */
*, *::before, *::after {
    transition-property: transform, opacity, box-shadow, border-color, background-color;
}

/* Ensure proper text rendering */
body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

/* Enhanced table styling */
.table-vcenter td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.table-vcenter th {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #e9ecef;
}

/* Badge enhancements */
.badge {
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
}

/* Enhanced modal styling */
.modal-content {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

.modal-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 1rem 1rem 0 0;
}

.modal-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0 0 1rem 1rem;
}
</style>
@endsection
