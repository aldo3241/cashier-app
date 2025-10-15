<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Sales Report - Inspizo Spiritosanto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <style>
        /* Custom DataTable Styling */
        .dataTables_wrapper {
            padding: 20px;
        }
        
        table.dataTable thead th {
            background: linear-gradient(135deg, #10b981);
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
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800" data-en="My Sales Report" data-id="Laporan Penjualan Saya">My Sales Report</h1>
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
                    
                    <!-- Close Button -->
                    <button onclick="window.close()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all">
                        <span data-en="Close" data-id="Tutup">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 flex justify-end">
        <a href="{{ route('cashier.index', ['new' => 1]) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-bold rounded-lg shadow-lg transition-all duration-200">
            <svg class="w-6 h-6 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span data-en="New Transaction" data-id="Transaksi Baru">New Transaction</span>
        </a>
    </div>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Error Messages -->
        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Success Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Total Transactions" data-id="Total Transaksi">Total Transactions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $sales->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Total Revenue" data-id="Total Pendapatan">Total Revenue</p>
                        <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($sales->sum('amount'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Average Sale" data-id="Rata-rata Penjualan">Average Sale</p>
                        <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($sales->avg('amount'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600" data-en="Total Items" data-id="Total Item">Total Items</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $sales->sum('items') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800" data-en="Sales Transactions" data-id="Transaksi Penjualan">Sales Transactions</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table id="salesTable" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th data-en="Invoice Number" data-id="No Faktur">Invoice Number</th>
                            <th data-en="Date" data-id="Tanggal">Date</th>
                            <th data-en="Time" data-id="Waktu">Time</th>
                            <th data-en="Items" data-id="Item">Items</th>
                            <th data-en="Amount" data-id="Jumlah">Amount</th>
                            <th data-en="Payment Method" data-id="Metode Pembayaran">Payment Method</th>
                            <th data-en="Customer" data-id="Pelanggan">Customer</th>
                            <th data-en="Status" data-id="Status">Status</th>
                            <th data-en="Actions" data-id="Aksi">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td><span class="font-mono text-sm text-gray-700">{{ $sale['invoice_number'] }}</span></td>
                            <td>{{ $sale['date'] }}</td>
                            <td>{{ $sale['time'] }}</td>
                            <td class="text-center">{{ $sale['items'] }}</td>
                            <td><span class="font-semibold text-green-600">{{ $sale['formatted_amount'] }}</span></td>
                            <td>{{ $sale['payment_method'] }}</td>
                            <td>{{ $sale['customer'] }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($sale['status']) }}">
                                    {{ $sale['status'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($sale['status_bayar'] === 'Lunas')
                                    <a href="#" onclick="showTransactionDetails({{ $sale['id'] }}, '{{ $sale['invoice_number'] }}')" 
                                       class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors"
                                       data-en="View Details" data-id="Lihat Detail">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span data-en="View Details" data-id="Lihat Detail">View Details</span>
                                    </a>
                                @elseif($sale['status_bayar'] === 'Belum Lunas')
                                    <a href="{{ route('api.cart.continue', $sale['id']) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"
                                       data-en="Continue Transaction" data-id="Lanjutkan Transaksi">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span data-en="Continue" data-id="Lanjutkan">Continue</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm" data-en="No Details" data-id="Tidak Ada">No Details</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        // Language switcher
        let currentLanguage = 'id'; // Default to Indonesian
        
        document.getElementById('language-toggle').addEventListener('click', function() {
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
            updateLanguage();
        });

        function updateLanguage() {
            const langButton = document.getElementById('current-lang');
            const elements = document.querySelectorAll('[data-en], [data-id]');
            
            langButton.textContent = currentLanguage.toUpperCase();
            
            elements.forEach(element => {
                if (element.hasAttribute(`data-${currentLanguage}`)) {
                    const text = element.getAttribute(`data-${currentLanguage}`);
                    // Only update text content for non-th elements or update innerHTML for th
                    if (element.tagName === 'TH') {
                        element.textContent = text;
                    } else {
                        element.textContent = text;
                    }
                }
            });
            
            // Update modal content if it exists
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                const modalElements = modal.querySelectorAll('[data-en], [data-id]');
                modalElements.forEach(element => {
                    // Skip elements that have been updated with real data
                    if (element.id === 'masuk-amount' && (element.textContent.includes('Rp') || element.textContent.includes('TEST:'))) {
                        return; // Skip this element as it has real data
                    }
                    if (element.id === 'payment-method' && !element.textContent.includes('Memuat') && !element.textContent.includes('Loading') && element.textContent.trim() !== '') {
                        return; // Skip this element as it has real data
                    }
                    if (element.id === 'keluar-amount' && element.textContent.includes('Rp')) {
                        return; // Skip this element as it has real data
                    }
                    if (element.id === 'kategori' && element.textContent !== 'Loading...' && element.textContent !== 'Memuat...' && element.textContent.trim() !== '') {
                        return; // Skip this element as it has real data
                    }
                    
                    // Only update if the element still contains loading text
                    if (element.textContent.includes('Memuat') || element.textContent.includes('Loading')) {
                        if (element.hasAttribute(`data-${currentLanguage}`)) {
                            const text = element.getAttribute(`data-${currentLanguage}`);
                            element.textContent = text;
                        }
                    }
                });
            }
            
            // Redraw table to update column headers
            if ($.fn.DataTable.isDataTable('#salesTable')) {
                $('#salesTable').DataTable().columns.adjust().draw();
            }
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('#salesTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'desc'], [2, 'desc']], // Sort by date and time descending
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search transactions...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                    infoEmpty: "No transactions found",
                    infoFiltered: "(filtered from _MAX_ total transactions)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { className: "text-center", targets: [3, 7, 8] }
                ]
            });
            
            // Initialize language
            updateLanguage();
        });

        // Function to show comprehensive transaction details
        function showTransactionDetails(transactionId, invoiceNumber) {
            console.log('=== SHOW TRANSACTION DETAILS CALLED ===');
            console.log('Transaction ID:', transactionId);
            console.log('Invoice Number:', invoiceNumber);
            
            // Create modal to show transaction details
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-5/6 max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="bg-blue-100 border-b border-blue-200 p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-800" data-en="Transaction Details" data-id="Detail Transaksi">Transaction Details</h3>
                                    <p class="text-sm text-blue-600" data-en="Invoice" data-id="Faktur">Invoice: ${invoiceNumber}</p>
                                </div>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <!-- Tabs -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button onclick="showTab('financial')" id="financial-tab" class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                                    <span data-en="Financial Mutation" data-id="Mutasi Keuangan">Financial Mutation</span>
                                </button>
                                <button onclick="showTab('stock')" id="stock-tab" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                    <span data-en="Stock Mutation" data-id="Mutasi Stok">Stock Mutation</span>
                                </button>
                            </nav>
                        </div>
                        
                        <!-- Financial Mutation Tab -->
                        <div id="financial-content" class="tab-content">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700" data-en="Invoice Number" data-id="No Faktur">Invoice Number</label>
                                        <p class="mt-1 text-sm text-gray-900 font-mono">${invoiceNumber}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700" data-en="Status" data-id="Status">Status</label>
                                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span data-en="Completed" data-id="Selesai">Completed</span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3" data-en="Financial Details" data-id="Detail Keuangan">Financial Details</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" data-en="Money In" data-id="Uang Masuk">Money In</label>
                                            <p class="mt-1 text-sm text-green-600 font-semibold" id="masuk-amount" data-en="Loading..." data-id="Memuat...">Loading...</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" data-en="Money Out" data-id="Uang Keluar">Money Out</label>
                                            <p class="mt-1 text-sm text-gray-600" id="keluar-amount">Rp 0</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3" data-en="Transaction Info" data-id="Info Transaksi">Transaction Info</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" data-en="Category" data-id="Kategori">Category</label>
                                            <p class="mt-1 text-sm text-gray-900" id="kategori">Penjualan</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" data-en="Payment Method" data-id="Metode Pembayaran">Payment Method</label>
                                            <p class="mt-1 text-sm text-gray-900" id="payment-method" data-en="Loading..." data-id="Memuat...">Loading...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stock Mutation Tab -->
                        <div id="stock-content" class="tab-content hidden">
                            <div class="space-y-4">
                                <h4 class="text-sm font-medium text-gray-900" data-en="Stock Movements" data-id="Pergerakan Stok">Stock Movements</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div id="stock-mutations-list" class="space-y-3">
                                        <div class="text-center text-gray-500">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                                            <p class="mt-2" data-en="Loading stock mutations..." data-id="Memuat mutasi stok...">Loading stock mutations...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                        <div class="flex justify-end">
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                                <span data-en="Close" data-id="Tutup">Close</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Add a small delay to ensure DOM elements are ready
            setTimeout(() => {
                loadTransactionDetails(transactionId, invoiceNumber);
            }, 100);
        }

        // Function to show tab content
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('[id$="-tab"]').forEach(tab => {
                tab.classList.remove('border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }

        // Function to load transaction details
        async function loadTransactionDetails(transactionId, invoiceNumber) {
            try {
                console.log('=== LOADING TRANSACTION DETAILS ===');
                console.log('Transaction ID:', transactionId);
                console.log('Invoice Number:', invoiceNumber);
                
                // Check if elements exist
                const masukElement = document.getElementById('masuk-amount');
                const paymentElement = document.getElementById('payment-method');
                const keluarElement = document.getElementById('keluar-amount');
                const kategoriElement = document.getElementById('kategori');
                
                console.log('All required elements found');
                
                if (!masukElement || !paymentElement || !keluarElement || !kategoriElement) {
                    console.error('Required elements not found! Retrying in 200ms...');
                    setTimeout(() => {
                        loadTransactionDetails(transactionId, invoiceNumber);
                    }, 200);
                    return;
                }
                
                console.log('Elements found, proceeding with API call...');
                
                // Load financial mutation details
                const keuanganResponse = await fetch(`/api/keuangan/by-invoice/${invoiceNumber}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                console.log('API response status:', keuanganResponse.status);
                
                if (keuanganResponse.ok) {
                    const keuanganData = await keuanganResponse.json();
                    console.log('Financial data loaded successfully');
                    
                    if (keuanganData.success && keuanganData.data) {
                        
                        masukElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(keuanganData.data.masuk);
                        document.getElementById('keluar-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(keuanganData.data.keluar);
                        document.getElementById('kategori').textContent = keuanganData.data.keuangan_kategori;
                        paymentElement.textContent = keuanganData.data.keuangan_kotak;
                        
                        console.log('Financial data displayed successfully');
                        
                        // Note: Language is already set when modal is created, no need to update
                    } else {
                        console.log('Keuangan API error:', keuanganData.message);
                        masukElement.textContent = 'Error loading data';
                        paymentElement.textContent = 'Error loading data';
                    }
                } else {
                    console.log('Keuangan API failed with status:', keuanganResponse.status);
                    const errorText = await keuanganResponse.text();
                    console.log('Error response:', errorText);
                    masukElement.textContent = 'API Error: ' + keuanganResponse.status;
                    paymentElement.textContent = 'API Error: ' + keuanganResponse.status;
                }

                // Load stock mutations
                const stockResponse = await fetch(`/api/stock/by-invoice/${invoiceNumber}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (stockResponse.ok) {
                    const stockData = await stockResponse.json();
                    if (stockData.success) {
                        displayStockMutations(stockData.data);
                    } else {
                        document.getElementById('stock-mutations-list').innerHTML = '<p class="text-gray-500" data-en="No stock mutations found" data-id="Tidak ada mutasi stok">No stock mutations found</p>';
                    }
                } else {
                    document.getElementById('stock-mutations-list').innerHTML = '<p class="text-red-500" data-en="Error loading stock mutations" data-id="Error memuat mutasi stok">Error loading stock mutations</p>';
                }

            } catch (error) {
                console.error('=== ERROR LOADING TRANSACTION DETAILS ===');
                console.error('Error:', error);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                
                // Try to update elements if they exist
                const masukElement = document.getElementById('masuk-amount');
                const paymentElement = document.getElementById('payment-method');
                const stockElement = document.getElementById('stock-mutations-list');
                
                if (masukElement) {
                    masukElement.textContent = 'Error: ' + error.message;
                }
                if (paymentElement) {
                    paymentElement.textContent = 'Error: ' + error.message;
                }
                if (stockElement) {
                    stockElement.innerHTML = '<p class="text-red-500" data-en="Error loading data" data-id="Error memuat data">Error: ' + error.message + '</p>';
                }
            }
        }

        // Function to display stock mutations
        function displayStockMutations(mutations) {
            const container = document.getElementById('stock-mutations-list');
            
            if (mutations.length === 0) {
                container.innerHTML = '<p class="text-gray-500" data-en="No stock mutations found" data-id="Tidak ada mutasi stok">No stock mutations found</p>';
                return;
            }

            let html = '';
            mutations.forEach(mutation => {
                const type = mutation.masuk > 0 ? 'IN' : 'OUT';
                const qty = mutation.masuk > 0 ? mutation.masuk : mutation.keluar;
                const typeColor = type === 'IN' ? 'text-green-600' : 'text-red-600';
                const bgColor = type === 'IN' ? 'bg-green-50' : 'bg-red-50';
                
                html += `
                    <div class="flex items-center justify-between p-3 ${bgColor} rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 ${bgColor} rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 ${typeColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${mutation.produk ? mutation.produk.nama_produk : 'Product: ' + mutation.kd_produk}</p>
                                <p class="text-xs text-gray-500">${mutation.klasifikasi} | ${mutation.dibuat_oleh}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold ${typeColor}">
                                <span data-en="${type}" data-id="${type === 'IN' ? 'MASUK' : 'KELUAR'}">${type}</span>: ${qty}
                            </p>
                            <p class="text-xs text-gray-500">${mutation.date_created ? new Date(mutation.date_created).toLocaleString() : '<span data-en="No date" data-id="Tidak ada tanggal">No date</span>'}</p>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
    </script>
</body>
</html>

