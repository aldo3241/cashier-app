@extends('layouts.app')

@section('title', 'User Management')

@push('styles')
<style>
/* User Management Styles - Enhanced Theme */
.user-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    color: white;
    text-transform: uppercase;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.user-avatar::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
}

.user-avatar:hover {
    transform: scale(1.15) rotate(5deg);
    box-shadow: 0 12px 30px rgba(0,0,0,0.3);
}

.user-avatar:hover::before {
    left: 100%;
}

.user-avatar.bg-gradient-primary { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
}
.user-avatar.bg-gradient-success { 
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); 
}
.user-avatar.bg-gradient-warning { 
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); 
}
.user-avatar.bg-gradient-danger { 
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); 
}

/* Enhanced Search Container - Premium Theme */
.search-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2.5rem;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    color: white;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.search-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.search-container:hover::before {
    left: 100%;
}

.search-container .form-control,
.search-container .form-select {
    border: none;
    border-radius: 8px;
    padding: 12px 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    font-weight: 500;
}

.search-container .form-control:focus,
.search-container .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    background: rgba(255, 255, 255, 1);
    transform: translateY(-1px);
}

.search-container .input-group-text {
    border: none;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 8px 0 0 8px;
    color: #2c3e50;
    font-weight: 600;
}

.search-container .btn-outline-light {
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.search-container .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-1px);
}

.search-container .btn-light {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #2c3e50;
    font-weight: 500;
    transition: all 0.3s ease;
}

.search-container .btn-light:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.search-container kbd {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 0.75rem;
}

/* Search highlight effect */
.highlight-search {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white;
    padding: 2px 4px;
    border-radius: 4px;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(243, 156, 18, 0.3);
}

/* Enhanced input group styling */
.input-group {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.input-group:focus-within {
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

/* Enhanced form select styling */
.form-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

/* Enhanced Table Card - Premium Theme */
.table-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: none;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.table-enhanced th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 1.5rem 1rem;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
    position: relative;
}

.table-enhanced td {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #f1f3f4;
    vertical-align: middle;
}

.table-enhanced tbody tr {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.table-enhanced tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.01) translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-left: 4px solid #667eea;
}

.table-enhanced tbody tr::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.table-enhanced tbody tr:hover::before {
    opacity: 1;
}

/* Enhanced Action Buttons - Premium Theme */
.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    margin: 0 3px;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.btn-action:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.btn-action:hover::before {
    left: 100%;
}

/* Solid colored action buttons - matching first image */
.btn-action.btn-view {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border: 2px solid #3498db;
    color: white;
}

.btn-action.btn-view:hover {
    background: linear-gradient(135deg, #2980b9 0%, #1f5f8b 100%);
    border-color: #2980b9;
    color: white;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
}

.btn-action.btn-password {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
    border: 2px solid #9b59b6;
    color: white;
}

.btn-action.btn-password:hover {
    background: linear-gradient(135deg, #8e44ad 0%, #7d3c98 100%);
    border-color: #8e44ad;
    color: white;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(155, 89, 182, 0.3);
}

.btn-action.btn-edit {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    border: 2px solid #f39c12;
    color: white;
}

.btn-action.btn-edit:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    border-color: #e67e22;
    color: white;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(243, 156, 18, 0.3);
}

.btn-action.btn-delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border: 2px solid #e74c3c;
    color: white;
}

.btn-action.btn-delete:hover {
    background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
    border-color: #c0392b;
    color: white;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
}

/* Enhanced Stats Cards - Matching Theme */
.stats-card-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.stats-card-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(44, 62, 80, 0.05), transparent);
    transition: left 0.5s;
}

.stats-card-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card-item:hover::before {
    left: 100%;
}

.stats-card-item .card-body {
    padding: 1.5rem;
}

.stats-card-item .avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.stats-card-item:hover .avatar {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.stats-card-item .font-weight-medium {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.stats-card-item .text-muted {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6c757d;
}

/* Avatar color variations */
.stats-card-item .bg-primary {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
}

.stats-card-item .bg-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
}

.stats-card-item .bg-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
}

.stats-card-item .bg-success {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
}

/* Enhanced Role Badges - Premium Theme */
.badge {
    padding: 8px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.badge:hover::before {
    left: 100%;
}

.badge.bg-red {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white;
}

.badge.bg-orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    color: white;
}

.badge.bg-green {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
    color: white;
}

