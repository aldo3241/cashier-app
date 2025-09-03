@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-receipt text-primary me-2"></i>
                    Sale Details
                </h2>
                <div class="text-muted mt-1">View and manage all sales transactions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('cashier') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Cashier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-search me-2"></i>Search & Filters
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Search Invoice/Customer</label>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search by invoice or customer name">
                            </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Date Range</label>
                                    <select class="form-select" id="dateRange">
                                        <option value="all">All Time</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                        <option value="Lunas">Completed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-primary w-100" id="searchBtn">
                                        <i class="fas fa-search me-2"></i>Search
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales List -->
        <div class="row">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-receipt text-primary me-2"></i>Sales Transactions
                        </h3>
                        <div class="card-actions">
                            <button class="btn btn-primary btn-sm" id="exportBtn">
                                <i class="fas fa-download me-2"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="salesList">
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                            </div>
                                    <p class="empty-title">No sales found</p>
                                    <p class="empty-subtitle text-muted">
                                        Start making sales to see transactions here
                                    </p>
                                    <div class="empty-action">
                                        <a href="{{ route('cashier') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Go to Cashier
                                        </a>
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

<!-- Sale Details Modal -->
<div class="modal modal-blur fade" id="saleDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title">
                    <i class="fas fa-receipt text-primary me-2"></i>Sale Details
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="saleDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="printSaleBtn">
                    <i class="fas fa-print me-2"></i>Print Receipt
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
                    <i class="fas fa-edit text-primary me-2"></i>Edit Sale Item
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-box me-2"></i>Product Information
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
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning btn-lg" id="saveEditItem">
                    <i class="fas fa-check me-2"></i>Update Item
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentPage = 1;
let totalPages = 1;
let currentSaleId = null;
let editingItemId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadSales();
    
    // Event listeners
    document.getElementById('searchBtn').addEventListener('click', function() {
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentPage = 1;
            loadSales();
        }
    });
    
    document.getElementById('dateRange').addEventListener('change', function() {
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('statusFilter').addEventListener('change', function() {
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('exportBtn').addEventListener('click', exportSales);
    document.getElementById('printSaleBtn').addEventListener('click', printSaleReceipt);
    document.getElementById('saveEditItem').addEventListener('click', saveEditItem);
    
    // Edit modal event listeners
    document.getElementById('editPrice').addEventListener('input', calculateEditTotals);
    document.getElementById('editQuantity').addEventListener('input', calculateEditTotals);
    document.getElementById('editDiscount').addEventListener('input', calculateEditTotals);
});

async function loadSales() {
    const searchTerm = document.getElementById('searchInput').value;
    const dateRange = document.getElementById('dateRange').value;
    const status = document.getElementById('statusFilter').value;
    
    try {
        const response = await fetch(`/api/sales?page=${currentPage}&search=${searchTerm}&date_range=${dateRange}&status=${status}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
                displaySales(data.sales);
            updatePagination(data.current_page, data.last_page);
            } else {
            showError('Failed to load sales');
            }
    } catch (error) {
        console.error('Error loading sales:', error);
            showError('Network error: ' + error.message);
    }
}

function displaySales(sales) {
    const container = document.getElementById('salesList');
    
    if (sales.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="empty">
                    <div class="empty-img">
                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                    </div>
                    <p class="empty-title">No sales found</p>
                    <p class="empty-subtitle text-muted">
                        No sales match your search criteria
                    </p>
                </div>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    sales.forEach(sale => {
        const statusClass = sale.status_bayar === 'Lunas' ? 'success' : 'warning';
        const statusText = sale.status_bayar === 'Lunas' ? 'Completed' : 'Pending';
        
        html += `
            <tr>
                <td class="h6">${sale.no_faktur_penjualan}</td>
                <td>${sale.kd_pelanggan ? 'Customer #' + sale.kd_pelanggan : 'Walk-in Customer'}</td>
                <td>${new Date(sale.date_created).toLocaleDateString('id-ID')}</td>
                <td class="text-center">
                    <span class="badge bg-primary">${sale.total_items || 0} items</span>
                </td>
                <td class="text-success">Rp ${parseFloat(sale.total_harga || 0).toLocaleString()}</td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-info btn-sm" onclick="viewSaleDetails(${sale.kd_penjualan})" title="View details">
                            <i class="fas fa-eye me-1"></i>View
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="editSale(${sale.kd_penjualan})" title="Edit sale">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteSale(${sale.kd_penjualan})" title="Delete sale">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function updatePagination(currentPage, lastPage) {
    totalPages = lastPage;
    
    if (lastPage <= 1) return;
    
    const paginationHtml = `
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Page ${currentPage} of ${lastPage}
            </div>
            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary" onclick="changePage(${currentPage - 1})" ${currentPage <= 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-outline-primary" onclick="changePage(${currentPage + 1})" ${currentPage >= lastPage ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('salesList').insertAdjacentHTML('beforeend', paginationHtml);
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadSales();
}

async function viewSaleDetails(saleId) {
    try {
        const response = await fetch(`/api/sales/${saleId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            showSaleDetailsModal(data.sale, data.details);
            } else {
            showError('Failed to load sale details');
        }
    } catch (error) {
        console.error('Error loading sale details:', error);
        showError('Network error: ' + error.message);
    }
}

function showSaleDetailsModal(sale, details) {
    currentSaleId = sale.kd_penjualan;
    
    let detailsHtml = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>Sale Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Invoice:</strong> ${sale.no_faktur_penjualan}</p>
                        <p><strong>Date:</strong> ${new Date(sale.date_created).toLocaleString('id-ID')}</p>
                        <p><strong>Customer:</strong> ${sale.kd_pelanggan ? 'Customer #' + sale.kd_pelanggan : 'Walk-in Customer'}</p>
                        <p><strong>Status:</strong> <span class="badge bg-${sale.status_bayar === 'Lunas' ? 'success' : 'warning'}">${sale.status_bayar}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator text-primary me-2"></i>Payment Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Subtotal:</strong> Rp ${parseFloat(sale.sub_total || 0).toLocaleString()}</p>
                        <p><strong>Tax:</strong> Rp ${parseFloat(sale.pajak || 0).toLocaleString()}</p>
                        <p><strong>Total:</strong> Rp ${parseFloat(sale.total_harga || 0).toLocaleString()}</p>
                        <p><strong>Payment Method:</strong> ${sale.metode_pembayaran || 'Not specified'}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
        
    if (details.length > 0) {
        detailsHtml += `
            <div class="row mt-4">
                <div class="col-12">
        <div class="card">
            <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list text-primary me-2"></i>Sale Items
                            </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                                <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Product</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Actions</th>
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
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="editSaleItem(${detail.kd_penjualan_detail}, '${detail.nama_produk}', ${detail.harga_jual}, ${detail.qty})" title="Edit item">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteSaleItem(${detail.kd_penjualan_detail})" title="Delete item">
                            <i class="fas fa-trash"></i>
                        </button>
                </td>
            </tr>
        `;
    });
    
        detailsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('saleDetailsContent').innerHTML = detailsHtml;
    
    const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
    modal.show();
}

function editSaleItem(detailId, productName, price, quantity) {
    editingItemId = detailId;
    
    document.getElementById('editProductName').value = productName;
    document.getElementById('editPrice').value = price;
    document.getElementById('editQuantity').value = quantity;
    document.getElementById('editDiscount').value = 0;
    
    calculateEditTotals();
    
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
        const response = await fetch(`/api/sale-items/${editingItemId}`, {
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
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
            modal.hide();
            
            // Refresh sale details
            if (currentSaleId) {
                viewSaleDetails(currentSaleId);
            }
            
            editingItemId = null;
        } else {
            showError('Failed to update item: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

async function deleteSaleItem(detailId) {
    if (!confirm('Are you sure you want to delete this item?')) return;
    
    try {
        const response = await fetch(`/api/sale-items/${detailId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Item deleted successfully!');
            
            // Refresh sale details
            if (currentSaleId) {
                viewSaleDetails(currentSaleId);
            }
        } else {
            showError('Failed to delete item: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

function printSaleReceipt() {
    if (!currentSaleId) {
        showError('No sale selected for printing');
        return;
    }
    
    // Implementation for printing sale receipt
    showSuccess('Print functionality will be implemented');
}

function exportSales() {
    const searchTerm = document.getElementById('searchInput').value;
    const dateRange = document.getElementById('dateRange').value;
    const status = document.getElementById('statusFilter').value;
    
    const url = `/api/sales/export?search=${searchTerm}&date_range=${dateRange}&status=${status}`;
    window.open(url, '_blank');
}

function showError(message) {
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-danger border-0 shadow-lg';
    toast.setAttribute('role', 'alert');
    toast.style.borderRadius = '1rem';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-exclamation-circle fa-2x"></i>
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
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });
    bsToast.show();
    
    setTimeout(() => {
        if (container.parentNode) {
            container.parentNode.removeChild(container);
        }
    }, 5000);
}

function showSuccess(message) {
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0 shadow-lg';
    toast.setAttribute('role', 'alert');
    toast.style.borderRadius = '1rem';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-2x"></i>
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
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
    
    setTimeout(() => {
        if (container.parentNode) {
            container.parentNode.removeChild(container);
        }
    }, 4000);
}
</script>

<style>
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
</style>
@endsection
