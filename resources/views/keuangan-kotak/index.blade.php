@extends('layouts.app')

@section('title', 'Payment Methods Management')

@section('content')
<style>
/* Custom styles for payment methods management */
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
.modal-lg {
    max-width: 600px;
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
</style>

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-credit-card text-primary me-2"></i>
                    Payment Methods Management
                </h2>
                <div class="text-muted mt-1">Manage payment methods and financial categories</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button class="btn btn-primary" onclick="showAddModal()">
                        <i class="ti ti-plus me-2"></i>Add Payment Method
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Search Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-8 col-md-6">
                                <label class="form-label">Search Payment Methods</label>
                                <input type="text" class="form-control" id="searchPaymentMethods" placeholder="Search by payment method name...">
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <label class="form-label">Items per page</label>
                                <select class="form-select" id="perPageFilter">
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-primary w-100" onclick="searchPaymentMethods()" id="searchBtn">
                                    <i class="ti ti-search me-2"></i>Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Methods</h3>
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
                            <h4 class="text-muted">Loading payment methods...</h4>
                            <p class="text-muted">Please wait while we fetch your payment methods</p>
                        </div>
                        
                        <!-- Payment Methods List -->
                        <div id="paymentMethodsList">
                            <!-- Payment methods will be loaded here -->
                        </div>
                        
                        <!-- Pagination -->
                        <div id="paymentMethodsPagination" class="mt-3">
                            <!-- Pagination will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Payment Method Modal -->
<div class="modal modal-blur fade" id="paymentMethodModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="ti ti-plus text-primary me-2"></i>
                    Add Payment Method
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentMethodForm">
                    <input type="hidden" id="editPaymentMethodId">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Payment Method Name</label>
                            <input type="text" class="form-control" id="paymentMethodName" placeholder="e.g., Cash, Credit Card, Bank Transfer" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Note:</strong> Payment method names must be unique. Once created, they can be used in sales transactions.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePaymentMethod()" id="saveBtn">
                    <i class="ti ti-check me-2"></i>Save Payment Method
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="ti ti-alert-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this payment method?</p>
                <p class="text-muted"><strong id="deletePaymentMethodName"></strong></p>
                <div class="alert alert-warning">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="ti ti-trash me-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentPerPage = 20;
let currentPaymentMethodId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadPaymentMethods();
    
    // Add event listeners
    document.getElementById('searchPaymentMethods').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchPaymentMethods();
        }
    });
    
    document.getElementById('perPageFilter').addEventListener('change', function() {
        currentPerPage = parseInt(this.value);
        currentPage = 1;
        loadPaymentMethods();
    });
});

function showLoading() {
    document.getElementById('loadingState').classList.remove('d-none');
    document.getElementById('paymentMethodsList').innerHTML = '';
    document.getElementById('paymentMethodsPagination').innerHTML = '';
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('d-none');
}

