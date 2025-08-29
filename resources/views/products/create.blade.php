@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-plus text-primary me-2"></i>Add New Product
                </h2>
                <div class="text-muted mt-1">Create a new product for your inventory</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-package me-2"></i>Product Information
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible mb-4">
                                <i class="ti ti-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <i class="ti ti-alert-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Basic Information</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Product Name</label>
                                                        <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                                               name="nama_produk" value="{{ old('nama_produk') }}" 
                                                               placeholder="Enter product name" required>
                                                        @error('nama_produk')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Product Type</label>
                                                        <select class="form-select @error('kd_produk_jenis') is-invalid @enderror" 
                                                                name="kd_produk_jenis" required>
                                                            <option value="">Select product type</option>
                                                            @foreach($productTypes as $type)
                                                                <option value="{{ $type->kd_produk_jenis }}" 
                                                                        {{ old('kd_produk_jenis') == $type->kd_produk_jenis ? 'selected' : '' }}>
                                                                    {{ $type->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('kd_produk_jenis')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Barcode</label>
                                                        <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                                               name="barcode" value="{{ old('barcode') }}" 
                                                               placeholder="Enter barcode">
                                                        @error('barcode')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Material</label>
                                                        <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                                               name="material" value="{{ old('material') }}" 
                                                               placeholder="Enter material">
                                                        @error('material')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Specifications</label>
                                                        <input type="text" class="form-control @error('spesifik') is-invalid @enderror" 
                                                               name="spesifik" value="{{ old('spesifik') }}" 
                                                               placeholder="Enter specifications">
                                                        @error('spesifik')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Size</label>
                                                        <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                                                               name="ukuran" value="{{ old('ukuran') }}" 
                                                               placeholder="Enter size">
                                                        @error('ukuran')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Unit</label>
                                                        <input type="text" class="form-control @error('satuan') is-invalid @enderror" 
                                                               name="satuan" value="{{ old('satuan') }}" 
                                                               placeholder="e.g., pcs, kg, m">
                                                        @error('satuan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Weight (grams)</label>
                                                        <input type="number" class="form-control @error('berat') is-invalid @enderror" 
                                                               name="berat" value="{{ old('berat') }}" 
                                                               placeholder="Enter weight in grams">
                                                        @error('berat')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Stock -->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Pricing & Stock</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label class="form-label required">Cost Price (HPP)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control @error('hpp') is-invalid @enderror" 
                                                           name="hpp" value="{{ old('hpp') }}" 
                                                           placeholder="0" min="0" step="0.01" required>
                                                </div>
                                                @error('hpp')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label required">Selling Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" 
                                                           name="harga_jual" value="{{ old('harga_jual') }}" 
                                                           placeholder="0" min="0" step="0.01" required>
                                                </div>
                                                @error('harga_jual')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-hint">Must be greater than or equal to cost price</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label required">Initial Stock</label>
                                                <input type="number" class="form-control @error('stok_total') is-invalid @enderror" 
                                                       name="stok_total" value="{{ old('stok_total', 0) }}" 
                                                       placeholder="0" min="0" required>
                                                @error('stok_total')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">Supplier</label>
                                                <input type="text" class="form-control @error('pemasok') is-invalid @enderror" 
                                                       name="pemasok" value="{{ old('pemasok') }}" 
                                                       placeholder="Enter supplier name">
                                                @error('pemasok')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Product Image</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Upload Image</label>
                                                <input type="file" class="form-control @error('gambar_produk') is-invalid @enderror" 
                                                       name="gambar_produk" accept="image/*">
                                                @error('gambar_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-hint">Max size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <button type="submit" class="btn btn-primary btn-lg me-3">
                                                <i class="ti ti-device-floppy me-2"></i>Save Product
                                            </button>
                                            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg">
                                                <i class="ti ti-x me-2"></i>Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate selling price based on cost price + 20% markup
    const hppInput = document.querySelector('input[name="hpp"]');
    const hargaJualInput = document.querySelector('input[name="harga_jual"]');
    
    hppInput.addEventListener('input', function() {
        const hpp = parseFloat(this.value) || 0;
        if (hpp > 0 && !hargaJualInput.value) {
            const markup = hpp * 0.2; // 20% markup
            hargaJualInput.value = (hpp + markup).toFixed(2);
        }
    });

    // Validate selling price is >= cost price
    hargaJualInput.addEventListener('input', function() {
        const hpp = parseFloat(hppInput.value) || 0;
        const hargaJual = parseFloat(this.value) || 0;
        
        if (hpp > 0 && hargaJual < hpp) {
            this.setCustomValidity('Selling price must be greater than or equal to cost price');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
