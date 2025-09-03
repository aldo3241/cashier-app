@extends('layouts.app')

@section('title', 'Payment Methods')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-credit-card text-primary me-2"></i>
                    Payment Methods
                </h2>
                <div class="text-muted mt-1">Manage payment methods and financial accounts</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                        <i class="fas fa-plus me-2"></i>Add Payment Method
                    </button>
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
                            <div class="col-md-8">
                                <div class="form-group">
                                <label class="form-label">Search Payment Methods</label>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name or type">
                            </div>
                            </div>
                            <div class="col-md-4">
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

        <!-- Payment Methods List -->
        <div class="row">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-credit-card text-primary me-2"></i>Payment Methods
                        </h3>
                        <div class="card-actions">
                            <div class="spinner-border spinner-border-sm text-primary d-none" id="loadingSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="paymentMethodsList">
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                                    </div>
                                    <p class="empty-title">No payment methods found</p>
                                    <p class="empty-subtitle text-muted">
                                        Add your first payment method to get started
                                    </p>
                                    <div class="empty-action">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                                            <i class="fas fa-plus me-2"></i>Add Payment Method
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

<!-- Add/Edit Payment Method Modal -->
<div class="modal modal-blur fade" id="addPaymentMethodModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title" id="modalTitle">
                    <i class="fas fa-plus text-primary me-2"></i>Add Payment Method
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="paymentMethodForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                            <label class="form-label">Payment Method Name</label>
                                <input type="text" class="form-control form-control-lg" id="paymentMethodName" 
                                       placeholder="e.g., Cash, BCA Transfer, Debit Card" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Payment Type</label>
                                <select class="form-select form-select-lg" id="paymentType" required>
                                    <option value="">Select Type</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Card">Card</option>
                                    <option value="Digital Wallet">Digital Wallet</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Account Number (Optional)</label>
                                <input type="text" class="form-control form-control-lg" id="accountNumber" 
                                       placeholder="e.g., 1234567890">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Bank Name (Optional)</label>
                                <input type="text" class="form-control form-control-lg" id="bankName" 
                                       placeholder="e.g., BCA, Mandiri, BNI">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control form-control-lg" id="description" rows="3" 
                                  placeholder="Additional details about this payment method"></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="isActive" checked>
                        <label class="form-check-label" for="isActive">
                            Active (Available for transactions)
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="savePaymentMethod">
                    <i class="fas fa-check me-2"></i>Save Payment Method
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="deletePaymentMethodModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h4 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirm Delete
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                    <h5>Are you sure?</h5>
                    <p class="text-muted">This payment method will be permanently deleted and cannot be recovered.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-2"></i>Delete
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
let editingPaymentMethodId = null;
let deletingPaymentMethodId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadPaymentMethods();
    
    // Event listeners
    document.getElementById('searchBtn').addEventListener('click', function() {
        currentPage = 1;
        loadPaymentMethods();
    });
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentPage = 1;
            loadPaymentMethods();
        }
    });
    
    document.getElementById('savePaymentMethod').addEventListener('click', savePaymentMethod);
    document.getElementById('confirmDelete').addEventListener('click', confirmDeletePaymentMethod);
    
    // Modal event listeners
    document.getElementById('addPaymentMethodModal').addEventListener('hidden.bs.modal', function() {
        resetForm();
    });
});

