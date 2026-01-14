<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cashier Period Report - Inspizo Spiritosanto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom DataTable Styling */
        .dataTables_wrapper {
            padding: 20px;
        }

        table.dataTable thead th {
            background: linear-gradient(135deg, #8b5cf6);
            color: white;
            font-weight: 600;
            padding: 12px 18px;
            border: none;
        }

        table.dataTable tbody tr {
            transition: all 0.2s ease;
        }

        table.dataTable tbody tr:hover {
            background-color: #f3f4f6;
        }

        table.dataTable tbody td {
            padding: 12px 18px;
        }

        .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }

        .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-refunded {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-belum-lunas {
            background-color: #fed7aa;
            color: #c2410c;
        }
    </style>

    <style>
        /* Shared Responsive Styles */
        body {
            margin: 0;
            padding: 0;
            padding-top: 60px; /* Space for mobile top header */
        }

        @media (min-width: 1024px) {
            body {
                padding-top: 0;
            }
        }
        
        /* Mobile Menu Drawer Styles */
        .mobile-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 80%;
            max-width: 320px;
            height: 100%;
            z-index: 9000;
            transform: translateX(100%);
            transition: transform 0.3s ease-out;
            box-shadow: -4px 0 12px rgba(0, 0, 0, 0.1);
        }

        .mobile-drawer.open {
            transform: translateX(0);
        }

        .mobile-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 8999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-out;
        }

        .mobile-drawer-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Mobile Top Header (Visible on small screens) -->
    <header id="mobile-top-header" class="fixed top-0 left-0 right-0 lg:hidden bg-white shadow-md z-40 px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo/Title Center -->
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Spiritosanto</h1>
            </div>
            <!-- Menu Toggle Right -->
            <button id="mobile-menu-btn" class="p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Open menu">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Header -->
    <header class="hidden lg:block bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800" data-en="Cashier Period Report" data-id="Laporan Periode Kasir">Cashier Period Report</h1>
                        <p class="text-sm text-gray-600">{{ $user->nama ?? $user->username }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Toggle -->
                    <button id="language-toggle" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        <span id="current-lang" class="text-sm font-medium">EN</span>
                    </button>

                    <!-- Back Button -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span data-en="Back" data-id="Kembali">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Filter Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4" data-en="Filter Period" data-id="Filter Periode">Filter Period</h2>
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1" data-en="Start Date" data-id="Tanggal Mulai">Start Date</label>
                    <input type="text" id="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Select start date">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1" data-en="End Date" data-id="Tanggal Akhir">End Date</label>
                    <input type="text" id="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Select end date">
                </div>
                <div class="w-full md:w-auto">
                    <button id="filter-btn" class="w-full md:w-auto px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <span data-en="Apply Filter" data-id="Terapkan Filter">Apply Filter</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Total Income" data-id="Total Pemasukan">Total Income</p>
                        <p class="text-2xl font-bold text-emerald-600" id="total-income">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Total Expenses" data-id="Total Pengeluaran">Total Expenses</p>
                        <p class="text-2xl font-bold text-red-600" id="total-expense">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Total Profit" data-id="Total Keuntungan">Total Profit</p>
                        <p class="text-2xl font-bold text-blue-600" id="total-profit">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Sales Trend Chart -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4" data-en="Sales & Expense Trend" data-id="Tren Penjualan & Pengeluaran">Sales & Expense Trend</h3>
                <div class="relative h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4" data-en="Payment Methods" data-id="Metode Pembayaran">Payment Methods</h3>
                <div class="relative h-64 flex justify-center">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4" data-en="Top Selling Products" data-id="Produk Terlaris">Top Selling Products</h3>
                <div class="overflow-y-auto max-h-64 custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-en="Product" data-id="Produk">Product</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-en="Qty" data-id="Jml">Qty</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-en="Revenue" data-id="Pendapatan">Revenue</th>
                            </tr>
                        </thead>
                        <tbody id="top-products-list" class="bg-white divide-y divide-gray-200">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cashier Performance -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4" data-en="Cashier Performance" data-id="Performa Kasir">Cashier Performance</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-en="Cashier" data-id="Kasir">Cashier</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-en="Transactions" data-id="Transaksi">Transactions</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-en="Revenue" data-id="Pendapatan">Revenue</th>
                            </tr>
                        </thead>
                        <tbody id="cashier-stats-list" class="bg-white divide-y divide-gray-200">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800" data-en="Report Data" data-id="Data Laporan">Report Data</h2>
            </div>
            
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="hidden px-6 py-4 text-center">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span data-en="Loading data..." data-id="Memuat data...">Loading data...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="reportTable" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                        <tr>
                            <th data-en="Start Date" data-id="Tanggal Mulai">Start Date</th>
                            <th data-en="End Date" data-id="Tanggal Akhir">End Date</th>
                            <th data-en="Income" data-id="Pemasukan">Income</th>
                            <th data-en="Correction (+)" data-id="Koreksi Masuk">Correction (+)</th>
                            <th data-en="Expense" data-id="Pengeluaran">Expense</th>
                            <th data-en="Correction (-)" data-id="Koreksi Keluar">Correction (-)</th>
                            <th data-en="Gross Profit" data-id="Laba Kotor">Gross Profit</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Mobile Menu Drawer (Right Tab) -->
    <div id="mobile-menu-overlay" class="mobile-drawer-overlay lg:hidden" onclick="closeMobileMenu()"></div>
    <div id="mobile-menu-drawer" class="mobile-drawer lg:hidden bg-white flex flex-col justify-between">
        <div class="p-4 flex-1 overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4" data-en="Navigation" data-id="Navigasi">Navigation</h3>
            <div class="space-y-2">
                 @if(auth()->user()->penjualan)
                <a href="{{ route('cashier.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                    <svg class="w-6 h-6 text-emerald-500 group-hover:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-gray-700 font-medium" data-en="Cashier" data-id="Kasir">Cashier</span>
                </a>
                @endif
                
                <a href="{{ route('sales.my-sales') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                    <svg class="w-6 h-6 text-blue-500 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-gray-700 font-medium" data-en="My Sales" data-id="Penjualan Saya">My Sales</span>
                </a>
                
                @if(auth()->user()->laporan)
                <a href="{{ route('sales.all-sales') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                    <svg class="w-6 h-6 text-purple-500 group-hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2zM9 17v-4h6v4m-6 0h6m-6 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium" data-en="All Sales" data-id="Semua Penjualan">All Sales</span>
                </a>
                <a href="{{ route('sales.period') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group bg-purple-50">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium" data-en="Period Report" data-id="Laporan Periode">Period Report</span>
                </a>
                @endif

                <a href="{{ route('profile') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                    <svg class="w-6 h-6 text-green-500 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium" data-en="Account Profile" data-id="Profil Akun">Account Profile</span>
                </a>
            </div>
        </div>
        
        <!-- Bottom: Language Switch and Logout -->
        <div class="p-4 border-t border-gray-200">
             <!-- Language Switcher -->
            <button id="mobile-language-toggle" class="w-full flex items-center justify-between space-x-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all mb-4" title="Change Language">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700" data-en="Switch Language" data-id="Ganti Bahasa">Switch Language</span>
                </div>
                <span id="mobile-current-lang" class="text-sm font-semibold text-blue-600">ID</span>
            </button>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="block w-full">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-white bg-red-500 hover:bg-red-600 font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span data-en="Logout" data-id="Keluar">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Language helper function
        function getText(enText, idText) {
            return currentLanguage === 'id' ? idText : enText;
        }

        // Mobile Menu Drawer Functionality
        window.openMobileMenu = function() {
            const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            if (mobileMenuDrawer && mobileMenuOverlay) {
                mobileMenuDrawer.classList.add('open');
                mobileMenuOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        window.closeMobileMenu = function() {
            const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            if (mobileMenuDrawer && mobileMenuOverlay) {
                mobileMenuDrawer.classList.remove('open');
                mobileMenuOverlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        // Language switcher with persistence
        let currentLanguage = localStorage.getItem('language') || 'id';

        const desktopLangToggle = document.getElementById('language-toggle');
        const mobileLangToggle = document.getElementById('mobile-language-toggle');

        function toggleLanguageHandler() {
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
            localStorage.setItem('language', currentLanguage);
            updateLanguage();
        }

        if (desktopLangToggle) desktopLangToggle.addEventListener('click', toggleLanguageHandler);
        if (mobileLangToggle) mobileLangToggle.addEventListener('click', toggleLanguageHandler);

        function updateLanguage() {
            const langButton = document.getElementById('current-lang');
            const mobileLangButton = document.getElementById('mobile-current-lang');
            const elements = document.querySelectorAll('[data-en], [data-id]');

            if (langButton) langButton.textContent = currentLanguage.toUpperCase();
            if (mobileLangButton) mobileLangButton.textContent = currentLanguage.toUpperCase();

            elements.forEach(element => {
                if (element.hasAttribute(`data-${currentLanguage}`)) {
                    const text = element.getAttribute(`data-${currentLanguage}`);
                    if (element.tagName === 'TH') {
                        element.textContent = text;
                    } else {
                        element.textContent = text;
                    }
                }
            });

            // Redraw table to update column headers
            if ($.fn.DataTable.isDataTable('#reportTable')) {
                $('#reportTable').DataTable().columns.adjust().draw();
            }
        }

        $(document).ready(function() {
            // Attach mobile menu listener
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', window.openMobileMenu);
            }

            // Initialize DatePickers
            const today = new Date();
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            $("#start_date").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d"
            });
            
            $("#end_date").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d"
            });

            // Initialize DataTable
            var table = $('#reportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('api.sales.period-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
                    dataSrc: function(json) {
                        // Update stats
                        $('#total-income').text(json.stats.total_income);
                        $('#total-expense').text(json.stats.total_expense);
                        $('#total-profit').text(json.stats.total_profit);
                        
                        return json.data;
                    }
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']], 
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    processing: "Loading data..."
                },
                columns: [
                    { data: 'start_date', name: 'mulai' },
                    { data: 'end_date', name: 'akhir' },
                    { data: 'income', name: 'pemasukkan' },
                    { data: 'correction_income', name: 'koreksi_pemasukkan' },
                    { data: 'expense', name: 'pengeluaran', className: "text-red-500" },
                    { data: 'correction_expense', name: 'koreksi_pengeluaran', className: "text-red-500" },
                    { 
                        data: 'gross_profit', 
                        name: 'laba_kotor',
                        className: "font-bold text-blue-600"
                    }
                ]
            });

            // Filter button click handler
            $('#filter-btn').on('click', function() {
                table.ajax.reload();
                loadAnalytics();
            });

            updateLanguage();
            
            // Initial load of analytics
            loadAnalytics();
        });

        // Charts specific code
        let trendChartInstance = null;
        let paymentChartInstance = null;

        function loadAnalytics() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            $.ajax({
                url: "{{ route('api.sales.period-analytics') }}",
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    renderTrendChart(response.trend);
                    renderPaymentChart(response.payment_methods);
                    renderTopProducts(response.top_products);
                    renderCashierStats(response.cashier_stats);
                },
                error: function(err) {
                    console.error('Failed to load analytics', err);
                }
            });
        }

        function renderTrendChart(data) {
            const ctx = document.getElementById('trendChart').getContext('2d');
            
            if (trendChartInstance) {
                trendChartInstance.destroy();
            }

            const labels = data.map(item => item.date);
            const income = data.map(item => item.income);
            const expense = data.map(item => item.expense);

            trendChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: getText('Income', 'Pemasukan'),
                            data: income,
                            borderColor: '#10b981', // emerald-500
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: getText('Expense', 'Pengeluaran'),
                            data: expense,
                            borderColor: '#ef4444', // red-500
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            cardinality: 'Rp '
                        }
                    }
                }
            });
        }

        function renderPaymentChart(data) {
            const ctx = document.getElementById('paymentChart').getContext('2d');
            
            if (paymentChartInstance) {
                paymentChartInstance.destroy();
            }

            // Generate nice colors
            const backgroundColors = [
                '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#6366f1'
            ];

            paymentChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.method),
                    datasets: [{
                        data: data.map(item => item.count), // Using count for distribution
                        backgroundColor: backgroundColors.slice(0, data.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.chart._metasets[context.datasetIndex].total;
                                    const percentage = Math.round((value / total) * 100) + '%';
                                    return label + ': ' + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderTopProducts(products) {
            const tbody = document.getElementById('top-products-list');
            tbody.innerHTML = '';
            
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">No data available</td></tr>';
                return;
            }

            products.forEach(product => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">${product.nama_produk}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-500">${product.total_qty}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(product.total_revenue)}</td>
                `;
                tbody.appendChild(tr);
            });
        }
        function renderCashierStats(data) {
            const tbody = $('#cashier-stats-list');
            tbody.empty();

            if (!data || data.length === 0) {
                tbody.append('<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No data available</td></tr>');
                return;
            }

            data.forEach(item => {
                const revenue = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.revenue);
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.dibuat_oleh}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${item.transaction_count}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        }
    </script>
</body>
</html>
