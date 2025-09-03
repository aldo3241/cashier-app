@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Sales Management</h2>
                <div class="page-pretitle">View and manage all sales transactions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('cashier') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        New Sale
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="card-title">Sales Transactions</h3>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by invoice number...">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="dateRange" class="form-select">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="all">All Status</option>
                        <option value="Lunas">Completed</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="perPage" class="form-select">
                        <option value="10">10 per page</option>
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>

            <div id="salesTable" class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody">
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="d-flex justify-content-between align-items-center">
                <!-- Pagination will be inserted here -->
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentSearch = '';
let currentDateRange = 'all';
let currentStatus = 'all';
let currentPerPage = 20;

document.addEventListener('DOMContentLoaded', function() {
    loadSales();
    
    // Event listeners
    document.getElementById('searchBtn').addEventListener('click', function() {
        currentSearch = document.getElementById('searchInput').value;
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentSearch = this.value;
            currentPage = 1;
            loadSales();
        }
    });
    
    document.getElementById('dateRange').addEventListener('change', function() {
        currentDateRange = this.value;
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('statusFilter').addEventListener('change', function() {
        currentStatus = this.value;
        currentPage = 1;
        loadSales();
    });
    
    document.getElementById('perPage').addEventListener('change', function() {
        currentPerPage = parseInt(this.value);
        currentPage = 1;
        loadSales();
    });
});

function loadSales() {
    const tableBody = document.getElementById('salesTableBody');
    tableBody.innerHTML = '<tr><td colspan="8" class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
    
    const params = new URLSearchParams({
        page: currentPage,
        per_page: currentPerPage,
        search: currentSearch,
        date_range: currentDateRange,
        status: currentStatus
    });
    
    fetch(`/api/sales?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySales(data.sales);
                displayPagination(data.current_page, data.last_page, data.total);
            } else {
                showError('Failed to load sales: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Network error occurred');
        });
}

function displaySales(sales) {
    const tableBody = document.getElementById('salesTableBody');
    
    if (sales.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No sales found</td></tr>';
        return;
    }
    
    tableBody.innerHTML = sales.map(sale => `
        <tr>
            <td>
                <div class="font-weight-medium">${sale.no_faktur_penjualan}</div>
            </td>
            <td>
                <div>${sale.kd_pelanggan || 'Walk-in Customer'}</div>
            </td>
            <td>
                <div>${new Date(sale.date_created).toLocaleDateString()}</div>
                <div class="text-muted">${new Date(sale.date_created).toLocaleTimeString()}</div>
            </td>
            <td>
                <span class="badge bg-blue">${sale.total_items}</span>
            </td>
            <td>
                <div class="font-weight-medium">Rp ${parseFloat(sale.total_harga).toLocaleString()}</div>
            </td>
            <td>
                <span class="badge bg-${sale.status_bayar === 'Lunas' ? 'success' : 'warning'}">
                    ${sale.status_bayar}
                </span>
            </td>
            <td>
                <div>${sale.metode_pembayaran || 'Cash'}</div>
            </td>
            <td>
                <div class="btn-group">
                    <a href="/sale-details?id=${sale.kd_penjualan}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </td>
        </tr>
    `).join('');
}

function displayPagination(currentPage, lastPage, total) {
    const pagination = document.getElementById('pagination');
    
    if (lastPage <= 1) {
        pagination.innerHTML = `<div class="text-muted">Showing ${total} results</div>`;
        return;
    }
    
    let paginationHtml = `
        <div class="text-muted">Showing ${total} results</div>
        <div class="btn-group">
    `;
    
    // Previous button
    if (currentPage > 1) {
        paginationHtml += `<button class="btn btn-sm btn-outline-secondary" onclick="goToPage(${currentPage - 1})">Previous</button>`;
    }
    
    // Page numbers
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
        paginationHtml += `
            <button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-secondary'}" onclick="goToPage(${i})">
                ${i}
            </button>
        `;
    }
    
    // Next button
    if (currentPage < lastPage) {
        paginationHtml += `<button class="btn btn-sm btn-outline-secondary" onclick="goToPage(${currentPage + 1})">Next</button>`;
    }
    
    paginationHtml += '</div>';
    pagination.innerHTML = paginationHtml;
}

function goToPage(page) {
    currentPage = page;
    loadSales();
}

function showError(message) {
    const tableBody = document.getElementById('salesTableBody');
    tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">${message}</td></tr>`;
}
</script>
@endsection
