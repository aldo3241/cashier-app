@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <i class="fas fa-chart-line me-1"></i>
                    Sales Management
                </div>
                <h2 class="page-title">
                    <i class="fas fa-receipt text-primary me-2"></i>
                    Sales Transactions
                </h2>
                <div class="text-muted mt-1">View, manage, and analyze all sales transactions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button class="btn btn-outline-warning" id="debugBtn" title="Debug API Access">
                        <i class="fas fa-bug"></i>
                    </button>
                    <button class="btn btn-outline-primary" id="refreshBtn" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <a href="{{ route('cashier') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Sale
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="fas fa-receipt"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="totalSales">
                                    0
                                </div>
                                <div class="text-muted">Total Sales</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="completedSales">
                                    0
                                </div>
                                <div class="text-muted">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <i class="fas fa-clock"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="pendingSales">
                                    0
                                </div>
                                <div class="text-muted">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="totalRevenue">
                                    Rp 0
                                </div>
                                <div class="text-muted">Total Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <i class="fas fa-filter text-primary me-2"></i>Search & Filters
                                </h3>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn">
                                    <i class="fas fa-times me-1"></i>Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-search me-1"></i>Search
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput" 
                                               placeholder="Invoice number, customer name...">
                                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                            <i class="fas fa-search"></i>
                                        </button>
                            </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Date Range
                                    </label>
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
                                    <label class="form-label">
                                        <i class="fas fa-flag me-1"></i>Status
                                    </label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                        <option value="Lunas">Completed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-list me-1"></i>Per Page
                                    </label>
                                    <select class="form-select" id="perPage">
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="50">50</option>
                                        <option value="100">100</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Custom Date Range (Hidden by default) -->
                        <div class="row g-3 mt-2" id="customDateRange" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="fromDate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="toDate">
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
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <i class="fas fa-receipt text-primary me-2"></i>Sales Transactions
                                </h3>
                                <div class="text-muted" id="resultsInfo">Loading...</div>
                            </div>
                            <div class="col-auto">
                                <div class="btn-list">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-secondary btn-sm" id="viewToggle" title="Toggle View">
                                            <i class="fas fa-th-large" id="viewIcon"></i>
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" id="exportBtn">
                                            <i class="fas fa-download me-1"></i>Export
                                        </button>
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        
                        <!-- Empty State -->
                        <div id="emptyState" class="text-center py-5" style="display: none;">
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
                                        <i class="fas fa-plus me-2"></i>Create New Sale
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sales List Container -->
                        <div id="salesList">
                            <!-- Sales will be loaded here -->
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer" id="paginationContainer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted" id="paginationInfo">
                                    <!-- Pagination info will be loaded here -->
                                </div>
                                <div id="pagination">
                                    <!-- Pagination buttons will be loaded here -->
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
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-white bg-opacity-10 me-3">
                        <i class="fas fa-receipt"></i>
            </div>
                    <div>
                        <h4 class="modal-title mb-0">Sale Details</h4>
                        <small class="opacity-75" id="modalInvoiceNumber">Invoice #</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="saleDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="editSaleBtn" style="display: none;">
                            <i class="fas fa-edit me-2"></i>Edit Sale
                        </button>
                        <button type="button" class="btn btn-primary" id="printSaleBtn">
                            <i class="fas fa-print me-2"></i>Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal modal-blur fade" id="editItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning text-white">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-white bg-opacity-10 me-3">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h4 class="modal-title mb-0">Edit Sale Item</h4>
                        <small class="opacity-75">Modify product details</small>
                    </div>
                </div>
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

@section('styles')
<style>
/* Custom Sales Page Styles */
.sale-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.sale-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    border-color: #206bc4;
}

