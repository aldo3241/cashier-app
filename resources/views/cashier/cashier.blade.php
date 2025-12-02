    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inspizo Spiritosanto - Cashier</title>
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    <style>
        .search-suggestions {
            max-height: 300px;
            overflow-y: auto;
        }
        .cart-item {
            transition: all 0.2s ease;
        }
        .cart-item:hover {
            background-color: #f8fafc;
        }
        .quantity-btn {
            transition: all 0.2s ease;
        }
        .quantity-btn:hover {
            transform: scale(1.05);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Bottom header styles - always visible */
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
            padding-bottom: 80px; /* Space for desktop bottom header */
            padding-top: 60px; /* Space for mobile top header */
        }

        /* Adjust body padding for large screens to remove mobile top padding */
        @media (min-width: 1024px) {
            body {
                padding-top: 0;
            }
        }


        /* Adjust main content for bottom header */
        main {
            margin-bottom: 0;
        }

        /* Custom Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 0;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.show .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            padding: 24px 24px 16px 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-body {
            padding: 16px 24px 24px 24px;
        }

        .modal-message {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            outline: none;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .modal-btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .modal-btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .modal-btn-secondary:hover {
            background: #e5e7eb;
        }

        .modal-icon {
            width: 24px;
            height: 24px;
            color: #f59e0b;
        }

        /* Hide Scrollbars - Keep Functionality */
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
            background: transparent;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: transparent;
        }

        ::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* Firefox - Hide scrollbar */
        * {
            scrollbar-width: none;
        }

        /* Hide scrollbars for specific elements */
        .search-suggestions::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        #cart-items::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        /* Alternative method for older browsers */
        .search-suggestions {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #cart-items {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Header Toggle Styles */
        .header-bottom {
            /* Retain positioning context for absolute children without overriding fixed property */
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

    <!-- Desktop Bottom Header - Collapsible -->
    <header id="main-header" class="hidden lg:block header-bottom bg-white shadow-lg border-t border-gray-200 transition-all duration-300 ease-in-out">
        <div class="px-6 py-4">
            <div class="flex flex-wrap lg:flex-nowrap justify-start lg:justify-between items-center">
                <!-- Left: User Info -->
                <div class="flex items-center space-x-3 w-full lg:w-auto py-2">
                    <!-- Profile Dropdown -->
                    <div class="hidden lg:block relative">
                        <button id="profile-dropdown-btn" class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center hover:from-green-500 hover:to-blue-600 transition-all duration-200 cursor-pointer group" title="Profile Menu">
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
                    <div class="hidden lg:block text-left">
                        <div class="text-sm text-gray-500" data-en="Logged in as" data-id="Masuk sebagai">Masuk sebagai</div>
                        <div class="font-medium text-gray-800">{{ auth()->user()->nama ?? auth()->user()->username ?? 'User' }}</div>
                    </div>
                </div>

                <!-- Center: PT Inspizo Multi Inspirasi Logo & Text -->
                <div class="flex items-center space-x-3 w-full justify-center lg:w-auto lg:justify-start py-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-gray-800">   Spiritosanto</h1>
                        <p class="text-sm text-gray-500">Inspizo Cashier System</p>
                    </div>
                </div>

                <!-- Right: Current Time & Language -->
                <div class="hidden lg:flex items-center space-x-4 w-full justify-start lg:justify-end lg:w-auto py-2">
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
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-120px)]">

            <!-- Left Panel - Product Search & Cart -->
            <div class="lg:col-span-3 flex flex-col space-y-6">

                <!-- Product Search -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="product-search"
                                placeholder="Search products by name, barcode, or category..."
                                data-en-placeholder="Search products by name, barcode, or category..."
                                data-id-placeholder="Cari produk"
                                class="w-full text-xl font-medium text-gray-800 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-blue-500 pl-12 pr-4 py-4 bg-gray-50 rounded-lg"
                                autocomplete="off"
                            >
                        </div>

                        <!-- Search Suggestions -->
                        <div id="search-suggestions" class="search-suggestions hidden mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                            <!-- Suggestions will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Shopping Cart -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 flex-1 flex flex-col">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-800" data-en="Shopping Cart" data-id="Keranjang Belanja">Shopping Cart</h2>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500" data-en="Items:" data-id="Item:">Items:</span>
                                <span id="cart-count" class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-semibold">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Start Responsive Table Wrapper for Horizontal Scroll on Mobile -->
                    <div class="flex-1 min-h-0 overflow-y-auto overflow-x-auto">

                        <!-- Cart Header -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600 min-w-[700px] lg:min-w-full sticky top-0 z-10">
                            <div class="col-span-5" data-en="Product" data-id="Produk">Product</div>
                            <div class="col-span-2 text-right" data-en="Price" data-id="Harga">Price</div>
                            <div class="col-span-2 text-center" data-en="Qty" data-id="Jml">Qty</div>
                            <div class="col-span-2 text-right" data-en="Total" data-id="Total">Total</div>
                            <div class="col-span-1 text-center" data-en="Action" data-id="Aksi">Action</div>
                        </div>

                        <!-- Cart Items -->
                        <div id="cart-items" class="">
                            <div class="p-8 text-center text-gray-500 min-w-[700px] lg:min-w-full">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                <p class="text-lg font-medium" data-en="Your cart is empty" data-id="Keranjang Anda kosong">Your cart is empty</p>
                                <p class="text-sm" data-en="Start by searching for products above" data-id="Mulai dengan mencari produk di atas">Start by searching for products above</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Order Summary & Actions -->
            <div class="lg:col-span-1 flex flex-col space-y-6">

                <!-- Invoice Info & Total -->
                <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-xl shadow-lg text-white">
                    <div class="p-6">

                        <div class="space-y-4">
                            <!-- Invoice Number -->
                            <div>
                                <div class="text-xs opacity-80" data-en="Invoice No:" data-id="No Faktur:">No Faktur:</div>
                                <div class="text-sm font-bold" id="invoice-number">PJ250101000000</div>
                            </div>

                            <!-- Timestamp -->
                            <div>
                                <div class="text-xs opacity-80" data-en="Date & Time:" data-id="Tanggal & Waktu:">Tanggal & Waktu:</div>
                                <div class="text-sm font-medium" id="invoice-timestamp">Loading...</div>
                            </div>

                            <!-- Total Price -->
                            <div class="border-t border-white/20 pt-4 text-center">
                                <div class="text-4xl font-bold" id="total">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-4 border-2 border-orange-200 shadow-sm">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="text-xs font-medium text-orange-700 uppercase tracking-wide" data-en="Customer Information" data-id="Informasi Pelanggan">Customer Information</div>
                        </div>

                        <div class="space-y-2">
                            <div>
                                <div class="text-xs text-orange-600 font-medium" data-en="Customer No" data-id="No Pelanggan">No Pelanggan</div>
                                <div id="customer-code-display" class="text-sm font-bold text-gray-800">Loading...</div>
                            </div>

                            <div>
                                <div class="text-xs text-orange-600 font-medium" data-en="Customer Name" data-id="Nama Pelanggan">Nama Pelanggan</div>
                                <div id="customer-name-display" class="text-sm font-bold text-gray-800">Loading...</div>
                            </div>
                        </div>

                        <button id="change-customer-btn" class="w-full px-3 py-2 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white text-xs font-bold rounded-lg transition-all transform hover:scale-105 shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            <span data-en="Change Customer" data-id="Ganti Pelanggan">Change Customer</span>
                            </button>
                    </div>
                </div>





                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6">
                        <div class="space-y-3">
                        <button onclick="window.location.href='{{ route('sales.my-sales') }}'" class="w-full bg-purple-100 hover:bg-purple-200 text-purple-800 p-4 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span data-en="Back to My Sales" data-id="Kembali ke Penjualan Saya">Kembali ke Penjualan Saya</span>
                        </button>
                        <button id="cancel-order-btn" class="w-full bg-red-100 hover:bg-red-200 text-red-800 p-4 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span data-en="Cancel Order" data-id="Batalkan Pesanan">Cancel Order</span>
                        </button>
                            <button id="quick-print-btn" class="w-full bg-green-100 hover:bg-green-200 text-green-800 p-4 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                <span data-en="Print Receipt" data-id="Cetak Struk">Print Receipt</span>
                            </button>

                            <button id="checkout-btn" class="w-full bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white text-xl font-bold py-4 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span data-en="Process Payment" data-id="Proses Pelunasan">Process Payment</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Mobile Menu Drawer (Right Tab) -->
   <div id="mobile-menu-overlay" class="mobile-drawer-overlay lg:hidden" onclick="closeMobileMenu()"></div>
   <div id="mobile-menu-drawer" class="mobile-drawer lg:hidden bg-white flex flex-col justify-between">
       <div class="p-4 flex-1 overflow-y-auto">
           <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4" data-en="Navigation" data-id="Navigasi">Navigation</h3>
           <div class="space-y-2">
               <!-- Top Navigation Links -->
               <a href="{{ route('sales.my-sales') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                   <svg class="w-6 h-6 text-blue-500 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                   </svg>
                   <span class="text-gray-700 font-medium" data-en="My Sales" data-id="Penjualan Saya">My Sales</span>
               </a>
               <a href="{{ route('sales.all-sales') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                   <svg class="w-6 h-6 text-purple-500 group-hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2zM9 17v-4h6v4m-6 0h6m-6 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
                   </svg>
                   <span class="text-gray-700 font-medium" data-en="All Sales" data-id="Semua Penjualan">All Sales</span>
               </a>
               <a href="{{ route('profile') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                   <svg class="w-6 h-6 text-green-500 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                   </svg>
                   <span class="text-gray-700 font-medium" data-en="Account Profile" data-id="Profil Akun">Account Profile</span>
               </a>

               <!-- Settings Link (Placeholder if needed) -->
               <button class="w-full text-left flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors group">
                   <svg class="w-6 h-6 text-yellow-500 group-hover:text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path>
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                   </svg>
                   <span class="text-gray-700 font-medium" data-en="Settings (Placeholder)" data-id="Pengaturan (Placeholder)">Settings (Placeholder)</span>
               </button>
           </div>
       </div>

       <!-- Bottom: Language Switch and Logout -->
       <div class="p-4 border-t border-gray-200">
            <!-- Language Switcher (Copied from desktop header, modified to be full width) -->
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

   <!-- Custom Modal -->
   <div id="custom-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <svg class="modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span id="modal-title-text">Notice</span>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-message" id="modal-message-text">
                    <!-- Message will be inserted here -->
                </div>
                <div class="modal-actions">
                    <button class="modal-btn modal-btn-secondary" id="modal-cancel-btn">Cancel</button>
                    <button class="modal-btn modal-btn-primary" id="modal-confirm-btn">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Search Modal -->
    <div id="customer-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[80vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900" data-en="Select Customer" data-id="Pilih Pelanggan">Select Customer</h3>
                <button onclick="closeCustomerModal('button')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <!-- Search Input -->
                <div class="mb-4">
                    <input
                        type="text"
                        id="customer-search-input"
                        placeholder="Search customer by name, phone, or organization..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Default Customer Option -->
                <div class="mb-4">
                    <button onclick="selectDefaultCustomer()" class="w-full text-left p-4 border-2 border-blue-300 bg-blue-50 rounded-lg hover:bg-blue-100 transition-all">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                #
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="font-semibold text-gray-800">#PLG1</div>
                                <div class="text-sm text-gray-600" data-en="Walk-in Customer (Default)" data-id="Pelanggan Umum (Default)">Walk-in Customer (Default)</div>
                            </div>
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Search Results -->
                <div id="customer-search-results" class="space-y-2 overflow-y-auto max-h-96">
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p data-en="Search for customers" data-id="Cari pelanggan">Search for customers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // CSRF Token for API calls
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Featured products from backend (will be populated)
        let featuredProducts = @json($featuredProducts ?? []);

        let cart = [];
        let currentInvoiceNumber = 'PJ250101000000'; // Default invoice number
        let currentCustomer = {
            id: 1, // kd_pelanggan (integer)
            name: 'Pelanggan', // Will be updated from database
            display_name: 'Pelanggan',
            is_default: true
        };
        let currentCartId = null;
        let searchTimeout;
        let allTransactions = []; // Array untuk menyimpan semua transaksi
        let currentTransactionId = 1; // ID transaksi yang sedang aktif
        let invoiceNumber = 'PJ250101000000'; // Nomor faktur saat ini

        // Update current time
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString();

            // Update invoice timestamp
            const timestampElement = document.getElementById('invoice-timestamp');
            if (timestampElement) {
                timestampElement.textContent = now.toLocaleString('id-ID', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Generate new invoice number
        function generateInvoiceNumber() {
            const now = new Date();
            const year = String(now.getFullYear()).slice(-2); // 2 digit tahun
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            // Generate 4 random digits (1000 to 9999)
            const randomDigits = Math.floor(1000 + Math.random() * 9000);

            invoiceNumber = `PJ0000000000`;
            document.getElementById('invoice-number').textContent = invoiceNumber;
        }

        // Initialize invoice number
        generateInvoiceNumber();

        // Product search functionality
        document.getElementById('product-search').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            if (query.length < 2) {
                document.getElementById('search-suggestions').classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                searchProducts(query);
            }, 300);
        });

        // Barcode scanning - Enter key handler
        document.getElementById('product-search').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = e.target.value.trim();

                if (barcode.length > 0) {
                    handleBarcodeInput(barcode);
                }
            }
        });

        // Search products via API
        async function searchProducts(query) {
            try {
                const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showSuggestions(result.data);
                } else {
                    console.error('Search error:', result.message);
                    showSuggestions([]);
                }
            } catch (error) {
                console.error('Search request failed:', error);
                showSuggestions([]);
            }
        }

        function showSuggestions(products) {
            const suggestionsContainer = document.getElementById('search-suggestions');

            if (products.length === 0) {
                suggestionsContainer.innerHTML = `<div class="p-4 text-gray-500">${getText('No products found', 'Produk tidak ditemukan')}</div>`;
            } else {
                suggestionsContainer.innerHTML = products.map(product => `
                    <div class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="addToCart('${product.id}')">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-medium text-gray-800">${product.name}</div>
                                <div class="text-sm text-gray-500">${product.category}</div>
                                <div class="text-xs text-gray-400">
                                    ${product.barcode_int ? 'INT: ' + product.barcode_int : ''}
                                    ${product.barcode_int && product.barcode_ext ? ' • ' : ''}
                                    ${product.barcode_ext ? 'EXT: ' + product.barcode_ext : ''}
                                </div>
                                <div class="text-xs text-green-600">${getText('Stock', 'Stok')}: ${product.stock} ${product.unit || 'pcs'}</div>
                            </div>
                            <div class="text-lg font-bold text-blue-600">Rp ${formatPrice(product.price)}</div>
                        </div>
                    </div>
                `).join('');
            }

            suggestionsContainer.classList.remove('hidden');
        }

        // Handle barcode input when Enter is pressed
        async function handleBarcodeInput(barcode) {
            const searchInput = document.getElementById('product-search');
            const originalValue = searchInput.value;

            try {
                // Show loading state
                searchInput.disabled = true;
                searchInput.style.backgroundColor = '#fef3c7';
                searchInput.style.borderColor = '#f59e0b';

                // Hide suggestions
                document.getElementById('search-suggestions').classList.add('hidden');

                // Search for product by barcode
                const response = await fetch(`/api/products/barcode?barcode=${encodeURIComponent(barcode)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success && result.data) {
                    const product = result.data;

                    // Check stock
                    if (product.stock <= 0) {
                        showNotification(getText('Out of Stock', 'Stok Habis'), 'error');
                        searchInput.style.backgroundColor = '#fef2f2';
                        searchInput.style.borderColor = '#ef4444';
                        return;
                    }

                    // Use real-time cart API to add/update the item
                    await addToCartRealTime(product, 1);

                    // Success!
                    searchInput.value = '';

                    // Show success feedback
                    searchInput.style.backgroundColor = '#dcfce7';
                    searchInput.style.borderColor = '#10b981';
                    showNotification(getText(`Added: ${product.name}`, `Ditambahkan: ${product.name}`), 'success');

                } else {
                    // Product not found
                    searchInput.style.backgroundColor = '#fef2f2';
                    searchInput.style.borderColor = '#ef4444';
                    showNotification(getText('Product not found', 'Produk tidak ditemukan'), 'error');
                    searchInput.select();
                }

            } catch (error) {
                console.error('Barcode scan error:', error);
                searchInput.style.backgroundColor = '#fef2f2';
                searchInput.style.borderColor = '#ef4444';
                showNotification(getText('Scan failed', 'Scan gagal'), 'error');
            } finally {
                // Reset and refocus
                searchInput.disabled = false;
                setTimeout(() => {
                    searchInput.style.backgroundColor = '';
                    searchInput.style.borderColor = '';
                    searchInput.focus();
                }, 1000);
            }
        }

        // Show notification toast
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

            notification.innerHTML = `
                <div class="flex items-center space-x-2 ${bgColor} text-white px-4 py-3 rounded-lg shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success'
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;

            notification.style.position = 'fixed';
            notification.style.top = '100px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            notification.style.transition = 'all 0.3s ease';

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 10);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 2500);
        }

        // Format price to Indonesian Rupiah
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#product-search') && !e.target.closest('#search-suggestions')) {
                document.getElementById('search-suggestions').classList.add('hidden');
            }
        });

        // Add to cart function
        async function addToCart(productId) {
            try {
                // First, get product details if not in featured products
                let product = featuredProducts.find(p => p.id === productId);

                if (!product) {
                    // Search for the product by ID/barcode
                    const response = await fetch(`/api/products/barcode?barcode=${encodeURIComponent(productId)}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        product = result.data;
                    } else {
                        showModal(
                            getText('Product Not Found', 'Produk Tidak Ditemukan'),
                            getText('The selected product could not be found.', 'Produk yang dipilih tidak dapat ditemukan.')
                        );
                        return;
                    }
                }

                // Use real-time cart API
                await addToCartRealTime(product, 1);

                document.getElementById('product-search').value = '';
                document.getElementById('search-suggestions').classList.add('hidden');

            } catch (error) {
                console.error('Error adding to cart:', error);
                showModal(
                    getText('Error', 'Error'),
                    getText('Failed to add product to cart.', 'Gagal menambahkan produk ke keranjang.')
                );
            }
        }

        // Update cart display
        function updateCartDisplay() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartCount = document.getElementById('cart-count');
            const total = document.getElementById('total');
            const checkoutBtn = document.getElementById('checkout-btn');
            const invoiceNumber = document.getElementById('invoice-number');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                        <p class="text-lg font-medium">Your cart is empty</p>
                        <p class="text-sm">Start by searching for products above</p>
                    </div>
                `;
                cartCount.textContent = '0';
                total.textContent = 'Rp 0';
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            // Calculate total from cart items
            const totalAmount = cart.reduce((sum, item) => {
                // Calculate subtotal if not available
                const subtotal = item.subtotal || ((item.harga_jual * item.qty) - (item.diskon || 0));
                return sum + subtotal;
            }, 0);

            // Update display
            cartCount.textContent = cart.length;
            total.textContent = `Rp ${formatPrice(totalAmount)}`;
            invoiceNumber.textContent = currentInvoiceNumber;
            checkoutBtn.disabled = false;
            checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            // Render cart items
            cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item grid grid-cols-12 gap-4 p-4 border-b border-gray-100 min-w-[700px] lg:min-w-full">
                    <div class="col-span-5">
                        <div class="font-medium text-gray-800">${item.nama_produk}</div>
                        <div class="text-sm text-gray-500">${item.produk_jenis || 'General'}</div>
                    </div>
                    <div class="col-span-2 text-right text-gray-600">Rp ${formatPrice(item.harga_jual)}</div>
                    <div class="col-span-2 flex items-center justify-center space-x-2">
                        <button class="quantity-btn w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center" onclick="updateQuantityRealTime('${item.kd_produk}', ${item.qty - 1})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="font-semibold w-8 text-center">${item.qty}</span>
                        <button class="quantity-btn w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center" onclick="updateQuantityRealTime('${item.kd_produk}', ${item.qty + 1})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="col-span-2 text-right font-semibold text-gray-800">Rp ${formatPrice(item.subtotal)}</div>
                    <div class="col-span-1 flex items-center justify-center">
                        <button class="text-red-500 hover:text-red-700 p-1" onclick="removeFromCartRealTime('${item.kd_produk}')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Update quantity (legacy function - kept for compatibility)
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (!item) return;

            item.quantity += change;
            if (item.quantity <= 0) {
                removeFromCartRealTime(productId);
            } else {
                updateCartDisplay();
            }
        }

        // Real-time quantity update
        async function updateQuantityRealTime(productId, newQty) {
            if (newQty <= 0) {
                await removeFromCartRealTime(productId);
            } else {
                await updateCartItem(productId, newQty);
            }
        }

        // Real-time remove from cart
        async function removeFromCartRealTime(productId) {
            try {
                const response = await fetch('/api/cart/remove', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        customer_id: currentCustomer.id,
                        cart_id: currentCartId
                    })
                });

                console.log('Remove from cart response status:', response.status);

                if (response.ok) {
                    const result = await response.json();
                    console.log('Remove from cart response:', result);

                    if (result.success) {
                        cart = result.data.items || [];
                        updateCartDisplay();
                        showSuccessMessage('Item removed from cart');
                    } else {
                        console.error('Remove from cart failed:', result.message);
                        showErrorMessage(result.message);
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Remove from cart API failed:', response.status, errorText);
                    showErrorMessage('Failed to remove item from cart');
                }
            } catch (error) {
                console.error('Error removing from cart:', error);
                showErrorMessage('Error removing item from cart');
            }
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        }

        // Clear cart with confirmation
        document.getElementById('cancel-order-btn')?.addEventListener('click', function() {
            showCancelTransactionModal();
        });

        // Checkout functionality
        document.getElementById('checkout-btn').addEventListener('click', async function() {
            if (cart.length === 0) return;

            // Calculate total
            const totalAmount = cart.reduce((sum, item) => {
                // Calculate subtotal if not available
                const subtotal = item.subtotal || ((item.harga_jual * item.qty) - (item.diskon || 0));
                return sum + subtotal;
            }, 0);

            // Debug logging
            console.log('Cart items for checkout:', cart);
            console.log('Calculated total amount:', totalAmount);

            // Show checkout modal with payment options
            showCheckoutModal(totalAmount);
        });

        // Customer selection functionality
        function openCustomerModal() {
            document.getElementById('customer-modal').classList.remove('hidden');
            document.getElementById('customer-search-input').focus();
            // Load all customers when modal opens
            searchCustomers('');
        }

        function closeCustomerModal(source = 'overlay') {
            console.log(`[DEBUG] closeCustomerModal called from: ${source}`);
            document.getElementById('customer-modal').classList.add('hidden');
            document.getElementById('customer-search-input').value = '';
            document.getElementById('customer-search-results').innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p data-en="Search for customers" data-id="Cari pelanggan">Search for customers</p>
                </div>
            `;
        }

        async function selectDefaultCustomer() {
            try {
                const response = await fetch('/api/customers/default');
                if (response.ok) {
                    const result = await response.json();
                    if (result.success && result.data) {
                        const customer = result.data;
                        currentCustomer = {
                            id: customer.id || customer.kd_pelanggan,
                            name: customer.name || customer.nama_lengkap,
                            display_name: customer.display_name || customer.name || customer.nama_lengkap,
                            organization: customer.organization || customer.nama_lembaga,
                            phone: customer.phone || customer.telp,
                            is_default: true
                        };
                    } else {
                        throw new Error('Invalid API response');
                    }
                } else {
                    throw new Error('API request failed');
                }
            } catch (error) {
                console.error('Error loading default customer:', error);
                // Fallback to hardcoded default
                currentCustomer = {
                    id: 1,
                    name: 'Pelanggan',
                    display_name: 'Pelanggan',
                    is_default: true
                };
            }
            updateCustomerDisplay();
            closeCustomerModal();
        }

        function selectCustomer(customer) {
            currentCustomer = {
                id: customer.id || customer.kd_pelanggan, // API returns both formats
                name: customer.name || customer.nama_lengkap, // API returns both formats
                display_name: customer.display_name || customer.name || customer.nama_lengkap,
                organization: customer.organization || customer.nama_lembaga, // API returns both formats
                phone: customer.phone || customer.telp, // API returns both formats
                is_default: false
            };
            updateCustomerDisplay();
            closeCustomerModal();
        }

        function updateCustomerDisplay() {
            // Update the new customer information card
            const customerCodeEl = document.getElementById('customer-code-display');
            const customerNameDisplayEl = document.getElementById('customer-name-display');

            if (currentCustomer.is_default) {
                // Default customer (ID = 1)
                customerCodeEl.textContent = '#PLG1'; // Display as #PLG1 for user
                customerNameDisplayEl.textContent = currentCustomer.name || 'Pelanggan Umum'; // nama_lengkap
                customerCodeEl.className = 'text-sm font-bold text-orange-600';
                customerNameDisplayEl.className = 'text-sm font-bold text-orange-600';
            } else {
                // Selected customer from database
                customerCodeEl.textContent = currentCustomer.id; // kd_pelanggan

                if (currentCustomer.organization) {
                    // Organization customer - show company name (nama_lembaga) + contact person (nama_lengkap)
                    customerNameDisplayEl.innerHTML = `
                        <div class="text-orange-700">${currentCustomer.organization}</div>
                        <div class="text-xs text-gray-600">${currentCustomer.name}</div>
                    `;
                } else {
                    // Personal customer - show full name (nama_lengkap)
                    customerNameDisplayEl.textContent = currentCustomer.name;
                }

                customerCodeEl.className = 'text-sm font-bold text-orange-800';
                customerNameDisplayEl.className = 'text-sm font-bold text-orange-800';
            }
        }

        async function searchCustomers(query) {
            if (!query || query.length < 2) {
                // Show all customers when no search query
                try {
                    const response = await fetch(`/api/customers/search?q=&limit=50`);
                    const data = await response.json();

                    if (data.success && data.data.length > 0) {
                        const resultsHtml = data.data.map(customer => `
                            <button onclick='selectCustomer(${JSON.stringify(customer)})' class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                        ${customer.display_name ? customer.display_name.charAt(0).toUpperCase() : '?'}
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="font-semibold text-gray-800">${customer.name}</div>
                                        ${customer.organization ? `<div class="text-sm text-purple-600">${customer.organization}</div>` : ''}
                                        ${customer.phone ? `<div class="text-xs text-gray-500">${customer.formatted_phone}</div>` : ''}
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </button>
                        `).join('');
                        document.getElementById('customer-search-results').innerHTML = resultsHtml;
                    } else {
                        document.getElementById('customer-search-results').innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <p data-en="No customers found" data-id="Tidak ada pelanggan ditemukan">No customers found</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error loading customers:', error);
                    document.getElementById('customer-search-results').innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p data-en="Error loading customers" data-id="Error memuat pelanggan">Error loading customers</p>
                        </div>
                    `;
                }
                return;
            }

            try {
                const response = await fetch(`/api/customers/search?q=${encodeURIComponent(query)}&limit=20`);
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    const resultsHtml = data.data.map(customer => `
                        <button onclick='selectCustomer(${JSON.stringify(customer)})' class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    ${customer.display_name ? customer.display_name.charAt(0).toUpperCase() : '?'}
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="font-semibold text-gray-800">${customer.name}</div>
                                    ${customer.organization ? `<div class="text-sm text-purple-600">${customer.organization}</div>` : ''}
                                    ${customer.phone ? `<div class="text-xs text-gray-500">${customer.formatted_phone}</div>` : ''}
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </button>
                    `).join('');

                    document.getElementById('customer-search-results').innerHTML = resultsHtml;
                } else {
                    document.getElementById('customer-search-results').innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p data-en="No customers found" data-id="Pelanggan tidak ditemukan">No customers found</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error searching customers:', error);
                document.getElementById('customer-search-results').innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <p data-en="Error searching customers" data-id="Error mencari pelanggan">Error searching customers</p>
                    </div>
                `;
            }
        }

        // Customer search input event listener
        let customerSearchTimeout;
        document.getElementById('customer-search-input').addEventListener('input', function(e) {
            clearTimeout(customerSearchTimeout);
            customerSearchTimeout = setTimeout(() => {
                searchCustomers(e.target.value);
            }, 300);
        });

        // Change customer button event listener
        document.getElementById('change-customer-btn').addEventListener('click', openCustomerModal);

        // Language switcher functionality with persistence
        let currentLanguage = localStorage.getItem('language') || 'id'; // Default to Indonesian

        // Language Toggle functionality
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

            // Update language buttons
            if (langButton) langButton.textContent = currentLanguage.toUpperCase();
            if (mobileLangButton) mobileLangButton.textContent = currentLanguage.toUpperCase();

            // Update all elements with data attributes
            elements.forEach(element => {
                if (element.hasAttribute(`data-${currentLanguage}`)) {
                    const text = element.getAttribute(`data-${currentLanguage}`);
                    element.textContent = text;
                }
            });

            // Update search placeholder
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
            if (currentLanguage === 'id') {
                searchInput.placeholder = searchInput.getAttribute('data-id-placeholder');
            } else {
                searchInput.placeholder = searchInput.getAttribute('data-en-placeholder');
                }
            }

            // Update textarea placeholders
            const textareas = document.querySelectorAll('textarea[data-placeholder-en], textarea[data-placeholder-id]');
            textareas.forEach(textarea => {
                if (currentLanguage === 'id') {
                    textarea.placeholder = textarea.getAttribute('data-placeholder-id');
                } else {
                    textarea.placeholder = textarea.getAttribute('data-placeholder-en');
                }
            });

            // Update cart empty messages
            updateCartEmptyMessages();
        }

        function updateCartEmptyMessages() {
            const cartItems = document.getElementById('cart-items');
            if (cartItems && cartItems.querySelector('.p-8')) {
                const emptyCart = cartItems.querySelector('.p-8');
                const title = emptyCart.querySelector('.text-lg');
                const subtitle = emptyCart.querySelector('.text-sm');

                if (currentLanguage === 'id') {
                    title.textContent = 'Keranjang Anda kosong';
                    subtitle.textContent = 'Mulai dengan mencari produk di atas';
                } else {
                    title.textContent = 'Your cart is empty';
                    subtitle.textContent = 'Start by searching for products above';
                }
            }
        }

        // Initialize language on page load
        updateLanguage();

        // Bottom header is always visible - no auto-hide functionality needed

        // Language helper function
        function getText(enText, idText) {
            return currentLanguage === 'id' ? idText : enText;
        }

        // Show cancel transaction confirmation modal
        function showCancelTransactionModal() {
            const modal = document.getElementById('custom-modal');
            const titleElement = document.getElementById('modal-title-text');
            const messageElement = document.getElementById('modal-message-text');
            const confirmBtn = document.getElementById('modal-confirm-btn');
            const cancelBtn = document.getElementById('modal-cancel-btn');

            titleElement.textContent = getText('Cancel Transaction', 'Batalkan Transaksi');
            messageElement.textContent = getText(
                'Are you sure you want to cancel this transaction? All items will be permanently deleted and you will be redirected to My Sales page.',
                'Apakah Anda yakin ingin membatalkan transaksi ini? Semua item akan dihapus secara permanen dan Anda akan dialihkan ke halaman Laporan Penjualan Saya.'
            );

            // Update button text for cancel transaction
            confirmBtn.textContent = getText('Yes, Cancel Transaction', 'Ya, Batalkan Transaksi');
            confirmBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700';
            cancelBtn.textContent = getText('No, Keep Transaction', 'Tidak, Pertahankan Transaksi');
            cancelBtn.className = 'px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50';

            // Show modal with animation
            modal.classList.add('show');

            // Event listeners
            const handleConfirm = () => {
                modal.classList.remove('show');
                clearCart(); // Clear the cart using real-time system
            };

            const handleCancel = () => {
                modal.classList.remove('show');
            };

            confirmBtn.onclick = handleConfirm;
            cancelBtn.onclick = handleCancel;

            // Close on escape key
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    handleCancel();
                    document.removeEventListener('keydown', handleEscape);
                }
            };
            document.addEventListener('keydown', handleEscape);
        }

        // Custom Modal Functions
        function showModal(title, message, onConfirm = null, onCancel = null) {
            const modal = document.getElementById('custom-modal');
            const titleElement = document.getElementById('modal-title-text');
            const messageElement = document.getElementById('modal-message-text');
            const confirmBtn = document.getElementById('modal-confirm-btn');
            const cancelBtn = document.getElementById('modal-cancel-btn');

            titleElement.textContent = title;
            messageElement.textContent = message;

            // Update button text based on language
            confirmBtn.textContent = getText('OK', 'OK');
            cancelBtn.textContent = getText('Cancel', 'Batal');

            // Show modal with animation
            modal.classList.add('show');

            // Event listeners
            const handleConfirm = () => {
                hideModal();
                if (onConfirm) onConfirm();
            };

            const handleCancel = () => {
                hideModal();
                if (onCancel) onCancel();
            };

            confirmBtn.onclick = handleConfirm;
            cancelBtn.onclick = handleCancel;

            // Close on overlay click
            modal.onclick = (e) => {
                if (e.target === modal) {
                    handleCancel();
                }
            };

            // Close on Escape key
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    handleCancel();
                    document.removeEventListener('keydown', handleEscape);
                }
            };
            document.addEventListener('keydown', handleEscape);
        }

        function hideModal() {
            console.log('[DEBUG] hideModal called');
            const modal = document.getElementById('custom-modal');
            modal.classList.remove('show');
        }

        // Switch Transaction functionality
        function switchTransaction() {
            // Simpan transaksi saat ini jika ada item di cart
            saveCurrentTransaction();

            // Tampilkan modal dengan daftar transaksi
            showTransactionListModal();
        }

        function saveCurrentTransaction() {
            if (cart.length > 0) {
                // Cari apakah transaksi dengan ID ini sudah ada
                const existingIndex = allTransactions.findIndex(t => t.id === currentTransactionId);

                const transactionData = {
                    id: currentTransactionId,
                    customerInfo: `Customer ${currentTransactionId}`,
                    cart: [...cart],
                    total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                    timestamp: new Date(),
                    items: cart.length
                };

                if (existingIndex >= 0) {
                    // Update transaksi yang sudah ada
                    allTransactions[existingIndex] = transactionData;
                } else {
                    // Tambah transaksi baru
                    allTransactions.push(transactionData);
                }
            }
        }

        function showTransactionListModal() {
            const modal = document.getElementById('custom-modal');
            const titleElement = document.getElementById('modal-title-text');
            const messageElement = document.getElementById('modal-message-text');
            const confirmBtn = document.getElementById('modal-confirm-btn');
            const cancelBtn = document.getElementById('modal-cancel-btn');

            titleElement.textContent = getText('Back to My Sales', 'Kembali ke My Sales');

            // Buat daftar transaksi
            let transactionList = '';

            // Tambahkan opsi untuk transaksi baru
            transactionList += `
                <div class="transaction-item p-3 border border-gray-200 rounded-lg mb-2 cursor-pointer hover:bg-blue-50 hover:border-blue-300"
                     onclick="switchToTransaction('new')">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-green-600">${getText('+ New Transaction', '+ Transaksi Baru')}</div>
                            <div class="text-sm text-gray-500">${getText('Start fresh transaction', 'Mulai transaksi baru')}</div>
                        </div>
                        <div class="text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            `;

            // Tambahkan transaksi yang sudah ada
            allTransactions.forEach(transaction => {
                const isActive = transaction.id === currentTransactionId;
                const activeClass = isActive ? 'bg-blue-100 border-blue-400' : 'hover:bg-gray-50';

                transactionList += `
                    <div class="transaction-item p-3 border border-gray-200 rounded-lg mb-2 cursor-pointer ${activeClass}"
                         onclick="switchToTransaction(${transaction.id})">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-semibold">${transaction.customerInfo} ${isActive ? '(Active)' : ''}</div>
                                <div class="text-sm text-gray-500">
                                    ${transaction.items} items • Rp${transaction.total.toFixed(2)} •
                                    ${transaction.timestamp.toLocaleTimeString()}
                                </div>
                            </div>
                            <div class="text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                `;
            });

            if (allTransactions.length === 0) {
                transactionList += `
                    <div class="text-center text-gray-500 py-4">
                        ${getText('No other transactions available', 'Tidak ada transaksi lain tersedia')}
                    </div>
                `;
            }

            messageElement.innerHTML = `
                <div class="max-h-60 overflow-y-auto">
                    ${transactionList}
                </div>
            `;

            // Sembunyikan tombol confirm/cancel karena kita pakai onclick
            confirmBtn.style.display = 'none';
            cancelBtn.textContent = getText('Close', 'Tutup');
            cancelBtn.style.display = 'block';

            modal.classList.add('show');

            const handleCancel = () => {
                hideModal();
                // Tampilkan kembali tombol confirm
                confirmBtn.style.display = 'block';
            };

            cancelBtn.onclick = handleCancel;
        }

        // Fungsi global untuk switch transaksi (dipanggil dari onclick)
        window.switchToTransaction = function(transactionId) {
            hideModal();

            if (transactionId === 'new') {
                // Buat transaksi baru
                currentTransactionId = Date.now();
                cart = [];
                generateInvoiceNumber(); // Generate nomor faktur baru
                updateCartDisplay();

                showModal(
                    getText('New Transaction', 'Transaksi Baru'),
                    getText('Started new transaction', 'Memulai transaksi baru')
                );
            } else {
                // Switch ke transaksi yang sudah ada
                const transaction = allTransactions.find(t => t.id === transactionId);
                if (transaction) {
                    currentTransactionId = transactionId;
                    cart = [...transaction.cart];
                    updateCartDisplay();

                    showModal(
                        getText('Transaction Switched', 'Transaksi Diganti'),
                        getText(`Switched to ${transaction.customerInfo}`, `Beralih ke ${transaction.customerInfo}`)
                    );
                }
            }
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

        // Load default customer data from database
        async function loadDefaultCustomer() {
            try {
                console.log('Loading default customer...');
                const response = await fetch('/api/customers/default');
                console.log('Customer API response status:', response.status);

                if (response.ok) {
                    const result = await response.json();
                    console.log('Customer API response:', result);

                    if (result.success && result.data) {
                        const customer = result.data;
                        currentCustomer = {
                            id: customer.id || customer.kd_pelanggan,
                            name: customer.name || customer.nama_lengkap,
                            display_name: customer.display_name || customer.name || customer.nama_lengkap,
                            organization: customer.organization || customer.nama_lembaga,
                            phone: customer.phone || customer.telp,
                            is_default: true
                        };
                        console.log('Updated currentCustomer:', currentCustomer);
                        updateCustomerDisplay();
                    } else {
                        console.error('Invalid customer API response:', result);
                        throw new Error('Invalid API response');
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Customer API failed:', response.status, errorText);
                    throw new Error('API request failed');
                }
            } catch (error) {
                console.error('Error loading default customer:', error);
                // Fallback to hardcoded default
                currentCustomer = {
                    id: 1,
                    name: 'Pelanggan',
                    display_name: 'Pelanggan',
                    is_default: true
                };
                updateCustomerDisplay();
            }
        }

        // Load default customer immediately when script loads
        loadDefaultCustomer();

        // Check if continuing an existing transaction first
        @if(isset($continueTransaction) && $continueTransaction)
            loadContinueTransaction(@json($continueTransaction));
        @elseif(isset($startNewTransaction) && $startNewTransaction)
            // Start a completely new transaction - create fresh cart
            console.log('Starting new transaction - creating fresh cart');
            startFreshTransaction();
        @else
            // Only load current cart if not continuing a transaction or starting a new one
            loadCurrentCart();
        @endif

        // Debug API connectivity
        testCartAPI();

        // Debug function to test API connectivity
        async function testCartAPI() {
            try {
                console.log('Testing cart API connectivity...');
                const response = await fetch('/api/cart/debug');
                console.log('Debug API response status:', response.status);

                if (response.ok) {
                    const result = await response.json();
                    console.log('Debug API response:', result);
                } else {
                    const errorText = await response.text();
                    console.error('Debug API failed:', response.status, errorText);
                }
            } catch (error) {
                console.error('Error testing cart API:', error);
            }
        }

        // Real-time Cart Functions
        async function loadCurrentCart() {
            try {
                console.log('Loading current cart for customer:', currentCustomer.id);
                const response = await fetch(`/api/cart?customer_id=${currentCustomer.id}`);
                console.log('Cart API response status:', response.status);

                if (response.ok) {
                    const result = await response.json();
                    console.log('Cart API response:', result);

                    if (result.success && result.data) {
                        currentCartId = result.data.cart_id;
                        cart = result.data.items || [];
                        currentInvoiceNumber = result.data.invoice_number || 'PJ250101000000';
                        console.log('Loaded cart items:', cart.length);
                        console.log('Cart items data:', cart);
                        console.log('Current cart ID:', currentCartId);
                        console.log('Invoice number:', currentInvoiceNumber);
                        updateCartDisplay();
                    } else {
                        console.error('Invalid cart API response:', result);
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Cart API failed:', response.status, errorText);
                }
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }

        async function addToCartRealTime(product, qty = 1) {
            // Store the cart ID before the API call
            const initialCartId = currentCartId;

            try {
                console.log('Adding to cart:', product, 'qty:', qty, 'customer:', currentCustomer.id, 'cartId:', initialCartId);
                console.log('Product ID to send:', product.id || product.kd_produk);
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: product.id || product.kd_produk,
                        qty: qty,
                        customer_id: currentCustomer.id,
                        cart_id: initialCartId // Include current cart ID for continued transactions
                    })
                });

                console.log('Add to cart response status:', response.status);

                if (response.ok) {
                    const result = await response.json();
                    console.log('Add to cart response:', result);

                    if (result.success) {
                        cart = result.data.items || [];
                        currentCartId = result.data.cart_id;
                        currentInvoiceNumber = result.data.invoice_number || currentInvoiceNumber;
                        console.log('Added to cart - New cart ID:', currentCartId);
                        console.log('Added to cart - Items count:', cart.length);
                        updateCartDisplay();
                        showSuccessMessage('Item added to cart');

                        // Check if this was the lazy creation step (initialCartId was null/falsy, but currentCartId is now truthy)
                        if (!initialCartId && currentCartId) {
                            console.log('Lazy creation detected. Redirecting to continue URL.');
                            // Redirect to the new transaction URL to reflect the ID
                            window.location.href = `/cashier?continue=${currentCartId}`;
                            return;
                        }

                    } else {
                        console.error('Add to cart failed:', result.message);
                        showErrorMessage(result.message);
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Add to cart API failed:', response.status, errorText);
                    showErrorMessage('Failed to add item to cart');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showErrorMessage('Error adding item to cart');
            }
        }

        async function updateCartItem(productId, qty) {
            try {
                const response = await fetch('/api/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        qty: qty,
                        customer_id: currentCustomer.id,
                        cart_id: currentCartId
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        cart = result.data.items || [];
                        updateCartDisplay();
                    } else {
                        showErrorMessage(result.message);
                    }
                } else {
                    showErrorMessage('Failed to update cart item');
                }
            } catch (error) {
                console.error('Error updating cart item:', error);
                showErrorMessage('Error updating cart item');
            }
        }


        async function clearCart() {
            try {
                console.log('Clearing cart - customer:', currentCustomer.id, 'cartId:', currentCartId);
                const response = await fetch('/api/cart/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        customer_id: currentCustomer.id,
                        cart_id: currentCartId // Include current cart ID for continued transactions
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    console.log('Clear cart response:', result);
                    if (result.success) {
                        cart = [];
                        currentCartId = null;
                        currentInvoiceNumber = 'PJ250101000000'; // Reset to default
                        updateCartDisplay();
                        showSuccessMessage('Transaction cancelled. Redirecting to My Sales...');

                        // Redirect to My Sales page immediately
                        window.location.href = '/sales/my-sales';
                    } else {
                        console.error('Clear cart failed:', result.message);
                        showErrorMessage(result.message);
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Clear cart API failed:', response.status, errorText);
                    showErrorMessage('Failed to clear cart: ' + response.status);
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
                showErrorMessage('Error clearing cart');
            }
        }

        async function checkoutCart(paymentMethod, totalBayar, catatan = '', statusBarang = 'diterima langsung') {
            try {
                const checkoutData = {
                    payment_method: paymentMethod,
                    total_bayar: totalBayar,
                    customer_id: currentCustomer.id,
                    cart_id: currentCartId,
                    catatan: catatan,
                    status_barang: statusBarang
                };

                console.log('Sending checkout data:', checkoutData);
                console.log('Current cart ID for checkout:', currentCartId);
                console.log('Cart items for checkout:', cart);

                const response = await fetch('/api/cart/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(checkoutData)
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        cart = [];
                        currentCartId = null;
                        updateCartDisplay();

                        // Show checkout success modal with change money
                        showCheckoutSuccessModal(result.data, totalBayar);
                        return result.data;
                    } else {
                        showErrorMessage(result.message);
                        return null;
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Checkout API failed:', response.status, errorText);
                    showErrorMessage(`Checkout failed: ${response.status} - ${errorText}`);
                    return null;
                }
            } catch (error) {
                console.error('Error during checkout:', error);
                showErrorMessage('Error during checkout');
                return null;
            }
        }

        // Print functionality for draft/current transaction
        function printDraftReceipt() {
            if (!currentCartId || cart.length === 0) {
                showModal(
                    getText('Empty Cart', 'Keranjang Kosong'),
                    getText('Your cart is empty. Please add some items before printing a receipt.', 'Keranjang Anda kosong. Silakan tambahkan beberapa item sebelum mencetak struk.')
                );
                return;
            }

            // Open print receipt in new window using the current cart ID (which is the sale ID for drafts)
            window.open('/receipt/print/' + currentCartId, '_blank');
        }

        function showSuccessMessage(message) {
            // You can implement a toast notification here
            console.log('Success:', message);
        }

        function showErrorMessage(message) {
            // You can implement a toast notification here
            console.error('Error:', message);
            alert(message); // Fallback to alert for now
        }

        // Load continue transaction
        function loadContinueTransaction(transactionData) {
            console.log('Loading continue transaction:', transactionData);

            try {
                // Set the current cart ID
                currentCartId = transactionData.kd_penjualan;
                console.log('Set current cart ID:', currentCartId);

                // Set the invoice number from the transaction
                currentInvoiceNumber = transactionData.no_faktur_penjualan || 'PJ' + new Date().getTime();
                console.log('Set invoice number:', currentInvoiceNumber);

                // Update customer information
                if (transactionData.pelanggan) {
                    currentCustomer = {
                        id: transactionData.pelanggan.kd_pelanggan,
                        name: transactionData.pelanggan.nama_lengkap,
                        code: transactionData.pelanggan.kd_pelanggan
                    };
                    console.log('Updated customer:', currentCustomer);
                    updateCustomerDisplay();
                } else {
                    console.log('No customer data found in transaction');
                }

                // Load cart items
                console.log('Transaction data structure:', transactionData);
                console.log('Penjualan details:', transactionData.penjualan_details);

                if (transactionData.penjualan_details && transactionData.penjualan_details.length > 0) {
                    console.log('First detail item:', transactionData.penjualan_details[0]);

                    const cartItems = transactionData.penjualan_details.map(detail => {
                        console.log('Mapping detail:', detail);
                        console.log('Detail fields:', {
                            kd_produk: detail.kd_produk,
                            nama_produk: detail.nama_produk,
                            harga_jual: detail.harga_jual,
                            qty: detail.qty
                        });

                        return {
                            kd_produk: detail.kd_produk,
                            nama_produk: detail.nama_produk,
                            harga_jual: parseFloat(detail.harga_jual) || 0,
                            qty: parseInt(detail.qty) || 0,
                            diskon: parseFloat(detail.diskon) || 0,
                            subtotal: (parseFloat(detail.harga_jual) * parseInt(detail.qty)) - (parseFloat(detail.diskon) || 0),
                            produk_jenis: detail.produk_jenis || 'General'
                        };
                    });

                    cart = cartItems;
                    console.log('Loaded cart items:', cart);
                    updateCartDisplay();
                } else {
                    console.log('No cart items found in transaction');
                    cart = [];
                    updateCartDisplay();
                }

            } catch (error) {
                console.error('Error loading continue transaction:', error);
                showNotification('Error loading transaction: ' + error.message, 'error');
            }
        }

        // Show checkout success modal with change money
        function showCheckoutSuccessModal(transactionData, amountPaid) {
            const changeMoney = amountPaid - transactionData.total_harga;

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800" data-en="Transaction Successful" data-id="Transaksi Berhasil">Transaction Successful</h3>
                                <p class="text-sm text-green-600" data-en="Payment completed successfully" data-id="Pembayaran berhasil diselesaikan">Payment completed successfully</p>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-4">
                        <!-- Invoice Number -->
                        <div class="text-center">
                            <p class="text-sm text-gray-500" data-en="Invoice Number" data-id="Nomor Faktur">Invoice Number</p>
                            <p class="text-lg font-bold text-gray-800">${transactionData.invoice_number}</p>
                        </div>

                        <!-- Transaction Details -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600" data-en="Total Amount" data-id="Jumlah Total">Total Amount</span>
                                <span class="font-semibold">Rp ${formatPrice(transactionData.total_harga)}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600" data-en="Amount Paid" data-id="Jumlah Bayar">Amount Paid</span>
                                <span class="font-semibold">Rp ${formatPrice(amountPaid)}</span>
                            </div>

                            <div class="flex justify-between items-center py-3 bg-blue-50 rounded-lg px-4">
                                <span class="text-lg font-semibold text-blue-800" data-en="Change Money" data-id="Kembalian">Change Money</span>
                                <span class="text-xl font-bold text-blue-900">Rp ${formatPrice(changeMoney)}</span>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="text-center text-sm text-gray-500">
                            <p data-en="Transaction completed at" data-id="Transaksi selesai pada">Transaction completed at</p>
                            <p class="font-medium">${new Date().toLocaleString('id-ID')}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-center space-x-3">
                        <button id="print-receipt-btn" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center space-x-2" data-en="Print Receipt" data-id="Cetak Struk">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <span>Print Receipt</span>
                        </button>
                        <button id="close-success-modal" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors" data-en="Close" data-id="Tutup">
                            Close
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Update language for the modal
            updateLanguage();

            // Print receipt event
            document.getElementById('print-receipt-btn').onclick = () => {
                // Open print receipt in new window
                window.open('/receipt/print/' + transactionData.sale_id, '_blank');
            };

            // Close modal event
            document.getElementById('close-success-modal').onclick = () => {
                console.log('[DEBUG] close-success-modal button clicked');
                document.body.removeChild(modal);
                // Redirect to My Sales page
                window.location.href = '/sales/my-sales';
            };

            // Close modal when clicking outside
            modal.onclick = (e) => {
                if (e.target === modal) {
                    console.log('[DEBUG] close-success-modal overlay clicked');
                    document.body.removeChild(modal);
                    // Redirect to My Sales page
                    window.location.href = '/sales/my-sales';
                }
            };

        }

        // Load payment methods from API
        async function loadPaymentMethods() {
            try {
                const response = await fetch('/api/payment-methods', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        return result.data;
                    }
                }

                // Fallback to default payment methods if API fails
                return [
                    { id: '1', name: 'Tunai' },
                    { id: '2', name: 'BRI-TRFSa EDC BCA' }
                ];
            } catch (error) {
                console.error('Error loading payment methods:', error);
                // Fallback to default payment methods
                return [
                    { id: '1', name: 'Tunai' },
                    { id: '2', name: 'BRI-TRFSa EDC BCA' }
                ];
            }
        }

        // Checkout modal
        async function showCheckoutModal(totalAmount) {
            // Debug logging
            console.log('showCheckoutModal called with totalAmount:', totalAmount);

            // Load payment methods from API
            const paymentMethods = await loadPaymentMethods();

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800" data-en="Checkout" data-id="Checkout">Checkout</h3>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-4">
                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" data-en="Payment Method" data-id="Metode Pembayaran">Payment Method</label>
                            <select id="payment-method" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                ${paymentMethods.map(method => `<option value="${method.name}" ${method.name === 'Tunai' ? 'selected' : ''}>${method.name}</option>`).join('')}
                            </select>
                        </div>

                        <!-- Item Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span data-en="Item Status" data-id="Status Barang">Status Barang</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="status-barang" value="diterima langsung" checked
                                           class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 focus:ring-2">
                                    <span class="ml-2 text-sm font-medium text-gray-700" data-en="received directly" data-id="diterima langsung">diterima langsung</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status-barang" value="dikirimkan ekspedisi"
                                           class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 focus:ring-2">
                                    <span class="ml-2 text-sm font-medium text-gray-700" data-en="sent by expedition" data-id="dikirimkan ekspedisi">dikirimkan ekspedisi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Amount Paid -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" data-en="Amount Paid" data-id="Jumlah Bayar">Amount Paid</label>
                            <input type="number" id="amount-paid" value="${totalAmount}" min="0" step="1000"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1" data-en="Total amount: Rp" data-id="Jumlah total: Rp">Total amount: Rp ${formatPrice(totalAmount)}</p>
                        </div>

                        <!-- Change Calculation -->
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700" data-en="Change" data-id="Kembalian">Change:</span>
                                <span id="change-amount" class="text-lg font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" data-en="Notes (Optional)" data-id="Catatan (Opsional)">Notes (Optional)</label>
                            <textarea id="checkout-notes" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      data-placeholder-en="Add any notes..." data-placeholder-id="Tambahkan catatan..."></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button id="cancel-checkout" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50" data-en="Cancel" data-id="Batal">
                            Cancel
                        </button>
                        <button id="confirm-checkout" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" data-en="Complete Checkout" data-id="Selesaikan Checkout">
                            Complete Checkout
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Update language for the modal
            updateLanguage();

            // Event listeners
            document.getElementById('cancel-checkout').onclick = () => {
                document.body.removeChild(modal);
                // Just close the modal, don't redirect
            };

            // Real-time change calculation
            const amountPaidInput = document.getElementById('amount-paid');
            const changeAmountSpan = document.getElementById('change-amount');

            function updateChangeAmount() {
                const amountPaid = parseFloat(amountPaidInput.value) || 0;
                const change = amountPaid - totalAmount;

                if (change >= 0) {
                    changeAmountSpan.textContent = `Rp ${formatPrice(change)}`;
                    changeAmountSpan.className = 'text-lg font-bold text-green-600';
                } else {
                    changeAmountSpan.textContent = `Rp ${formatPrice(Math.abs(change))} kurang`;
                    changeAmountSpan.className = 'text-lg font-bold text-red-600';
                }
            }

            // Update change amount on input
            amountPaidInput.addEventListener('input', updateChangeAmount);

            // Initial calculation
            updateChangeAmount();

            document.getElementById('confirm-checkout').onclick = async () => {
                const paymentMethod = document.getElementById('payment-method').value;
                const amountPaid = parseFloat(document.getElementById('amount-paid').value);
                const notes = document.getElementById('checkout-notes').value;
                const statusBarang = document.querySelector('input[name="status-barang"]:checked').value;

                if (amountPaid < totalAmount) {
                    alert('Amount paid cannot be less than total amount. Please enter at least Rp ' + formatPrice(totalAmount));
                    return;
                }

                // Process checkout
                const result = await checkoutCart(paymentMethod, amountPaid, notes, statusBarang);
                if (result) {
                    document.body.removeChild(modal);
                    // Optionally show success message or redirect
                }
            };
        }

        // Add event listeners for print buttons and switch transaction
        document.addEventListener('DOMContentLoaded', function() {
            // Load default customer data
            loadDefaultCustomer();

            // Profile dropdown functionality
            const profileBtn = document.getElementById('profile-dropdown-btn');
            if (profileBtn) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleProfileDropdown();
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#profile-dropdown') && !e.target.closest('#profile-dropdown-btn')) {
                    closeProfileDropdown();
                }
            });

            // Header Toggle Functionality (Desktop bottom header visibility)
            const headerToggle = document.getElementById('header-toggle');
            const mainHeader = document.getElementById('main-header');
            const toggleIcon = document.getElementById('toggle-icon');
            let isHeaderVisible = true;

            if (headerToggle && mainHeader && toggleIcon) {
                headerToggle.addEventListener('click', function() {
                    if (isHeaderVisible) {
                        // Hide header
                        mainHeader.classList.add('hidden');
                        toggleIcon.classList.add('rotated');
                        isHeaderVisible = false;
                    } else {
                        // Show header
                        mainHeader.classList.remove('hidden');
                        toggleIcon.classList.remove('rotated');
                        isHeaderVisible = true;
                    }
                });
            }

            // Mobile Menu Drawer Functionality
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

            window.openMobileMenu = function() {
                if (mobileMenuDrawer && mobileMenuOverlay) {
                    mobileMenuDrawer.classList.add('open');
                    mobileMenuOverlay.classList.add('open');
                    document.body.style.overflow = 'hidden';
                }
            }

            window.closeMobileMenu = function() {
                if (mobileMenuDrawer && mobileMenuOverlay) {
                    mobileMenuDrawer.classList.remove('open');
                    mobileMenuOverlay.classList.remove('open');
                    document.body.style.overflow = '';
                }
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', window.openMobileMenu);
            }


            // Auto focus on search input when page loads
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100); // Small delay to ensure page is fully loaded
            }


            // Print button in quick actions
            const quickPrintBtn = document.getElementById('quick-print-btn');
            if (quickPrintBtn) {
                quickPrintBtn.addEventListener('click', printDraftReceipt);
            }

            // Switch transaction button
            const switchBtn = document.getElementById('switch-transaction-btn');
            if (switchBtn) {
                switchBtn.addEventListener('click', switchTransaction);
            }
        });

        // Draft Transactions Management
        async function showDraftTransactions() {
            console.log('showDraftTransactions() called from:', new Error().stack);
            try {
                console.log('Loading draft transactions...');
                const response = await fetch('/api/cart/drafts', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                console.log('Draft transactions response status:', response.status);

                if (response.ok) {
                    const result = await response.json();
                    console.log('Draft transactions result:', result);
                    if (result.success) {
                        displayDraftTransactions(result.data);
                    } else {
                        console.error('Draft transactions API error:', result.message);
                        showErrorMessage(result.message);
                    }
                } else {
                    console.error('Draft transactions API failed with status:', response.status);
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    showErrorMessage('Failed to load draft transactions');
                }
            } catch (error) {
                console.error('Error loading draft transactions:', error);
                showErrorMessage('Error loading draft transactions');
            }
        }

        function displayDraftTransactions(drafts) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-4/5 max-w-4xl mx-4 max-h-[80vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="bg-purple-100 border-b border-purple-200 p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-purple-800">Kembali ke My Sales</h3>
                                    <p class="text-purple-600 text-sm">Kembali ke halaman My Sales untuk melihat transaksi</p>
                                </div>
                            </div>
                            <button id="close-drafts" class="text-purple-600 hover:text-purple-800 transition-colors p-2 rounded-lg hover:bg-purple-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        ${drafts.length === 0 ?
                            `<div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Transaksi Draft</h4>
                                <p class="text-gray-500">Belum ada transaksi yang disimpan sebagai draft.</p>
                            </div>` :
                            `<div class="space-y-3">
                                ${drafts.map(draft => `
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-all">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-4 mb-2">
                                                    <span class="font-semibold text-gray-900">${draft.invoice_number}</span>
                                                    <span class="text-sm text-gray-500">${draft.customer_name}</span>
                                                    <span class="text-sm text-gray-500">${new Date(draft.created_at).toLocaleString()}</span>
                                                </div>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                    <span>Items: ${draft.item_count}</span>
                                                    <span>Total: Rp ${formatPrice(draft.total_harga)}</span>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button onclick="switchToDraft(${draft.id})" class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg hover:text-blue-900 text-sm font-semibold flex items-center space-x-1 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Lanjutkan</span>
                                                </button>
                                                <button onclick="deleteDraft(${draft.id})" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-800 rounded-lg hover:text-red-900 text-sm font-semibold flex items-center space-x-1 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>`
                        }
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Total: <span class="font-semibold">${drafts.length}</span> transaksi draft
                            </div>
                            <button onclick="startNewTransaction()"
                                    class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-800 rounded-lg font-semibold transition-all">
                                Transaksi Baru
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Close modal
            document.getElementById('close-drafts').addEventListener('click', () => {
                document.body.removeChild(modal);
            });

            // Close on backdrop click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        async function switchToDraft(draftId) {
            try {
                const response = await fetch('/api/cart/switch-draft', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        draft_id: draftId
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        // Update current cart with draft data
                        cart = result.data.items || [];
                        currentCartId = result.data.cart_id;
                        updateCartDisplay();
                        showSuccessMessage('Switched to draft transaction');

                        // Close the modal
                        const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
                        if (modal) {
                            document.body.removeChild(modal);
                        }
                    } else {
                        showErrorMessage(result.message);
                    }
                } else {
                    showErrorMessage('Failed to switch to draft transaction');
                }
            } catch (error) {
                console.error('Error switching to draft:', error);
                showErrorMessage('Error switching to draft transaction');
            }
        }

        async function deleteDraft(draftId) {
            if (!confirm('Are you sure you want to delete this draft transaction?')) {
                return;
            }

            try {
                const response = await fetch('/api/cart/delete-draft', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        draft_id: draftId
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        showSuccessMessage('Draft transaction deleted successfully');
                        // Refresh the draft transactions list
                        showDraftTransactions();
                    } else {
                        showErrorMessage(result.message);
                    }
                } else {
                    showErrorMessage('Failed to delete draft transaction');
                }
            } catch (error) {
                console.error('Error deleting draft:', error);
                showErrorMessage('Error deleting draft transaction');
            }
        }

        async function startFreshTransaction() {
            try {
                console.log('Creating fresh transaction on page load...');

                // Create a fresh transaction via API
                const response = await fetch('/api/cart/fresh', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        customer_id: currentCustomer.id
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    console.log('Fresh transaction created:', result);

                    if (result.success) {
                        // Update cart state with new transaction
                        currentCartId = result.data.kd_penjualan;
                        currentInvoiceNumber = result.data.no_faktur_penjualan;
                        cart = [];
                        updateCartDisplay();
                        console.log('Fresh transaction loaded successfully');
                    } else {
                        console.error('Failed to create fresh transaction:', result.message);
                        // Fallback to manual creation
                        cart = [];
                        currentCartId = null;
                        currentInvoiceNumber = 'PJ' + new Date().getTime();
                        updateCartDisplay();
                    }
                } else {
                    console.error('Error creating fresh transaction');
                    // Fallback to manual creation
                    cart = [];
                    currentCartId = null;
                    currentInvoiceNumber = 'PJ' + new Date().getTime();
                    updateCartDisplay();
                }

            } catch (error) {
                console.error('Error starting fresh transaction:', error);
                // Fallback to manual creation
                cart = [];
                currentCartId = null;
                currentInvoiceNumber = 'PJ' + new Date().getTime();
                updateCartDisplay();
            }
        }

        async function startNewTransaction() {
            try {
                console.log('Starting fresh transaction...');

                // Create a fresh transaction via API
                const response = await fetch('/api/cart/fresh', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        customer_id: currentCustomer.id
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    console.log('Fresh transaction created:', result);

                    if (result.success) {
                        // Update cart state with new transaction
                        currentCartId = result.data.kd_penjualan;
                        currentInvoiceNumber = result.data.no_faktur_penjualan;
                        cart = [];
                        updateCartDisplay();

                        // Close the modal
                        const modal = document.querySelector('.fixed.inset-0');
                        if (modal) {
                            document.body.removeChild(modal);
                        }

                        showSuccessMessage('Transaksi baru dimulai');
                    } else {
                        showErrorMessage(result.message || 'Failed to start new transaction');
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Error creating fresh transaction:', errorText);
                    showErrorMessage('Failed to start new transaction');
                }

            } catch (error) {
                console.error('Error starting new transaction:', error);
                showErrorMessage('Error starting new transaction');
            }
        }
    </script>
    </body>
    </html>