function loadPaymentMethods() {
    showLoading();
    const search = document.getElementById('searchPaymentMethods').value;
    
    fetch(`/payment-methods/all?page=${currentPage}&per_page=${currentPerPage}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                displayPaymentMethods(data.payment_methods);
                displayPagination(data.pagination);
            } else {
                showError('Failed to load payment methods: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            showError('Network error: ' + error.message);
        });
}

function displayPaymentMethods(paymentMethods) {
    const container = document.getElementById('paymentMethodsList');
    
    if (paymentMethods.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="empty">
                    <div class="empty-img">
                        <i class="ti ti-credit-card fa-4x text-muted mb-3"></i>
                    </div>
                    <h3 class="empty-title">No payment methods found</h3>
                    <p class="empty-subtitle text-muted">
                        Try adjusting your search criteria or add a new payment method
                    </p>
                    <div class="empty-action">
                        <button class="btn btn-primary btn-lg" onclick="showAddModal()">
                            <i class="ti ti-plus me-2"></i>Add Payment Method
                        </button>
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
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Payment Method Name</th>
                        <th class="text-nowrap">Created Date</th>
                        <th class="text-nowrap">Last Updated</th>
                        <th class="w-1 text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    paymentMethods.forEach(method => {
        const createdDate = new Date(method.date_created).toLocaleDateString('id-ID');
        const updatedDate = new Date(method.date_updated).toLocaleDateString('id-ID');
        
        html += `
            <tr class="align-middle">
                <td>
                    <div class="d-flex align-items-center">
                        <div class="subheader fw-semibold">${method.kd_keuangan_kotak}</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary rounded-pill me-2">
                            <i class="ti ti-credit-card me-1"></i>
                        </span>
                        <div class="text-body-secondary fw-medium">${method.nama}</div>
                    </div>
                </td>
                <td>
                    <div class="text-body-secondary">${createdDate}</div>
                </td>
                <td>
                    <div class="text-body-secondary">${updatedDate}</div>
                </td>
                <td>
                    <div class="btn-list flex-nowrap">
                        <button class="btn btn-sm btn-outline-primary" onclick="editPaymentMethod(${method.kd_keuangan_kotak}, '${method.nama}')" title="Edit">
                            <i class="ti ti-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePaymentMethod(${method.kd_keuangan_kotak}, '${method.nama}')" title="Delete">
                            <i class="ti ti-trash me-1"></i>Delete
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
    const container = document.getElementById('paymentMethodsPagination');
    
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
    loadPaymentMethods();
    document.getElementById('paymentMethodsList').scrollIntoView({ behavior: 'smooth' });
}

function searchPaymentMethods() {
    currentPage = 1;
    loadPaymentMethods();
}

function showAddModal() {
    currentPaymentMethodId = null;
    document.getElementById('modalTitle').innerHTML = '<i class="ti ti-plus text-primary me-2"></i>Add Payment Method';
    document.getElementById('paymentMethodForm').reset();
    document.getElementById('editPaymentMethodId').value = '';
    document.getElementById('nameError').textContent = '';
    document.getElementById('paymentMethodName').classList.remove('is-invalid');
    
    const modal = new bootstrap.Modal(document.getElementById('paymentMethodModal'));
    modal.show();
}

function editPaymentMethod(id, name) {
    currentPaymentMethodId = id;
    document.getElementById('modalTitle').innerHTML = '<i class="ti ti-edit text-primary me-2"></i>Edit Payment Method';
    document.getElementById('editPaymentMethodId').value = id;
    document.getElementById('paymentMethodName').value = name;
    document.getElementById('nameError').textContent = '';
    document.getElementById('paymentMethodName').classList.remove('is-invalid');
    
    const modal = new bootstrap.Modal(document.getElementById('paymentMethodModal'));
    modal.show();
}

function savePaymentMethod() {
    const name = document.getElementById('paymentMethodName').value.trim();
    
    if (!name) {
        document.getElementById('nameError').textContent = 'Payment method name is required';
        document.getElementById('paymentMethodName').classList.add('is-invalid');
        return;
    }
    
    // Clear previous errors
    document.getElementById('nameError').textContent = '';
    document.getElementById('paymentMethodName').classList.remove('is-invalid');
    
    // Disable button and show loading
    const saveBtn = document.getElementById('saveBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Saving...';
    
    const url = currentPaymentMethodId ? `/payment-methods/${currentPaymentMethodId}` : '/payment-methods';
    const method = currentPaymentMethodId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ nama: name })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentMethodModal'));
            modal.hide();
            loadPaymentMethods();
        } else {
            if (data.errors && data.errors.nama) {
                document.getElementById('nameError').textContent = data.errors.nama[0];
                document.getElementById('paymentMethodName').classList.add('is-invalid');
            } else {
                showError(data.message);
            }
        }
    })
    .catch(error => {
        showError('Network error: ' + error.message);
    })
    .finally(() => {
        // Re-enable button
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

function deletePaymentMethod(id, name) {
    currentPaymentMethodId = id;
    document.getElementById('deletePaymentMethodName').textContent = name;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function confirmDelete() {
    if (!currentPaymentMethodId) return;
    
    fetch(`/payment-methods/${currentPaymentMethodId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
            loadPaymentMethods();
        } else {
            showError(data.message);
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

