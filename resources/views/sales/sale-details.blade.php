@extends('layouts.app')

@section('title', 'Sale Details Management')

@section('content')
<style>
/* Custom styles for better table appearance */
.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-table {
    margin-bottom: 0;
}

.card-table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 1rem 0.75rem;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.card-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.card-table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.card-table tbody tr:last-child td {
    border-bottom: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    font-weight: 500;
}

.badge.rounded-pill {
    padding-left: 1rem;
    padding-right: 1rem;
}

.btn-list .btn {
    margin-right: 0.25rem;
    transition: all 0.2s ease;
}

.btn-list .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.btn-list .btn:last-child {
    margin-right: 0;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .card-table thead th,
    .card-table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Loading animation */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

#loadingState {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 1rem;
    padding: 3rem 1rem;
}

#loadingState .spinner-border {
    border-width: 0.25rem;
}

#loadingState h4 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

/* Empty state improvements */
.empty {
    padding: 3rem 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 1rem;
    border: 2px dashed #dee2e6;
}

.empty-img {
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: #495057;
}

.empty-subtitle {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    color: #6c757d;
}

/* Modal improvements */
.modal-xl {
    max-width: 95%;
}

@media (max-width: 768px) {
    .modal-xl {
        max-width: 100%;
        margin: 0.5rem;
    }
}

/* Toast improvements */
.toast-container {
    z-index: 9999;
}

.toast {
    min-width: 300px;
}

/* Pagination improvements */
.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.5rem 0.75rem;
    color: #495057;
    border-color: #dee2e6;
}

.page-link:hover {
    color: #0d6efd;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-receipt text-primary me-2"></i>
                    Sale Details Management
                </h2>
                <div class="text-muted mt-1">Manage and edit sale line items</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('cashier') }}" class="btn btn-outline-primary">
                        <i class="ti ti-arrow-left me-2"></i>Back to Cashier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label">Search Sales</label>
                                <input type="text" class="form-control" id="searchSales" placeholder="Invoice number or customer name...">
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Lunas">Paid</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">Items per page</label>
                                <select class="form-select" id="perPageFilter">
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-primary w-100" onclick="searchSales()" id="searchBtn">
                                    <i class="ti ti-search me-2"></i>Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sales Records</h3>
                        <div class="card-actions">
                            <div class="spinner-border spinner-border-sm text-primary d-none" id="loadingSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Loading State -->
                        <div id="loadingState" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h4 class="text-muted">Loading sales data...</h4>
                            <p class="text-muted">Please wait while we fetch your sales records</p>
                        </div>
                        
                        <!-- Sales List -->
                        <div id="salesList">
                            <!-- Sales will be loaded here -->
                        </div>
                        
                        <!-- Pagination -->
                        <div id="salesPagination" class="mt-3">
                            <!-- Pagination will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sale Details Modal -->
<div class="modal modal-blur fade" id="saleDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-receipt text-primary me-2"></i>
                    Sale Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="saleDetailsContent">
                    <!-- Sale details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal modal-blur fade" id="editItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-edit text-primary me-2"></i>
                    Edit Sale Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editDetailId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product Type</label>
                            <input type="text" class="form-control" id="editProductType" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="editQty" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="editHargaJual" min="0" step="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Discount</label>
                            <input type="number" class="form-control" id="editDiskon" min="0" step="100" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cost Price (HPP)</label>
                            <input type="text" class="form-control" id="editHpp" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profit</label>
                            <input type="text" class="form-control" id="editProfit" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateSaleItem()" id="updateBtn">
                    <i class="ti ti-check me-2"></i>Update Item
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentPerPage = 20;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadSales();
    
    // Add event listeners
    document.getElementById('searchSales').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchSales();
        }
    });
    
    document.getElementById('statusFilter').addEventListener('change', searchSales);
    document.getElementById('perPageFilter').addEventListener('change', function() {
        currentPerPage = parseInt(this.value);
        currentPage = 1;
        searchSales();
    });
});

