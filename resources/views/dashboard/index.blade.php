<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Inspizo Spiritosanto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        /* Professional Color Palette */
        :root {
            /* Primary Brand Colors */
            --color-primary: #2563eb;        /* Blue 600 */
            --color-primary-dark: #1e40af;   /* Blue 700 */
            --color-primary-light: #3b82f6;  /* Blue 500 */
            
            /* Secondary Colors */
            --color-secondary: #8b5cf6;      /* Purple 500 */
            --color-secondary-dark: #7c3aed; /* Purple 600 */
            
            /* Success/Positive */
            --color-success: #10b981;        /* Emerald 500 */
            --color-success-dark: #059669;   /* Emerald 600 */
            --color-success-light: #34d399;  /* Emerald 400 */
            
            /* Warning */
            --color-warning: #f59e0b;        /* Amber 500 */
            --color-warning-dark: #d97706;   /* Amber 600 */
            
            /* Danger/Error */
            --color-danger: #ef4444;         /* Red 500 */
            --color-danger-dark: #dc2626;    /* Red 600 */
            
            /* Info */
            --color-info: #06b6d4;          /* Cyan 500 */
            --color-info-dark: #0891b2;     /* Cyan 600 */
            
            /* Neutral Grays */
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1);
        }
        .quick-action-btn {
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .alert-item {
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Bottom header styles - same as cashier */
        .header-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Ensure proper spacing for bottom header */
        body {
            margin: 0;
            padding: 0;
            padding-bottom: 80px; /* Space for bottom header */
        }
        
        /* Adjust main content for bottom header */
        main {
            margin-bottom: 0;
        }
        
        /* Header Toggle Styles */
        .header-bottom {
            position: relative;
        }
        
        .header-bottom.hidden {
            transform: translateY(100%);
        }
        
        .header-bottom.visible {
            transform: translateY(0);
        }
        
        #header-toggle {
            z-index: 50;
        }
        
        #toggle-icon.rotated {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Bottom Header - Collapsible (Same as Cashier) -->
    <header id="main-header" class="header-bottom bg-white shadow-lg border-t border-gray-200 transition-all duration-300 ease-in-out">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Left: User Info -->
                <div class="flex items-center space-x-3">
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profile-dropdown-btn" class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-full flex items-center justify-center hover:from-emerald-500 hover:to-blue-600 transition-all duration-200 cursor-pointer group shadow-md" title="Profile Menu">
                            <span class="text-white font-bold text-sm group-hover:scale-110 transition-transform duration-200">{{ substr(auth()->user()->nama ?? auth()->user()->username ?? 'U', 0, 1) }}</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown" class="absolute left-0 top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->nama ?? auth()->user()->username ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'No email' }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span data-en="Profile Settings" data-id="Pengaturan Profil">Profile Settings</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span data-en="Logout" data-id="Keluar">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="text-left">
                        <div class="text-sm text-gray-500" data-en="Logged in as" data-id="Masuk sebagai">Masuk sebagai</div>
                        <div class="font-medium text-gray-800">{{ auth()->user()->nama ?? auth()->user()->username ?? 'User' }}</div>
                    </div>
                </div>
                
                <!-- Center: App Title -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-500 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-gray-800">Spiritosanto</h1>
                        <p class="text-sm text-gray-500">Inspizo Cashier System</p>
                    </div>
                </div>
                
                <!-- Right: Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Current Time -->
                    <div class="text-right">
                        <div class="text-sm text-gray-500" data-en="Current Time" data-id="Waktu Saat Ini">Current Time</div>
                        <div class="text-lg font-semibold text-gray-800" id="current-time"></div>
                    </div>
                    
                    <!-- Language Switcher -->
                    <button id="language-toggle" class="flex items-center space-x-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all" title="Change Language">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        <span id="current-lang" class="text-sm font-medium">EN</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Toggle Button -->
        <button id="header-toggle" class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-t-lg transition-all duration-300 ease-in-out shadow-lg">
            <svg id="toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="text-center space-y-4">
                <!-- Sales Report Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('sales.my-sales') }}" target="_blank"
                       class="inline-flex items-center px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white text-xl font-bold rounded-lg shadow-lg quick-action-btn transition-all duration-300">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span data-en="My Sales" data-id="Penjualan Saya">My Sales</span>
                    </a>
                    
                    <a href="{{ route('sales.all-sales') }}" target="_blank"
                       class="inline-flex items-center px-8 py-4 bg-purple-500 hover:bg-purple-600 text-white text-xl font-bold rounded-lg shadow-lg quick-action-btn transition-all duration-300">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span data-en="All Cashiers Sales" data-id="Penjualan Semua Kasir">All Cashiers Sales</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- My Sales Today -->
            <div class="bg-white rounded-lg shadow stat-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" data-en="My Sales Today" data-id="Penjualan Saya Hari Ini">My Sales Today</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $dashboardData['my_sales_today']['formatted_amount'] }}</p>
                        <p class="text-sm text-gray-500">{{ $dashboardData['my_sales_today']['transactions'] }} <span data-en="transactions" data-id="transaksi">transactions</span></p>
                    </div>
                </div>
            </div>

            <!-- All Sales Today -->
            <div class="bg-white rounded-lg shadow stat-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" data-en="All Sales Today" data-id="Semua Penjualan Hari Ini">All Sales Today</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $dashboardData['all_sales_today']['formatted_amount'] }}</p>
                        <p class="text-sm text-gray-500">{{ $dashboardData['all_sales_today']['transactions'] }} <span data-en="transactions" data-id="transaksi">transactions</span></p>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white rounded-lg shadow stat-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" data-en="Total Products" data-id="Total Produk">Total Products</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $dashboardData['total_products'] }}</p>
                        <p class="text-sm text-gray-500" data-en="in inventory" data-id="dalam inventori">in inventory</p>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white rounded-lg shadow stat-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" data-en="Low Stock Alert" data-id="Peringatan Stok Rendah">Low Stock Alert</p>
                        <p class="text-2xl font-bold text-red-500">{{ $dashboardData['low_stock_count'] }}</p>
                        <p class="text-sm text-gray-500" data-en="items need restock" data-id="item perlu restock">items need restock</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Transactions (Left) -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900" data-en="Recent Transactions" data-id="Transaksi Terbaru">Recent Transactions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($dashboardData['recent_transactions'] as $transaction)
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $transaction['id'] }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction['cashier'] }} • {{ $transaction['time'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">{{ $transaction['formatted_amount'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts (Right) -->
            @if($dashboardData['low_stock_products']->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900" data-en="⚠️ Low Stock Alerts" data-id="⚠️ Peringatan Stok Rendah">⚠️ Low Stock Alerts</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($dashboardData['low_stock_products'] as $product)
                        <div class="alert-item flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $product['category'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-red-600">{{ $product['stock'] }} left</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <!-- No Alerts Message -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900" data-en="⚠️ Low Stock Alerts" data-id="⚠️ Peringatan Stok Rendah">⚠️ Low Stock Alerts</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto mb-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-900" data-en="All Good!" data-id="Semua Baik!">All Good!</p>
                        <p class="text-sm text-gray-500" data-en="No low stock alerts at the moment" data-id="Tidak ada peringatan stok rendah saat ini">No low stock alerts at the moment</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <script>

        // Auto-refresh dashboard data every 5 minutes
        setInterval(() => {
            location.reload();
        }, 300000);

        // Language switcher functionality with persistence
        let currentLanguage = localStorage.getItem('language') || 'id'; // Default to Indonesian
        
        document.getElementById('language-toggle').addEventListener('click', function() {
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
            localStorage.setItem('language', currentLanguage);
            updateLanguage();
        });

        function updateLanguage() {
            const langButton = document.getElementById('current-lang');
            const elements = document.querySelectorAll('[data-en], [data-id]');
            
            // Update language button
            langButton.textContent = currentLanguage.toUpperCase();
            
            // Update all elements with data attributes
            elements.forEach(element => {
                if (element.hasAttribute(`data-${currentLanguage}`)) {
                    const text = element.getAttribute(`data-${currentLanguage}`);
                    element.textContent = text;
                }
            });
        }


        // Profile Dropdown Functionality
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        function closeProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.add('hidden');
        }

        // Update current time
        function updateCurrentTime() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Convert to 12-hour format
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            const hoursStr = String(hours);
            
            const timeString = `${hoursStr}:${minutes}:${seconds} ${ampm}`;
            
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Header Toggle Functionality (same as cashier)
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('main-header');
            const toggleBtn = document.getElementById('header-toggle');
            const toggleIcon = document.getElementById('toggle-icon');
            const profileBtn = document.getElementById('profile-dropdown-btn');
            let isHidden = false;

            // Header toggle
            toggleBtn.addEventListener('click', function() {
                if (isHidden) {
                    header.classList.remove('hidden');
                    header.classList.add('visible');
                    toggleIcon.classList.remove('rotated');
                    isHidden = false;
                } else {
                    header.classList.add('hidden');
                    header.classList.remove('visible');
                    toggleIcon.classList.add('rotated');
                    isHidden = true;
                }
            });

            // Profile dropdown toggle
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleProfileDropdown();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#profile-dropdown') && !e.target.closest('#profile-dropdown-btn')) {
                    closeProfileDropdown();
                }
            });

            // Initialize header as visible
            header.classList.add('visible');
            
            // Initialize language on page load
            updateLanguage();
            
            // Initialize and update current time
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000); // Update every second
        });
    </script>
</body>
</html>