async function loadPaymentMethods() {
    const searchTerm = document.getElementById('searchInput').value;
    
    try {
        showLoading();
        
        const response = await fetch(`/api/payment-methods?page=${currentPage}&search=${searchTerm}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
                displayPaymentMethods(data.payment_methods);
            updatePagination(data.current_page, data.last_page);
            } else {
            showError('Failed to load payment methods');
        }
    } catch (error) {
        console.error('Error loading payment methods:', error);
        showError('Network error: ' + error.message);
    } finally {
            hideLoading();
    }
}

function displayPaymentMethods(paymentMethods) {
    const container = document.getElementById('paymentMethodsList');
    
    if (paymentMethods.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="empty">
                    <div class="empty-img">
                        <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                    </div>
                    <p class="empty-title">No payment methods found</p>
                    <p class="empty-subtitle text-muted">
                        No payment methods match your search criteria
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
                        <th>Payment Method</th>
                        <th>Type</th>
                        <th>Account Details</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    paymentMethods.forEach(paymentMethod => {
        const statusClass = paymentMethod.is_active ? 'success' : 'secondary';
        const statusText = paymentMethod.is_active ? 'Active' : 'Inactive';
        
        html += `
            <tr>
                <td class="h6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-credit-card me-1"></i>
                        ${paymentMethod.nama_metode_pembayaran}
                    </div>
                </td>
                <td><span class="badge bg-primary">${paymentMethod.jenis_pembayaran}</span></td>
                <td>
                    ${paymentMethod.nomor_rekening ? `Account: ${paymentMethod.nomor_rekening}` : 'N/A'}
                    ${paymentMethod.nama_bank ? `<br><small class="text-muted">${paymentMethod.nama_bank}</small>` : ''}
                </td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                <td>${new Date(paymentMethod.created_at).toLocaleDateString('id-ID')}</td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-warning btn-sm" onclick="editPaymentMethod(${paymentMethod.kd_metode_pembayaran})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deletePaymentMethod(${paymentMethod.kd_metode_pembayaran})" title="Delete">
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
    
    document.getElementById('paymentMethodsList').insertAdjacentHTML('beforeend', paginationHtml);
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadPaymentMethods();
}

function editPaymentMethod(id) {
    editingPaymentMethodId = id;
    
    // Load payment method data
    fetch(`/api/payment-methods/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const paymentMethod = data.payment_method;
                
                document.getElementById('paymentMethodName').value = paymentMethod.nama_metode_pembayaran;
                document.getElementById('paymentType').value = paymentMethod.jenis_pembayaran;
                document.getElementById('accountNumber').value = paymentMethod.nomor_rekening || '';
                document.getElementById('bankName').value = paymentMethod.nama_bank || '';
                document.getElementById('description').value = paymentMethod.deskripsi || '';
                document.getElementById('isActive').checked = paymentMethod.is_active;
                
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit text-primary me-2"></i>Edit Payment Method';
                document.getElementById('savePaymentMethod').innerHTML = '<i class="fas fa-check me-2"></i>Update Payment Method';
                
                const modal = new bootstrap.Modal(document.getElementById('addPaymentMethodModal'));
                modal.show();
            } else {
                showError('Failed to load payment method details');
            }
        })
        .catch(error => {
            showError('Network error: ' + error.message);
        });
}

function deletePaymentMethod(id) {
    deletingPaymentMethodId = id;
    
    const modal = new bootstrap.Modal(document.getElementById('deletePaymentMethodModal'));
    modal.show();
}

async function confirmDeletePaymentMethod() {
    if (!deletingPaymentMethodId) return;
    
    try {
        const response = await fetch(`/api/payment-methods/${deletingPaymentMethodId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Payment method deleted successfully!');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('deletePaymentMethodModal'));
            modal.hide();
            
            loadPaymentMethods();
        } else {
            showError('Failed to delete payment method: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    } finally {
        deletingPaymentMethodId = null;
    }
}

async function savePaymentMethod() {
    const formData = {
        nama_metode_pembayaran: document.getElementById('paymentMethodName').value,
        jenis_pembayaran: document.getElementById('paymentType').value,
        nomor_rekening: document.getElementById('accountNumber').value,
        nama_bank: document.getElementById('bankName').value,
        deskripsi: document.getElementById('description').value,
        is_active: document.getElementById('isActive').checked
    };
    
    if (!formData.nama_metode_pembayaran || !formData.jenis_pembayaran) {
        showError('Please fill in all required fields');
        return;
    }
    
    try {
        const url = editingPaymentMethodId ? 
            `/api/payment-methods/${editingPaymentMethodId}` : 
            '/api/payment-methods';
        
        const method = editingPaymentMethodId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess(editingPaymentMethodId ? 
                'Payment method updated successfully!' : 
                'Payment method added successfully!');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPaymentMethodModal'));
            modal.hide();
            
            loadPaymentMethods();
        } else {
            showError('Failed to save payment method: ' + data.message);
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

function resetForm() {
    editingPaymentMethodId = null;
    
    document.getElementById('paymentMethodForm').reset();
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus text-primary me-2"></i>Add Payment Method';
    document.getElementById('savePaymentMethod').innerHTML = '<i class="fas fa-check me-2"></i>Save Payment Method';
}

function showLoading() {
    document.getElementById('loadingSpinner').classList.remove('d-none');
}

function hideLoading() {
    document.getElementById('loadingSpinner').classList.add('d-none');
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