function showLoading() {
    document.getElementById('loadingState').classList.remove('d-none');
    document.getElementById('salesList').innerHTML = '';
    document.getElementById('salesPagination').innerHTML = '';
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('d-none');
}

function loadSales() {
    showLoading();
    const search = document.getElementById('searchSales').value;
    const status = document.getElementById('statusFilter').value;
    
    fetch(`/cashier/sales-with-details?page=${currentPage}&per_page=${currentPerPage}&search=${search}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                displaySales(data.sales);
                displayPagination(data.pagination);
            } else {
                showError('Failed to load sales: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            showError('Network error: ' + error.message);
        });
}

function displaySales(sales) {
    const container = document.getElementById('salesList');
    
    if (sales.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="empty">
                    <div class="empty-img">
                        <i class="ti ti-receipt fa-4x text-muted mb-3"></i>
                    </div>
                    <h3 class="empty-title">No sales found</h3>
                    <p class="empty-subtitle text-muted">
                        Try adjusting your search criteria or create a new sale
                    </p>
                    <div class="empty-action">
                        <a href="{{ route('cashier') }}" class="btn btn-primary btn-lg">
                            <i class="ti ti-plus me-2"></i>Go to Cashier
                        </a>
                    </div>
                </div>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="text-nowrap">Invoice</th>
                        <th class="text-nowrap">Customer</th>
                        <th class="text-nowrap">Items</th>
                        <th class="text-nowrap">Subtotal</th>
                        <th class="text-nowrap">Tax</th>
                        <th class="text-nowrap">Total</th>
                        <th class="text-nowrap">Profit</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Date</th>
                        <th class="w-1 text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    sales.forEach(sale => {
        const date = new Date(sale.date_created).toLocaleDateString('id-ID');
        const statusClass = sale.status_bayar === 'Lunas' ? 'bg-success' : 'bg-warning';
        
        html += `
            <tr class="align-middle">
                <td>
                    <div class="d-flex align-items-center">
                        <div class="subheader fw-semibold">${sale.no_faktur_penjualan}</div>
                    </div>
                </td>
                <td>
                    <div class="text-body-secondary fw-medium">${sale.catatan || 'Walk-in Customer'}</div>
                </td>
                <td>
                    <span class="badge bg-primary rounded-pill">${sale.total_items || 0} items</span>
                </td>
                <td>
                    <div class="text-body-secondary">Rp ${parseInt(sale.sub_total).toLocaleString()}</div>
                </td>
                <td>
                    <div class="text-body-secondary">Rp ${parseInt(sale.pajak).toLocaleString()}</div>
                </td>
                <td>
                    <div class="text-success fw-bold fs-6">Rp ${parseInt(sale.total_harga).toLocaleString()}</div>
                </td>
                <td>
                    <div class="text-success fw-semibold">Rp ${parseInt(sale.total_profit || 0).toLocaleString()}</div>
                </td>
                <td>
                    <span class="badge ${statusClass} rounded-pill">${sale.status_bayar}</span>
                </td>
                <td>
                    <div class="text-body-secondary">${date}</div>
                </td>
                <td>
                    <div class="btn-list flex-nowrap">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewSaleDetails(${sale.kd_penjualan})" title="View Details">
                            <i class="ti ti-eye me-1"></i>View
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function displayPagination(pagination) {
    const container = document.getElementById('salesPagination');
    
    if (pagination.last_page <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous button
    if (pagination.current_page > 1) {
        html += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(${pagination.current_page - 1})">
                    <i class="ti ti-chevron-left"></i>
                    Previous
                </a>
            </li>
        `;
    }
    
    // Page numbers with ellipsis for large numbers
    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
    
    if (startPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(1)">1</a></li>`;
        if (startPage > 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === pagination.current_page) {
            html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${i})">${i}</a></li>`;
        }
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${pagination.last_page})">${pagination.last_page}</a></li>`;
    }
    
    // Next button
    if (pagination.current_page < pagination.last_page) {
        html += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(${pagination.current_page + 1})">
                    Next
                    <i class="ti ti-chevron-right"></i>
                </a>
            </li>
        `;
    }
    
    html += '</ul></nav>';
    
    // Add summary info
    html += `
        <div class="text-center text-muted mt-2">
            Showing ${((pagination.current_page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} results
        </div>
    `;
    
    container.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    loadSales();
    // Scroll to top of the table
    document.getElementById('salesList').scrollIntoView({ behavior: 'smooth' });
}

function searchSales() {
    currentPage = 1;
    loadSales();
}

function viewSaleDetails(saleId) {
    // Show loading in modal
    const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
    modal.show();
    
    document.getElementById('saleDetailsContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading sale details...</p>
        </div>
    `;
    
    fetch(`/cashier/sale/${saleId}/details`)
        .then(response => response.json())
        .then(data => { 
            if (data.success) {
                displaySaleDetails(data.sale, data.details);
            } else {
                document.getElementById('saleDetailsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="ti ti-alert-circle fa-3x text-danger mb-3"></i>
                        <h5>Error Loading Details</h5>
                        <p class="text-muted">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('saleDetailsContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="ti ti-alert-circle fa-3x text-danger mb-3"></i>
                    <h5>Network Error</h5>
                    <p class="text-muted">${error.message}</p>
                </div>
            `;
        });
}

function displaySaleDetails(sale, details) {
    const container = document.getElementById('saleDetailsContent');
    
    let html = `
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ti ti-info-circle text-primary me-2"></i>
                            Sale Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted">Invoice:</td><td><strong>${sale.no_faktur_penjualan}</strong></td></tr>
                            <tr><td class="text-muted">Customer:</td><td>${sale.catatan || 'Walk-in Customer'}</td></tr>
                            <tr><td class="text-muted">Status:</td><td><span class="badge bg-${sale.status_bayar === 'Lunas' ? 'success' : 'warning'}">${sale.status_bayar}</span></td></tr>
                            <tr><td class="text-muted">Date:</td><td>${new Date(sale.date_created).toLocaleString('id-ID')}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ti ti-calculator text-primary me-2"></i>
                            Financial Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted">Subtotal:</td><td><strong>Rp ${parseInt(sale.sub_total).toLocaleString()}</strong></td></tr>
                            <tr><td class="text-muted">Tax (11%):</td><td>Rp ${parseInt(sale.pajak).toLocaleString()}</td></tr>
                            <tr><td class="text-muted">Total:</td><td><strong class="text-success">Rp ${parseInt(sale.total_harga).toLocaleString()}</strong></td></tr>
                            <tr><td class="text-muted">Payment Method:</td><td>${sale.keuangan_kotak}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="ti ti-list text-primary me-2"></i>
                    Sale Items (${details.length} items)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>HPP</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                                <th>Profit</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    details.forEach(detail => {
        html += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="subheader">${detail.nama_produk}</div>
                    </div>
                </td>
                <td><span class="badge bg-secondary">${detail.produk_jenis}</span></td>
                <td>
                    <div class="text-body-secondary">${detail.qty}</div>
                </td>
                <td>
                    <div class="text-body-secondary">Rp ${parseInt(detail.hpp).toLocaleString()}</div>
                </td>
                <td>
                    <div class="text-body-secondary">Rp ${parseInt(detail.harga_jual).toLocaleString()}</div>
                </td>
                <td>
                    <div class="text-body-secondary">Rp ${parseInt(detail.diskon || 0).toLocaleString()}</div>
                </td>
                <td>
                    <div class="fw-bold">Rp ${parseInt(detail.sub_total).toLocaleString()}</div>
                </td>
                <td>
                    <div class="text-success">Rp ${parseInt(detail.laba).toLocaleString()}</div>
                </td>
                <td>
                    <div class="btn-list flex-nowrap">
                        <button class="btn btn-sm btn-outline-primary" onclick="editSaleItem(${detail.kd_penjualan_detail}, '${detail.nama_produk}', '${detail.produk_jenis}', ${detail.qty}, ${detail.harga_jual}, ${detail.diskon || 0}, ${detail.hpp})" title="Edit Item">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeSaleItem(${detail.kd_penjualan_detail})" title="Remove Item">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div></div></div>';
    container.innerHTML = html;
}

function editSaleItem(detailId, productName, productType, qty, hargaJual, diskon, hpp) {
    document.getElementById('editDetailId').value = detailId;
    document.getElementById('editProductName').value = productName;
    document.getElementById('editProductType').value = productType;
    document.getElementById('editQty').value = qty;
    document.getElementById('editHargaJual').value = hargaJual;
    document.getElementById('editDiskon').value = diskon;
    document.getElementById('editHpp').value = hpp;
    
    // Calculate initial profit
    calculateEditProfit();
    
    const modal = new bootstrap.Modal(document.getElementById('editItemModal'));
    modal.show();
}

function calculateEditProfit() {
    const qty = parseInt(document.getElementById('editQty').value) || 0;
    const hargaJual = parseInt(document.getElementById('editHargaJual').value) || 0;
    const diskon = parseInt(document.getElementById('editDiskon').value) || 0;
    const hpp = parseInt(document.getElementById('editHpp').value) || 0;
    
    const profit = (hargaJual - hpp) * qty;
    document.getElementById('editProfit').value = `Rp ${profit.toLocaleString()}`;
}

// Add event listeners for real-time profit calculation
document.addEventListener('DOMContentLoaded', function() {
    const editQty = document.getElementById('editQty');
    const editHargaJual = document.getElementById('editHargaJual');
    const editDiskon = document.getElementById('editDiskon');
    
    if (editQty) editQty.addEventListener('input', calculateEditProfit);
    if (editHargaJual) editHargaJual.addEventListener('input', calculateEditProfit);
    if (editDiskon) editDiskon.addEventListener('input', calculateEditProfit);
});

function updateSaleItem() {
    const detailId = document.getElementById('editDetailId').value;
    const qty = document.getElementById('editQty').value;
    const hargaJual = document.getElementById('editHargaJual').value;
    const diskon = document.getElementById('editDiskon').value;
    
    // Disable button and show loading
    const updateBtn = document.getElementById('updateBtn');
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Updating...';
    
    fetch(`/cashier/sale-item/${detailId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            qty: parseInt(qty),
            harga_jual: parseInt(hargaJual),
            diskon: parseInt(diskon)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Sale item updated successfully');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
            modal.hide();
            
            // Refresh the sale details
            const saleDetailsModal = bootstrap.Modal.getInstance(document.getElementById('saleDetailsModal'));
            if (saleDetailsModal) {
                saleDetailsModal.hide();
            }
            
            // Reload sales list
            loadSales();
        } else {
            showError('Failed to update sale item: ' + data.message);
        }
    })
    .catch(error => {
        showError('Network error: ' + error.message);
    })
    .finally(() => {
        // Re-enable button
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
    });
}

function removeSaleItem(detailId) {
    if (!confirm('Are you sure you want to remove this item? This action cannot be undone.')) {
        return;
    }
    
    fetch(`/cashier/sale-item/${detailId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Sale item removed successfully');
            
            // Close modals and refresh
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
            if (editModal) editModal.hide();
            
            const detailsModal = bootstrap.Modal.getInstance(document.getElementById('saleDetailsModal'));
            if (detailsModal) detailsModal.hide();
            
            loadSales();
        } else {
            showError('Failed to remove sale item: ' + data.message);
        }
    })
    .catch(error => {
        showError('Network error: ' + error.message);
    });
}

function showSuccess(message) {
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
    
    setTimeout(() => {
        if (container.parentNode) {
            container.parentNode.removeChild(container);
        }
    }, 5000);
}

function showError(message) {
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
    
    setTimeout(() => {
        if (container.parentNode) {
            container.parentNode.removeChild(container);
        }
    }, 5000);
}
</script>
@endsection