/* Table Improvements */
.table-vcenter {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.table-vcenter thead th {
    background: linear-gradient(135deg, #206bc4 0%, #1a5aa3 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border: none;
    padding: 1rem 0.75rem;
}

.table-vcenter tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.table-vcenter tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.table-vcenter tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border: none;
}

.sale-card .card-body {
    padding: 1.5rem;
}

.sale-status {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.375rem 0.875rem;
    border-radius: 25px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.sale-status:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.sale-status.completed {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

.sale-status.pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.sale-status.cancelled {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.sale-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #206bc4;
}

.sale-date {
    font-size: 0.875rem;
    color: #6c757d;
}

.sale-customer {
    font-weight: 600;
    color: #495057;
}

.sale-items {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Loading Animation */
.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* View Toggle Styles */
.view-toggle-active {
    background-color: #206bc4 !important;
    color: white !important;
}

/* Custom Modal Styles */
.modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
    padding: 1.5rem;
}

.modal-body {
    padding: 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

/* Stats Cards Animation */
.card-sm {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-sm:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-sm .card-body {
    padding: 1.5rem;
}

.card-sm .avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.card-sm .font-weight-medium {
    font-size: 1.5rem;
    font-weight: 700;
    color: #206bc4;
}

.card-sm .text-muted {
    font-size: 0.875rem;
    font-weight: 500;
}

/* Filter Section */
.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0;
}

.card {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
}

.form-control:focus {
    border-color: #206bc4;
    box-shadow: 0 0 0 0.2rem rgba(32, 107, 196, 0.25);
    border-radius: 8px;
}

.form-select:focus {
    border-color: #206bc4;
    box-shadow: 0 0 0 0.2rem rgba(32, 107, 196, 0.25);
    border-radius: 8px;
}

/* Responsive Improvements */
@media (max-width: 768px) {
    .sale-card .card-body {
        padding: 1rem;
    }
    
    .sale-amount {
        font-size: 1.1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
}

/* Empty State Improvements */
.empty {
    padding: 3rem 1rem;
}

.empty-img {
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-subtitle {
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

/* Pagination Improvements */
.pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: 1px solid #e9ecef;
    color: #495057;
}

.pagination .page-link:hover {
    background-color: #206bc4;
    border-color: #206bc4;
    color: white;
}

.pagination .page-item.active .page-link {
    background-color: #206bc4;
    border-color: #206bc4;
}

/* Search Input Improvements */
.form-control:focus {
    border-color: #206bc4;
    box-shadow: 0 0 0 0.2rem rgba(32, 107, 196, 0.25);
}

/* Button Improvements */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #206bc4 0%, #1a5aa3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1a5aa3 0%, #154a8a 100%);
}

.btn-outline-primary {
    border-color: #206bc4;
    color: #206bc4;
}

.btn-outline-primary:hover {
    background-color: #206bc4;
    border-color: #206bc4;
    color: white;
}

/* Avatar Improvements */
.avatar {
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection

@section('scripts')
<script>
let currentPage = 1;
let totalPages = 1;
let currentSaleId = null;
let editingItemId = null;
let isCardView = false;
let currentPerPage = 20;

document.addEventListener('DOMContentLoaded', function() {
    
    loadSales();
    loadStats();
    

    
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
        // Show/hide custom date range
        const customRange = document.getElementById('customDateRange');
        if (this.value === 'custom') {
            customRange.style.display = 'block';
        } else {
            customRange.style.display = 'none';
        }
    });
    
    document.getElementById('statusFilter').addEventListener('change', function() {
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('perPage').addEventListener('change', function() {
        currentPerPage = parseInt(this.value);
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('clearFiltersBtn').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('dateRange').value = 'all';
        document.getElementById('statusFilter').value = '';
        document.getElementById('perPage').value = '20';
        document.getElementById('customDateRange').style.display = 'none';
        currentPage = 1;
        currentPerPage = 20;
        loadSales();
    });
    
    document.getElementById('refreshBtn').addEventListener('click', function() {
        loadSales();
        loadStats();
    });
    
    document.getElementById('debugBtn').addEventListener('click', function() {
        debugAPI();
    });
    
    document.getElementById('viewToggle').addEventListener('click', function() {
        isCardView = !isCardView;
        const icon = document.getElementById('viewIcon');
        if (isCardView) {
            icon.className = 'fas fa-list';
            this.classList.add('view-toggle-active');
        } else {
            icon.className = 'fas fa-th-large';
            this.classList.remove('view-toggle-active');
        }
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

// Debug function to test API access
async function debugAPI() {
    console.log('=== DEBUGGING API ACCESS ===');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');
        
        // Test stats endpoint
        console.log('Testing /api/sales/stats...');
        const statsResponse = await fetch('/api/sales/stats', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        console.log('Stats Response Status:', statsResponse.status);
        console.log('Stats Response Headers:', Object.fromEntries(statsResponse.headers.entries()));
        
        if (statsResponse.ok) {
            const statsData = await statsResponse.json();
            console.log('Stats Data:', statsData);
            alert('Stats API Working!\n\n' + JSON.stringify(statsData, null, 2));
        } else {
            const errorText = await statsResponse.text();
            console.error('Stats Error:', errorText);
            alert('Stats API Error: ' + statsResponse.status + '\n\n' + errorText);
        }
        
        // Test sales endpoint
        console.log('Testing /api/sales...');
        const salesResponse = await fetch('/api/sales?page=1&per_page=5', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        console.log('Sales Response Status:', salesResponse.status);
        
        if (salesResponse.ok) {
            const salesData = await salesResponse.json();
            console.log('Sales Data:', salesData);
            alert('Sales API Working!\n\n' + JSON.stringify(salesData, null, 2));
        } else {
            const errorText = await salesResponse.text();
            console.error('Sales Error:', errorText);
            alert('Sales API Error: ' + salesResponse.status + '\n\n' + errorText);
        }
        
    } catch (error) {
        console.error('Debug Error:', error);
        alert('Debug Error: ' + error.message);
    }
}

// Load statistics
async function loadStats() {
    console.log('Loading stats...');
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('Stats CSRF token found:', !!csrfToken);
        
        const response = await fetch('/api/sales/stats', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        console.log('Stats response status:', response.status);
        console.log('Stats response headers:', Object.fromEntries(response.headers.entries()));
        
        if (response.ok) {
            const data = await response.json();
            console.log('Stats data received:', data);
            updateStats(data);
        } else {
            const errorText = await response.text();
            console.error('Stats HTTP Error:', response.status, errorText);
            
            if (response.status === 403) {
                showError('Access denied. You need administrator privileges to view sales data.');
            } else if (response.status === 401) {
                showError('Authentication required. Please log in again.');
            } else if (response.status === 419) {
                showError('CSRF token mismatch. Please refresh the page.');
            } else {
                showError(`Failed to load statistics (${response.status}): ${response.statusText}`);
            }
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        showError('Network error: ' + error.message);
    }
}

// Update statistics display
function updateStats(stats) {
    document.getElementById('totalSales').textContent = stats.total_sales || 0;
    document.getElementById('completedSales').textContent = stats.completed_sales || 0;
    document.getElementById('pendingSales').textContent = stats.pending_sales || 0;
    document.getElementById('totalRevenue').textContent = `Rp ${(stats.total_revenue || 0).toLocaleString()}`;
}

async function loadSales() {
    console.log('Loading sales data...');
    
    try {
        // Check if elements exist
        const searchInput = document.getElementById('searchInput');
        const dateRange = document.getElementById('dateRange');
        const statusFilter = document.getElementById('statusFilter');
        
        const searchTerm = searchInput?.value || '';
        const dateRangeValue = dateRange?.value || 'all';
        const status = statusFilter?.value || '';
        
        console.log('Search parameters:', { searchTerm, dateRangeValue, status, currentPage, currentPerPage });
        
        // Check CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF token found:', !!csrfToken);
        
        const params = new URLSearchParams({
            page: currentPage,
            per_page: currentPerPage,
            search: searchTerm,
            date_range: dateRangeValue,
            status: status
        });
        
        const url = `/api/sales?${params}`;
        console.log('Fetching from:', url);
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));
        
        if (response.ok) {
            const data = await response.json();
            console.log('Sales data received:', data);
            
            if (data.success) {
                displaySales(data.sales);
                updatePagination(data.current_page, data.last_page, data.total);
                updateResultsInfo(data.total, data.current_page, data.per_page);
            } else {
                showError('Failed to load sales: ' + (data.message || 'Unknown error'));
            }
        } else {
            const errorText = await response.text();
            console.error('HTTP Error:', response.status, errorText);
            
            if (response.status === 403) {
                showError('Access denied. You need administrator privileges to view sales data.');
            } else if (response.status === 401) {
                showError('Authentication required. Please log in again.');
            } else if (response.status === 419) {
                showError('CSRF token mismatch. Please refresh the page.');
            } else {
                showError(`Failed to load sales (${response.status}): ${response.statusText}`);
            }
        }
    } catch (error) {
        console.error('Error loading sales:', error);
        showError('Network error: ' + error.message);
    }
}


// Show error state
function showError(message) {
    console.error('Sales error:', message);
    
    // Hide sales list and pagination
    document.getElementById('salesList').style.display = 'none';
    document.getElementById('paginationContainer').style.display = 'none';
    
    // Show error in empty state
    const emptyState = document.getElementById('emptyState');
    emptyState.style.display = 'block';
    emptyState.innerHTML = `
        <div class="empty">
            <div class="empty-img">
                <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
            </div>
            <p class="empty-title">Error Loading Sales</p>
            <p class="empty-subtitle text-muted">${message}</p>
            <div class="empty-action">
                <button onclick="loadSales()" class="btn btn-primary">
                    <i class="fas fa-refresh me-2"></i>Try Again
                </button>
            </div>
        </div>
    `;
}

// Update results info
function updateResultsInfo(total, currentPage, perPage) {
    const start = (currentPage - 1) * perPage + 1;
    const end = Math.min(currentPage * perPage, total);
    document.getElementById('resultsInfo').textContent = `Showing ${start}-${end} of ${total} results`;
}

function displaySales(sales) {
    console.log('displaySales called with:', sales);
    console.log('Sales count:', sales.length);
    
    const container = document.getElementById('salesList');
    console.log('Sales container element:', container);
    
    if (sales.length === 0) {
        console.log('No sales data - showing empty state');
        document.getElementById('emptyState').style.display = 'block';
        container.style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
        return;
    }
    
    console.log('Displaying sales data - count:', sales.length);
    
    // Show sales list
    document.getElementById('emptyState').style.display = 'none';
    container.style.display = 'block';
    document.getElementById('paginationContainer').style.display = 'block';
    
    if (isCardView) {
        console.log('Using card view');
        displaySalesCards(sales);
    } else {
        console.log('Using table view');
        displaySalesTable(sales);
    }
}

function displaySalesCards(sales) {
    const container = document.getElementById('salesList');
    
    const cardsHtml = sales.map(sale => `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card sale-card h-100" onclick="viewSaleDetails('${sale.kd_penjualan}')">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="card-title mb-1">${sale.no_faktur_penjualan}</h6>
                            <small class="sale-date">${new Date(sale.date_created).toLocaleDateString()}</small>
                    </div>
                        <span class="sale-status ${sale.status_bayar === 'Lunas' ? 'completed' : sale.status_bayar === 'Batal' ? 'cancelled' : 'pending'}">
                            ${sale.status_bayar}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <div class="sale-customer">${sale.kd_pelanggan || 'Walk-in Customer'}</div>
                        <div class="sale-items">${sale.total_items} items</div>
                </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="sale-amount">Rp ${parseFloat(sale.total_harga).toLocaleString()}</div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </button>
            </div>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = `<div class="row">${cardsHtml}</div>`;
}

function displaySalesTable(sales) {
    const container = document.getElementById('salesList');
    
    const tableHtml = `
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
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${sales.map(sale => `
                        <tr>
                            <td>
                                <div class="font-weight-medium">${sale.no_faktur_penjualan}</div>
                            </td>
                            <td>
                                <div class="sale-customer">${sale.kd_pelanggan || 'Walk-in Customer'}</div>
                            </td>
                            <td>
                                <div class="sale-date">${new Date(sale.date_created).toLocaleDateString()}</div>
                                <div class="text-muted small">${new Date(sale.date_created).toLocaleTimeString()}</div>
                            </td>
                            <td>
                                <span class="badge bg-blue">${sale.total_items || 0}</span>
                            </td>
                            <td>
                                <div class="sale-amount">Rp ${parseFloat(sale.total_harga || 0).toLocaleString()}</div>
                            </td>
                            <td>
                                <span class="sale-status ${sale.status_bayar === 'Lunas' ? 'completed' : sale.status_bayar === 'Batal' ? 'cancelled' : 'pending'}">
                                    ${sale.status_bayar}
                                </span>
                            </td>
                            <td>
                                <div class="text-muted">${sale.metode_pembayaran || 'Cash'}</div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSaleDetails('${sale.kd_penjualan}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = tableHtml;
}

function updatePagination(currentPage, lastPage, total) {
    totalPages = lastPage;
    const paginationContainer = document.getElementById('pagination');
    const paginationInfo = document.getElementById('paginationInfo');
    
    // Update pagination info
    const start = (currentPage - 1) * currentPerPage + 1;
    const end = Math.min(currentPage * currentPerPage, total);
    paginationInfo.textContent = `Showing ${start}-${end} of ${total} results`;
    
    if (lastPage <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let paginationHtml = '<nav><ul class="pagination justify-content-center">';
    
    // Previous button
    if (currentPage > 1) {
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;"><i class="fas fa-chevron-left"></i></a></li>`;
    }
    
    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(lastPage, currentPage + 2);
    
    if (startPage > 1) {
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`;
        if (startPage > 2) {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a></li>`;
    }
    
    if (endPage < lastPage) {
        if (endPage < lastPage - 1) {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${lastPage}); return false;">${lastPage}</a></li>`;
    }
    
    // Next button
    if (currentPage < lastPage) {
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;"><i class="fas fa-chevron-right"></i></a></li>`;
    }
    
    paginationHtml += '</ul></nav>';
    paginationContainer.innerHTML = paginationHtml;
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadSales();
}



async function viewSaleDetails(saleId) {
    currentSaleId = saleId;
    
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
            alert('Failed to load sale details');
        }
    } catch (error) {
        console.error('Error loading sale details:', error);
        alert('Network error: ' + error.message);
    }
}

function showSaleDetailsModal(sale, details) {
    currentSaleId = sale.kd_penjualan;
    
    // Update modal header
    document.getElementById('modalInvoiceNumber').textContent = `Invoice #${sale.no_faktur_penjualan}`;
    
    let detailsHtml = `
        <div class="p-4">
            <!-- Sale Summary -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Sale Information</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Invoice</small>
                                    <div class="fw-bold">${sale.no_faktur_penjualan}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Date</small>
                                    <div class="fw-bold">${new Date(sale.date_created).toLocaleDateString()}</div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Customer</small>
                                    <div class="fw-bold">${sale.kd_pelanggan || 'Walk-in Customer'}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Status</small>
                                    <div><span class="sale-status ${sale.status_bayar === 'Lunas' ? 'completed' : sale.status_bayar === 'Batal' ? 'cancelled' : 'pending'}">${sale.status_bayar}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Payment Summary</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Subtotal</small>
                                    <div class="fw-bold">Rp ${parseFloat(sale.sub_total || 0).toLocaleString()}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tax</small>
                                    <div class="fw-bold">Rp ${parseFloat(sale.pajak || 0).toLocaleString()}</div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Total</small>
                                    <div class="sale-amount">Rp ${parseFloat(sale.total_harga || 0).toLocaleString()}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Payment Method</small>
                                    <div class="fw-bold">${sale.metode_pembayaran || 'Cash'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Items List -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Items (${details.length})</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Discount</th>
                                    <th>Subtotal</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${details.map(item => `
                                    <tr>
                                        <td>
                                            <div class="fw-bold">${item.nama_produk}</div>
                                            <small class="text-muted">${item.kd_produk}</small>
                                        </td>
                                        <td>Rp ${parseFloat(item.harga_jual).toLocaleString()}</td>
                                        <td class="text-center">${item.qty}</td>
                                        <td>Rp ${parseFloat(item.diskon || 0).toLocaleString()}</td>
                                        <td>Rp ${parseFloat(item.sub_total).toLocaleString()}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning" onclick="editSaleItem('${item.kd_penjualan_detail}')" title="Edit Item">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('saleDetailsContent').innerHTML = detailsHtml;
    
    // Show the modal
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
@endsection