/* Enhanced Role Badges - Matching Theme */
.badge {
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-red {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.badge.bg-blue {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

.badge.bg-green {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
}

.badge.bg-orange {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
}

/* Enhanced Page Header - Matching Theme */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    margin-bottom: 2rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.page-title {
    color: #2c3e50;
    font-weight: 700;
    margin: 0.5rem 0;
}

.page-title i {
    color: #2c3e50;
}

/* Enhanced Buttons - Matching Theme */
.btn-primary {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(44, 62, 80, 0.3);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(44, 62, 80, 0.4);
}

.btn-light {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #2c3e50;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-light:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Enhanced Animations */
.animate-in {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced Alerts - Matching Theme */
.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #27ae60;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #e74c3c;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
    border-left: 4px solid #3498db;
}

/* Enhanced Card Header - Matching Theme */
.card-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border-bottom: none;
    color: white;
    padding: 1.5rem;
}

.card-header h3 {
    color: white;
    margin: 0;
}

.card-header .btn-primary {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
}

.card-header .btn-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.4);
}

/* Responsive Improvements */
@media (max-width: 768px) {
    .search-container {
        padding: 1.5rem;
    }
    
    .stats-card-item {
        margin-bottom: 1rem;
    }
    
    .stats-card-item .card-body {
        padding: 1rem;
    }
    
    .stats-card-item .avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .stats-card-item .font-weight-medium {
        font-size: 1.5rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

</style>
@endpush

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-users text-primary me-2"></i>
                    User Management
                </h2>
                <div class="text-muted mt-1">Manage system users and their roles</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Statistics Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm stats-card-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="totalUsers">{{ $users->count() }}</div>
                                <div class="text-muted">Total Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm stats-card-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <i class="fas fa-crown"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="adminCount">{{ $users->where('role', 'admin')->count() + $users->filter(function($u) { return is_object($u->role) && $u->role->name === 'admin'; })->count() }}</div>
                                <div class="text-muted">Administrators</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm stats-card-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <i class="fas fa-cash-register"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="cashierCount">{{ $users->where('role', 'cashier')->count() + $users->filter(function($u) { return is_object($u->role) && $u->role->name === 'cashier'; })->count() }}</div>
                                <div class="text-muted">Cashiers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm stats-card-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="userCount">{{ $users->where('role', 'user')->count() + $users->filter(function($u) { return is_object($u->role) && $u->role->name === 'user'; })->count() }}</div>
                                <div class="text-muted">Regular Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card table-card animate-in">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    System Users
                                </h3>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Enhanced Search and Filter Section -->
                    <div class="search-container">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" id="userSearch" class="form-control" placeholder="Search by name, username, or email..." autocomplete="off">
                                    <button class="btn btn-outline-light" type="button" id="advancedSearch" title="Advanced Search">
                                        <i class="fas fa-sliders-h"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="roleFilter" class="form-select">
                                    <option value="">🎭 All Roles</option>
                                    <option value="admin">👑 Administrator</option>
                                    <option value="cashier">💰 Cashier</option>
                                    <option value="user">👤 User</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button id="clearFilters" class="btn btn-light flex-fill">
                                        <i class="fas fa-times me-2"></i>Clear
                                    </button>
                                    <button id="refreshData" class="btn btn-light" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <div class="d-flex">
                                    <div>
                                        <i class="fas fa-check-circle me-2"></i>
                                        {{ session('success') }}
                                    </div>
                                </div>
                                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <div class="d-flex">
                                    <div>
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        {{ session('error') }}
                                    </div>
                                </div>
                                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-vcenter table-enhanced">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                                    <div class="text-muted small">ID: {{ $user->kd }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php
                                                $roleName = '';
                                                if (is_object($user->role) && isset($user->role->name)) {
                                                    $roleName = $user->role->name;
                                                } elseif (is_string($user->role)) {
                                                    $roleName = $user->role;
                                                }
                                                $isAdmin = $roleName === 'admin';
                                            @endphp
                                            @php
                                                $roleClass = '';
                                                switch($roleName) {
                                                    case 'admin':
                                                        $roleClass = 'bg-red';
                                                        break;
                                                    case 'cashier':
                                                        $roleClass = 'bg-orange';
                                                        break;
                                                    case 'user':
                                                        $roleClass = 'bg-green';
                                                        break;
                                                    default:
                                                        $roleClass = 'bg-blue';
                                                }
                                            @endphp
                                            <span class="badge {{ $roleClass }}">
                                                {{ $user->getRoleDisplayName() }}
                                            </span>
                                        </td>
                                        <td>{{ $user->date_created ? $user->date_created->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('users.show', $user) }}" class="btn-action btn-view" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.change-password', $user) }}" class="btn-action btn-password" title="Change Password">
                                                    <i class="fas fa-key"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->kd !== auth()->id())
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete" 
                                                            onclick="return confirm('⚠️ Are you sure you want to delete this user?\\n\\nThis action cannot be undone!')"
                                                            title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('User management page initializing...');
    
    const searchInput = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const clearBtn = document.getElementById('clearFilters');
    const refreshBtn = document.getElementById('refreshData');
    const tableRows = document.querySelectorAll('tbody tr');
    
    console.log('Found elements:', {
        searchInput: !!searchInput,
        roleFilter: !!roleFilter,
        clearBtn: !!clearBtn,
        refreshBtn: !!refreshBtn,
        tableRowsCount: tableRows.length
    });
    
    // Enhanced search functionality
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedRole = roleFilter.value.toLowerCase();
        
        let visibleCount = 0;
        let hasResults = false;
        
        tableRows.forEach((row, index) => {
            // Get user data from row
            const nameCell = row.cells[0].textContent.toLowerCase();
            const usernameCell = row.cells[1].textContent.toLowerCase();
            const emailCell = row.cells[2].textContent.toLowerCase();
            const roleCell = row.cells[3].textContent.toLowerCase();
            
            // Check search match
            const searchMatch = !searchTerm || 
                nameCell.includes(searchTerm) || 
                usernameCell.includes(searchTerm) || 
                emailCell.includes(searchTerm);
            
            // Check role match
            const roleMatch = !selectedRole || roleCell.includes(selectedRole);
            
            // Show/hide row with animation
            if (searchMatch && roleMatch) {
                row.style.display = '';
                row.style.animationDelay = `${index * 50}ms`;
                row.classList.add('animate-in');
                visibleCount++;
                hasResults = true;
                
                // Add highlight effect for search term
                if (searchTerm) {
                    highlightSearchTerm(row, searchTerm);
                }
            } else {
                row.style.display = 'none';
                row.classList.remove('animate-in');
            }
        });
        
        // Show no results message if needed
        showNoResultsMessage(!hasResults);
        
        // Update statistics
        updateResultsCount();
        updateFilterStats(visibleCount);
    }
    
    // Highlight search terms
    function highlightSearchTerm(row, searchTerm) {
        const cells = [row.cells[0], row.cells[1], row.cells[2]];
        cells.forEach(cell => {
            const text = cell.textContent;
            const highlightedText = text.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<mark class="highlight-search">${match}</mark>`
            );
            if (highlightedText !== text) {
                cell.innerHTML = highlightedText;
            }
        });
    }
    
    // Show/hide no results message
    function showNoResultsMessage(show) {
        let noResultsDiv = document.getElementById('noResultsMessage');
        
        if (show) {
            if (!noResultsDiv) {
                noResultsDiv = document.createElement('div');
                noResultsDiv.id = 'noResultsMessage';
                noResultsDiv.className = 'alert alert-info text-center mt-3';
                noResultsDiv.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-search me-3 fa-2x text-muted"></i>
                        <div>
                            <h5 class="mb-1">No users found</h5>
                            <p class="mb-0 text-muted">Try adjusting your search criteria or filters</p>
                        </div>
                    </div>
                `;
                const tableContainer = document.querySelector('.table-responsive');
                tableContainer.parentNode.insertBefore(noResultsDiv, tableContainer);
            }
            noResultsDiv.style.display = 'block';
        } else if (noResultsDiv) {
            noResultsDiv.style.display = 'none';
        }
    }
    
    // Update results count with enhanced display
    function updateResultsCount() {
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const totalRows = tableRows.length;
        
        // Create or update count display
        let countDisplay = document.getElementById('resultsCount');
        if (!countDisplay) {
            countDisplay = document.createElement('div');
            countDisplay.id = 'resultsCount';
            countDisplay.className = 'alert alert-info mt-3 mb-3';
            const cardBody = document.querySelector('.card-body');
            cardBody.insertBefore(countDisplay, cardBody.querySelector('.table-responsive'));
        }
        
        const percentage = Math.round((visibleRows.length / totalRows) * 100);
        countDisplay.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                <div>
                    <strong>Showing ${visibleRows.length} of ${totalRows} users</strong>
                    <div class="text-muted small">${percentage}% of total users displayed</div>
                </div>
            </div>
        `;
    }
    
    // Update filter statistics
    function updateFilterStats(visibleCount) {
        // Update the total users counter in stats
        const totalUsersElement = document.getElementById('totalUsers');
        if (totalUsersElement && searchInput.value || roleFilter.value) {
            totalUsersElement.innerHTML = `${visibleCount}<small class="text-white-50 d-block">filtered</small>`;
        } else {
            totalUsersElement.innerHTML = tableRows.length;
        }
    }
    
    // Clear filters with animation
    function clearFilters() {
        searchInput.value = '';
        roleFilter.value = '';
        
        // Add visual feedback
        const clearIcon = clearBtn.querySelector('i');
        clearIcon.classList.add('fa-spin');
        
        setTimeout(() => {
            filterUsers();
            clearIcon.classList.remove('fa-spin');
        }, 300);
    }
    
    // Refresh data
    function refreshData() {
        const refreshIcon = refreshBtn.querySelector('i');
        refreshIcon.classList.add('fa-spin');
        
        setTimeout(() => {
            location.reload();
        }, 500);
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    clearBtn.addEventListener('click', clearFilters);
    refreshBtn.addEventListener('click', refreshData);
    
    // Initialize page
    console.log('Initializing page...');
    updateResultsCount();
    
    // Ensure all rows are visible initially
    tableRows.forEach(row => {
        row.style.display = '';
        row.classList.add('animate-in');
    });
    
    console.log('User management page fully loaded!');
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            clearFilters();
            searchInput.blur();
        }
    });
    
    // Add search tips
    searchInput.setAttribute('title', 'Tip: Use Ctrl+K to quickly focus search, Esc to clear');
    
    // Enhanced user avatar interactions
    const userAvatars = document.querySelectorAll('.user-avatar');
    userAvatars.forEach(avatar => {
        avatar.addEventListener('click', function() {
            // Add a subtle pulse animation
            this.style.animation = 'pulse 0.6s ease-in-out';
            setTimeout(() => {
                this.style.animation = '';
            }, 600);
        });
    });
    
    // Add pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);
    
    // Enhanced table row interactions
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
        
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on action buttons
            if (!e.target.closest('.btn-action')) {
                this.style.background = 'linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%)';
                setTimeout(() => {
                    this.style.background = '';
                }, 300);
            }
        });
    });
    
    // Add loading states
    function showLoading() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(44, 62, 80, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        `;
        loadingOverlay.innerHTML = `
            <div class="text-center text-white">
                <div class="spinner-border mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading users...</div>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
    }
    
    function hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    }
    
    // Enhanced refresh with loading
    const originalRefreshData = refreshData;
    refreshData = function() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            // Simulate refresh
            location.reload();
        }, 1000);
    };
    
    // Add smooth scrolling to top when searching
    function smoothScrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Enhanced search with smooth scroll
    const originalFilterUsers = filterUsers;
    filterUsers = function() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const roleFilter = roleSelect.value;
        let hasResults = false;
        
        tableRows.forEach(row => {
            const name = row.querySelector('td:first-child .fw-bold').textContent.toLowerCase();
            const username = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const role = row.querySelector('td:nth-child(4) .badge').textContent.toLowerCase();
            
            const matchesSearch = name.includes(searchTerm) || 
                                username.includes(searchTerm) || 
                                email.includes(searchTerm);
            const matchesRole = roleFilter === '' || role.includes(roleFilter.toLowerCase());
            
            if (matchesSearch && matchesRole) {
                row.style.display = '';
                hasResults = true;
                highlightSearchTerm(row, searchTerm);
            } else {
                row.style.display = 'none';
            }
        });
        
        if (searchTerm || roleFilter !== '') {
            smoothScrollToTop();
        }
        
        showNoResultsMessage(hasResults);
    };
    
    // Add tooltip functionality
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            const action = this.getAttribute('title') || this.querySelector('i').className;
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Add confirmation for delete actions
    const deleteButtons = document.querySelectorAll('.btn-action.btn-outline-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });
    });
        showLoading();
        setTimeout(() => {
            originalRefreshData();
        }, 500);
    };
    
    console.log('User management page fully loaded with enhanced features!');
});
</script>
@endpush
