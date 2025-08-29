@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <i class="ti ti-arrow-left me-2"></i>Back to Products
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ Str::limit($product->nama_produk, 50) }}
            </li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image Section -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if($product->gambar_produk)
                        <div class="product-detail-image-container">
                            <img src="{{ $product->gambar_produk }}" class="img-fluid rounded" 
                                 alt="{{ $product->nama_produk }}">
                        </div>
                    @else
                        <div class="product-detail-placeholder">
                            <i class="ti ti-photo text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted">No product image available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="col-lg-7 col-md-6">
            <!-- Main Product Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="h3 mb-2 text-dark">{{ $product->nama_produk }}</h2>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="badge bg-light text-dark border">
                                    <i class="ti ti-tag me-1"></i>
                                    {{ $product->productType ? $product->productType->nama : 'N/A' }}
                                </span>
                                <span class="badge bg-{{ $product->stock_status_class }} rounded-pill">
                                    <i class="ti ti-{{ $product->stock_status_class == 'success' ? 'check' : ($product->stock_status_class == 'warning' ? 'alert-triangle' : 'x') }} me-1"></i>
                                    {{ $product->stock_status }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="product-id text-muted small">
                                ID: {{ $product->kd_produk }}
                            </div>
                        </div>
                    </div>

                    <!-- Price Section -->
                    <div class="price-detail-section mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-primary mb-2">{{ $product->formatted_price }}</h3>
                                @if($product->hpp && $product->hpp > 0)
                                    <p class="text-muted mb-0">
                                        <small>Cost: {{ $product->formatted_hpp }}</small>
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end">
                                @if($product->prediksi_laba && $product->prediksi_laba > 0)
                                    <span class="badge bg-success">
                                        <i class="ti ti-chart-line me-1"></i>
                                        Profit: {{ 'IDR ' . number_format($product->prediksi_laba, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-primary flex-fill">
                            <i class="ti ti-edit me-2"></i>Edit Product
                        </button>
                        <button class="btn btn-success flex-fill">
                            <i class="ti ti-plus me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="ti ti-printer"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stock Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="ti ti-boxes me-2 text-primary"></i>Stock Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="stock-stat">
                                <div class="stock-number text-success">{{ $product->stok_masuk ?? 0 }}</div>
                                <div class="stock-label">Stock In</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stock-stat">
                                <div class="stock-number text-warning">{{ $product->stok_keluar ?? 0 }}</div>
                                <div class="stock-label">Stock Out</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stock-stat">
                                <div class="stock-number text-primary fw-bold">{{ $product->stok_total ?? 0 }}</div>
                                <div class="stock-label">Total Stock</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Unit: <strong>{{ $product->satuan ?? 'pcs' }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="ti ti-info-circle me-2 text-primary"></i>Product Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Type:</span>
                                    <span class="info-value">{{ $product->productType ? $product->productType->nama : 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Barcode:</span>
                                    <span class="info-value">{{ $product->barcode ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Material:</span>
                                    <span class="info-value">{{ $product->material ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Specification:</span>
                                    <span class="info-value">{{ $product->spesifik ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Size:</span>
                                    <span class="info-value">{{ $product->ukuran ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Weight:</span>
                                    <span class="info-value">{{ $product->berat ? $product->berat . ' kg' : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Business Information</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Payment System:</span>
                                    <span class="info-value">{{ $product->sistem_bayar ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Supplier:</span>
                                    <span class="info-value">{{ $product->pemasok ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Category:</span>
                                    <span class="info-value">{{ $product->jenis ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Created By:</span>
                                    <span class="info-value">{{ $product->dibuat_oleh ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Internal Code:</span>
                                    <span class="info-value">{{ $product->kd_int ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">External Code:</span>
                                    <span class="info-value">{{ $product->kd_ext ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between text-muted small">
                                <span>
                                    <i class="ti ti-calendar-plus me-1"></i>
                                    Created: {{ $product->date_created ? $product->date_created->format('d M Y H:i') : 'N/A' }}
                                </span>
                                <span>
                                    <i class="ti ti-calendar-edit me-1"></i>
                                    Updated: {{ $product->date_updated ? $product->date_updated->format('d M Y H:i') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Product Detail Image */
.product-detail-image-container {
    text-align: center;
}

.product-detail-image-container img {
    max-height: 400px;
    width: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-detail-placeholder {
    text-align: center;
    padding: 3rem 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

/* Price Detail Section */
.price-detail-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

/* Stock Statistics */
.stock-stat {
    padding: 1rem;
}

.stock-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stock-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Information Grid */
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #495057;
    min-width: 120px;
}

.info-value {
    color: #6c757d;
    text-align: right;
    max-width: 200px;
    word-wrap: break-word;
}

/* Breadcrumb Styling */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

/* Card Styling */
.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.5rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .product-detail-image-container img {
        max-height: 300px;
    }
    
    .stock-stat {
        padding: 0.5rem;
    }
    
    .stock-number {
        font-size: 1.5rem;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .info-value {
        text-align: left;
        max-width: 100%;
    }
}
</style>
@endsection
