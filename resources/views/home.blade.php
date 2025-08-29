@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-dashboard text-primary me-2"></i>
                    Dashboard
                </h2>
                <div class="text-muted mt-1">Welcome to your cashier management system</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Stats Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Sales</div>
                        </div>
                        <div class="h1 mb-3">Rp 2,500,000</div>
                        <div class="d-flex mb-2">
                            <div>Today's sales</div>
                            <div class="ms-auto">
                                <span class="text-green d-inline-flex align-items-center lh-1">
                                    +7% <i class="ti ti-trending-up"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Products</div>
                        </div>
                        <div class="h1 mb-3">1,234</div>
                        <div class="d-flex mb-2">
                            <div>Total products</div>
                            <div class="ms-auto">
                                <span class="text-blue d-inline-flex align-items-center lh-1">
                                    <i class="ti ti-package"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Transactions</div>
                        </div>
                        <div class="h1 mb-3">89</div>
                        <div class="d-flex mb-2">
                            <div>Today's transactions</div>
                            <div class="ms-auto">
                                <span class="text-green d-inline-flex align-items-center lh-1">
                                    +12% <i class="ti ti-trending-up"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Low Stock</div>
                        </div>
                        <div class="h1 mb-3">15</div>
                        <div class="d-flex mb-2">
                            <div>Products needing restock</div>
                            <div class="ms-auto">
                                <span class="text-warning d-inline-flex align-items-center lh-1">
                                    <i class="ti ti-alert-triangle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-bolt me-2"></i>Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('cashier') }}" class="btn btn-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-cash-register fa-3x mb-3"></i>
                                    <span class="h5">Open Cashier</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('products.index') }}" class="btn btn-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-package fa-3x mb-3"></i>
                                    <span class="h5">Manage Products</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('sales') }}" class="btn btn-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-chart-line fa-3x mb-3"></i>
                                    <span class="h5">View Sales</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('products.create') }}" class="btn btn-warning btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-plus fa-3x mb-3"></i>
                                    <span class="h5">Add Product</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="btn btn-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-report-analytics fa-3x mb-3"></i>
                                    <span class="h5">Reports</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-clock me-2"></i>Recent Transactions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>INV-20241201-ABC123</td>
                                        <td>John Doe</td>
                                        <td>Rp 150,000</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>2 min ago</td>
                                    </tr>
                                    <tr>
                                        <td>INV-20241201-DEF456</td>
                                        <td>Jane Smith</td>
                                        <td>Rp 75,000</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>15 min ago</td>
                                    </tr>
                                    <tr>
                                        <td>INV-20241201-GHI789</td>
                                        <td>Bob Johnson</td>
                                        <td>Rp 200,000</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>1 hour ago</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-bell me-2"></i>Notifications
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="status-dot status-dot-dot bg-warning"></span>
                                    </div>
                                    <div class="col text-truncate">
                                        <span class="text-reset d-block">Low stock alert</span>
                                        <div class="d-block text-muted text-truncate mt-n1">
                                            Product "Widget A" is running low
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="status-dot status-dot-dot bg-success"></span>
                                    </div>
                                    <div class="col text-truncate">
                                        <span class="text-reset d-block">New sale completed</span>
                                        <div class="d-block text-muted text-truncate mt-n1">
                                            Invoice INV-20241201-ABC123
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="status-dot status-dot-dot bg-info"></span>
                                    </div>
                                    <div class="col text-truncate">
                                        <span class="text-reset d-block">System update</span>
                                        <div class="d-block text-muted text-truncate mt-n1">
                                            Cashier system updated to v2.0
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
</div>
@endsection


