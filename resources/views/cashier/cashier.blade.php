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
            padding-bottom: 80px; /* Space for bottom header */
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
    <!-- Bottom Header - Collapsible -->
    <header id="main-header" class="header-bottom bg-white shadow-lg border-t border-gray-200 transition-all duration-300 ease-in-out">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Left: User Info -->
                <div class="flex items-center space-x-3">
                    <!-- Profile Dropdown -->
                    <div class="relative">
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
                    <div class="text-left">
                        <div class="text-sm text-gray-500" data-en="Logged in as" data-id="Masuk sebagai">Masuk sebagai</div>
                        <div class="font-medium text-gray-800">{{ auth()->user()->nama ?? auth()->user()->username ?? 'User' }}</div>
                    </div>
                </div>
                
                <!-- Center: FreshFood Logo & Text -->
                <div class="flex items-center space-x-3">
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
                                data-id-placeholder="Cari produk berdasarkan nama, barcode, atau kategori..."
                                class="w-full text-xl font-medium text-gray-800 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-blue-500 pl-12 pr-4 py-4 bg-gray-50 rounded-lg"
                                autocomplete="off"
                            >
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <button class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Print Receipt">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </button>
                            </div>
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
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-800" data-en="Shopping Cart" data-id="Keranjang Belanja">Shopping Cart</h2>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500" data-en="Items:" data-id="Item:">Items:</span>
                                <span id="cart-count" class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-semibold">0</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cart Header -->
                    <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                        <div class="col-span-5" data-en="Product" data-id="Produk">Product</div>
                        <div class="col-span-2 text-right" data-en="Price" data-id="Harga">Price</div>
                        <div class="col-span-2 text-center" data-en="Qty" data-id="Jml">Qty</div>
                        <div class="col-span-2 text-right" data-en="Total" data-id="Total">Total</div>
                        <div class="col-span-1 text-center" data-en="Action" data-id="Aksi">Action</div>
                    </div>
                    
                    <!-- Cart Items -->
                    <div id="cart-items" class="flex-1 overflow-y-auto">
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            <p class="text-lg font-medium" data-en="Your cart is empty" data-id="Keranjang Anda kosong">Your cart is empty</p>
                            <p class="text-sm" data-en="Start by searching for products above" data-id="Mulai dengan mencari produk di atas">Start by searching for products above</p>
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
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4" data-en="Customer Information" data-id="Informasi Pelanggan">Informasi Pelanggan</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="customer-name" class="block text-sm font-medium text-gray-700 mb-2" data-en="Customer Name" data-id="Nama Pelanggan">Nama Pelanggan</label>
                                <input type="text" id="customer-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan nama pelanggan..." data-en="Enter customer name..." data-id="Masukkan nama pelanggan...">
                            </div>
                            
                            <button id="switch-transaction-btn" class="w-full bg-orange-100 hover:bg-orange-200 text-orange-800 p-4 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span data-en="Switch Transaction" data-id="Ganti Transaksi">Switch Transaction</span>
                            </button>
                        </div>
                    </div>
                </div>



                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6">                        
                        <div class="space-y-3">
                        <button onclick="clearCart()" class="w-full bg-red-100 hover:bg-red-200 text-red-800 p-4 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span data-en="Cancel Order" data-id="Batalkan Pesanan">Cancel Order</span>
                        </button>
                            <button class="w-full bg-green-100 hover:bg-green-200 text-green-800 p-4 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
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

    <!-- JavaScript -->
    <script>
        // CSRF Token for API calls
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Featured products from backend (will be populated)
        let featuredProducts = @json($featuredProducts ?? []);

        let cart = [];
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
            const hour = String(now.getHours()).padStart(2, '0');
            const minute = String(now.getMinutes()).padStart(2, '0');
            const second = String(now.getSeconds()).padStart(2, '0');
            
            invoiceNumber = `PJ${year}${month}${day}${hour}${minute}${second}`;
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

                    // Check if already in cart
                    const existingItem = cart.find(item => item.id === product.id);
                    if (existingItem) {
                        if (existingItem.quantity >= product.stock) {
                            showNotification(getText('Stock limit reached', 'Batas stok tercapai'), 'error');
                            searchInput.style.backgroundColor = '#fef2f2';
                            searchInput.style.borderColor = '#ef4444';
                            return;
                        }
                        existingItem.quantity += 1;
                    } else {
                        cart.push({ ...product, quantity: 1 });
                    }

                    // Success!
                    updateCartDisplay();
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

                // Check if product has enough stock
                if (product.stock <= 0) {
                    showModal(
                        getText('Out of Stock', 'Stok Habis'),
                        getText(`${product.name} is out of stock.`, `${product.name} sedang habis stok.`)
                    );
                    return;
                }

                const existingItem = cart.find(item => item.id === productId);
                if (existingItem) {
                    if (existingItem.quantity >= product.stock) {
                        showModal(
                            getText('Insufficient Stock', 'Stok Tidak Cukup'),
                            getText(`Only ${product.stock} items available.`, `Hanya tersedia ${product.stock} item.`)
                        );
                        return;
                    }
                    existingItem.quantity += 1;
                } else {
                    cart.push({ ...product, quantity: 1 });
                }

                updateCartDisplay();
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

            // Calculate total (no tax)
            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Update display
            cartCount.textContent = cart.length;
            total.textContent = `Rp ${formatPrice(totalAmount)}`;
            checkoutBtn.disabled = false;
            checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            // Render cart items
            cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item grid grid-cols-12 gap-4 p-4 border-b border-gray-100">
                    <div class="col-span-5">
                        <div class="font-medium text-gray-800">${item.name}</div>
                        <div class="text-sm text-gray-500">${item.category}</div>
                    </div>
                    <div class="col-span-2 text-right text-gray-600">Rp ${formatPrice(item.price)}</div>
                    <div class="col-span-2 flex items-center justify-center space-x-2">
                        <button class="quantity-btn w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center" onclick="updateQuantity('${item.id}', -1)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="font-semibold w-8 text-center">${item.quantity}</span>
                        <button class="quantity-btn w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center" onclick="updateQuantity('${item.id}', 1)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="col-span-2 text-right font-semibold text-gray-800">Rp ${formatPrice(item.price * item.quantity)}</div>
                    <div class="col-span-1 flex items-center justify-center">
                        <button class="text-red-500 hover:text-red-700 p-1" onclick="removeFromCart('${item.id}')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Update quantity
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (!item) return;

            item.quantity += change;
            if (item.quantity <= 0) {
                removeFromCart(productId);
            } else {
                updateCartDisplay();
            }
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        }

        // Clear cart
        document.querySelector('button[onclick="clearCart()"]')?.addEventListener('click', function() {
            showModal(
                getText('Clear Cart', 'Kosongkan Keranjang'),
                getText('Are you sure you want to clear the cart?', 'Apakah Anda yakin ingin mengosongkan keranjang?'),
                function() {
                    // Confirm action - clear the cart
                    cart = [];
                    generateInvoiceNumber(); // Generate nomor faktur baru
                    updateCartDisplay();
                },
                null // Cancel action - do nothing
            );
        });

        // Checkout functionality
        document.getElementById('checkout-btn').addEventListener('click', function() {
            if (cart.length === 0) return;
            
            // Here you would implement the actual checkout process
            showModal(
                getText('Checkout', 'Checkout'),
                getText('Checkout functionality will be implemented here!', 'Fitur checkout akan diimplementasikan di sini!')
            );
        });

        // Language switcher functionality
        let currentLanguage = 'id'; // Default to Indonesian
        
        document.getElementById('language-toggle').addEventListener('click', function() {
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
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
            
            // Update search placeholder
            const searchInput = document.getElementById('product-search');
            if (currentLanguage === 'id') {
                searchInput.placeholder = searchInput.getAttribute('data-id-placeholder');
            } else {
                searchInput.placeholder = searchInput.getAttribute('data-en-placeholder');
            }
            
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
            const modal = document.getElementById('custom-modal');
            modal.classList.remove('show');
        }
        
        // Print functionality
        function printReceipt() {
            if (cart.length === 0) {
                showModal(
                    getText('Empty Cart', 'Keranjang Kosong'), 
                    getText('Your cart is empty. Please add some items before printing a receipt.', 'Keranjang Anda kosong. Silakan tambahkan beberapa item sebelum mencetak struk.'),
                    null, // No confirm action needed
                    null  // No cancel action needed
                );
                return;
            }
            
            // Create a print-friendly receipt
            const printWindow = window.open('', '_blank');
            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            const receiptContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .receipt { max-width: 300px; margin: 0 auto; }
                        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
                        .item { display: flex; justify-content: space-between; margin-bottom: 5px; }
                        .total { border-top: 1px solid #000; padding-top: 10px; margin-top: 20px; font-weight: bold; }
                        .center { text-align: center; }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header">
                            <h2>Inspizo Spiritosanto</h2>
                            <p>Cashier Receipt</p>
                            <p>${new Date().toLocaleString()}</p>
                        </div>
                        
                        <div class="items">
                            ${cart.map(item => `
                                <div class="item">
                                    <span>${item.name} x${item.quantity}</span>
                                    <span>$${(item.price * item.quantity).toFixed(2)}</span>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="total">
                            <div class="item">
                                <span>Total:</span>
                                <span>$${totalAmount.toFixed(2)}</span>
                            </div>
                        </div>
                        
                        <div class="center" style="margin-top: 30px;">
                            <p>Thank you for your purchase!</p>
                        </div>
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(receiptContent);
            printWindow.document.close();
            printWindow.print();
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
            
            titleElement.textContent = getText('Switch Transaction', 'Ganti Transaksi');
            
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

        // Add event listeners for print buttons and switch transaction
        document.addEventListener('DOMContentLoaded', function() {
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

            // Header Toggle Functionality
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
            
            // Auto focus on search input when page loads
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100); // Small delay to ensure page is fully loaded
            }
            
            // Print button in search area
            const searchPrintBtn = document.querySelector('button[title="Print Receipt"]');
            if (searchPrintBtn) {
                searchPrintBtn.addEventListener('click', printReceipt);
            }
            
            // Print button in quick actions
            const quickPrintBtn = document.querySelector('button:has(span[data-en="Print Receipt"])');
            if (quickPrintBtn) {
                quickPrintBtn.addEventListener('click', printReceipt);
            }
            
            // Switch transaction button
            const switchBtn = document.getElementById('switch-transaction-btn');
            if (switchBtn) {
                switchBtn.addEventListener('click', switchTransaction);
            }
        });
    </script>
    </body>
    </html>
