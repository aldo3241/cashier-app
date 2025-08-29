@extends('layouts.app')

@section('title', 'Products')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-package text-primary me-2"></i>Product Catalog
                </h2>
                <div class="text-muted mt-1">Browse and manage your product inventory</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i>Add Product
                    </a>
                    <button class="btn btn-outline-primary">
                        <i class="ti ti-download me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4">
                <i class="ti ti-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-4">
                <i class="ti ti-alert-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-search me-2"></i>Search & Filters
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('products.index') }}">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Search by name or barcode..." 
                                               value="{{ request('search') }}">
                                    </div>
                                </div>

                                <!-- Filters -->
                                <div class="col-md-3">
                                    <select name="type" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Types</option>
                                        @foreach($productTypes as $type)
                                            <option value="{{ $type->kd_produk_jenis }}" {{ request('type') == $type->kd_produk_jenis ? 'selected' : '' }}>
                                                {{ $type->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <select name="stock" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Stock</option>
                                        <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="low_stock" {{ request('stock') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                        <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-search me-1"></i>Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="mt-3">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-x me-1"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sort Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        <i class="ti ti-info-circle me-2"></i>
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    <div class="d-flex gap-2">
                        <span class="text-muted me-2">Sort by:</span>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kd_produk', 'order' => 'desc']) }}" 
                           class="btn btn-sm {{ request('sort') == 'kd_produk' && request('order') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="ti ti-clock me-1"></i>Newest First
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kd_produk', 'order' => 'asc']) }}" 
                           class="btn btn-sm {{ request('sort') == 'kd_produk' && request('order') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="ti ti-clock me-1"></i>Oldest First
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_produk', 'order' => 'asc']) }}" 
                           class="btn btn-sm {{ request('sort') == 'nama_produk' && request('order') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="ti ti-sort-ascending me-1"></i>Name A-Z
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'harga_jual', 'order' => 'desc']) }}" 
                           class="btn btn-sm {{ request('sort') == 'harga_jual' && request('order') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="ti ti-currency-dollar me-1"></i>Price High-Low
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row row-cards">
            @forelse($products as $product)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card card-cashier h-100">
                        <!-- Product Image -->
                        <div class="card-img-top img-responsive img-responsive-16by9" style="background-image: url({{ $product->gambar_produk ? asset('storage/' . $product->gambar_produk) : asset('https://via.placeholder.com/300x200?text=No+Image') }})">
                        </div>
                        
                        <!-- Product Info -->
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title mb-2">
                                <a href="{{ route('products.show', $product->kd_produk) }}" class="text-decoration-none">
                                    {{ $product->nama_produk }}
                                </a>
                            </h3>
                            
                            <div class="text-muted mb-2">
                                <small>
                                    <i class="ti ti-tag me-1"></i>
                                    {{ $product->productType ? $product->productType->nama : 'Unknown Type' }}
                                </small>
                            </div>
                            
                            @if($product->barcode)
                            <div class="text-muted mb-2">
                                <small>
                                    <i class="ti ti-barcode me-1"></i>
                                    {{ $product->barcode }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="mb-2">
                                <span class="badge bg-{{ $product->stock_status_class }}">
                                    {{ $product->stock_status }}
                                </span>
                            </div>
                            
                            <div class="text-h4 text-primary mb-2">
                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                            </div>
                            
                            <div class="text-muted mb-3">
                                Stock: <strong>{{ $product->stok_total }}</strong>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                <div class="btn-group w-100">
                                    <a href="{{ route('products.show', $product->kd_produk) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('products.edit', $product->kd_produk) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="ti ti-edit me-1"></i>Edit
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteProduct({{ $product->kd_produk }})">
                                        <i class="ti ti-trash me-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-cashier">
                        <div class="card-body text-center py-5">
                            <div class="empty">
                                <div class="empty-img">
                                    <i class="ti ti-package fa-4x text-muted"></i>
                                </div>
                                <p class="empty-title">No products found</p>
                                <p class="empty-subtitle text-muted">
                                    @if(request('search') || request('type') || request('stock'))
                                        Try adjusting your search criteria or 
                                    @endif
                                    <a href="{{ route('products.create') }}" class="text-primary">add your first product</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">Are you sure?</div>
                <div>If you proceed, you will lose all data about this product.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Yes, delete product</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let productToDelete = null;

function deleteProduct(productId) {
    productToDelete = productId;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDelete').addEventListener('click', async function() {
    if (!productToDelete) return;
    
    try {
        const response = await fetch(`/products/${productToDelete}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ti ti-check-circle me-2"></i>
                        <strong>Success:</strong> ${data.message}
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
            
            // Reload page after a short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
            
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        // Show error message
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Error:</strong> ${error.message}
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
    }
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
    modal.hide();
    productToDelete = null;
});
</script>
@endsection
