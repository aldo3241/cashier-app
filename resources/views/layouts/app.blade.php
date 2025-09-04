<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        /* Reset and Base Styles */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
        }
        
        /* App Container */
        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            min-width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed {
            width: 70px;
            min-width: 70px;
        }
        
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .sidebar-footer .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-header {
            padding: 1rem 0.5rem;
        }
        
        .sidebar.collapsed .sidebar-brand {
            margin: 0;
        }
        
        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }
        
        .sidebar.collapsed .nav-link {
            padding: 0.75rem 0.5rem;
            justify-content: center;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        .sidebar.collapsed .sidebar-footer {
            padding: 0.5rem;
        }
        
        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }
        
        .sidebar-brand {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            width: 100%;
        }
        
        .sidebar-brand a {
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        
        .sidebar-brand a:hover {
            color: #667eea;
        }
        
        .sidebar-brand span {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            text-align: center;
        }
        
        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.25rem;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Sidebar Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
            max-height: calc(100vh - 200px);
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .nav-link span {
            font-size: 0.95rem;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            position: sticky;
            bottom: 0;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            z-index: 10;
        }
        
        .sidebar-footer .nav-link {
            margin: 0;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-footer .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            background: #f8f9fa;
            min-height: 100vh;
            transition: all 0.3s ease;
            margin-left: 0;
            width: 100%;
        }
        
        /* When sidebar is hidden (not logged in), main content takes full width */
        .app-container:not(:has(.sidebar)) .main-content {
            width: 100%;
            margin-left: 0;
        }
        
        /* Fallback for browsers that don't support :has() */
        .app-container .main-content {
            width: 100%;
            margin-left: 0;
        }
        
        /* When sidebar exists, adjust main content */
        .app-container .sidebar + .main-content {
            width: calc(100% - 280px);
            margin-left: 0;
        }
        
        .app-container .sidebar.collapsed + .main-content {
            width: calc(100% - 70px);
        }
        
        .content-wrapper {
            padding: 2rem;
            max-width: 100%;
        }
        
        /* Responsive Design */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 1rem;
            }
        }
        
        /* Existing Styles */
        .btn-cashier {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .btn-cashier:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .card-cashier {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        
        .card-cashier:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <div class="app-container">
        @auth
        <!-- Left Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-brand">
                    <a href="{{ route('home') }}">
                        <span>{{ config('app.name', 'CASHIER-APP') }}</span>
                    </a>
                </h1>
                <button class="sidebar-toggle" id="sidebarToggle" type="button">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <div class="sidebar-nav">
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cashier') ? 'active' : '' }}" href="{{ route('cashier') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Cashier</span>
                    </a>
                </div>
                
                @if(auth()->user()->isAdmin())
                <!-- Admin only menu items -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sale-details') ? 'active' : '' }}" href="{{ route('sale-details') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Sales</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}" href="{{ route('payment-methods.index') }}">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Methods</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Role Management</span>
                    </a>
                </div>
                @endif
            </div>
            
            <div class="sidebar-footer">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span>Account</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="dropdown-header">
                            <div class="fw-bold">{{ auth()->user()->nama }}</div>
                            <div class="text-muted small">{{ auth()->user()->getRoleDisplayName() }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            // Only run sidebar logic if sidebar exists (user is logged in)
            if (sidebar) {
                // Check if sidebar state is saved in localStorage
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                }
                
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        console.log('Toggle button clicked!');
                        sidebar.classList.toggle('collapsed');
                        
                        // Save state to localStorage
                        const isNowCollapsed = sidebar.classList.contains('collapsed');
                        localStorage.setItem('sidebarCollapsed', isNowCollapsed);
                        
                        console.log('Sidebar collapsed:', isNowCollapsed);
                    });
                }
                
                // Mobile sidebar toggle (if needed)
                const mobileToggle = document.createElement('button');
                mobileToggle.className = 'mobile-toggle d-lg-none';
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                mobileToggle.style.cssText = `
                    position: fixed;
                    top: 1rem;
                    left: 1rem;
                    z-index: 1001;
                    background: #667eea;
                    color: white;
                    border: none;
                    border-radius: 0.5rem;
                    padding: 0.5rem;
                    font-size: 1.2rem;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                `;
                
                document.body.appendChild(mobileToggle);
                
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 991.98) {
                        if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            } else {
                console.log('Sidebar not found - user not logged in');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
