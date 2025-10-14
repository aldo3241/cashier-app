<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>All Cashiers Sales Report - Inspizo Spiritosanto</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800" data-en="All Cashiers Sales Report" data-id="Laporan Penjualan Semua Kasir">All Cashiers Sales Report</h1>
                        <p class="text-sm text-gray-600" data-en="All transactions from all cashiers" data-id="Semua transaksi dari semua kasir">All transactions from all cashiers</p>
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                        <p class="text-2xl font-bold text-gray-900">{{ $sales->total() }}</p>
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
            
            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="text-center py-8 hidden">
                <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-blue-500 hover:bg-blue-400 transition ease-in-out duration-150 cursor-not-allowed">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span data-en="Loading transactions..." data-id="Memuat transaksi...">Loading transactions...</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table id="salesTable" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th data-en="Transaction ID" data-id="ID Transaksi">Transaction ID</th>
                            <th data-en="Date" data-id="Tanggal">Date</th>
                            <th data-en="Time" data-id="Waktu">Time</th>
                            <th data-en="Cashier" data-id="Kasir">Cashier</th>
                            <th data-en="Items" data-id="Item">Items</th>
                            <th data-en="Amount" data-id="Jumlah">Amount</th>
                            <th data-en="Payment Method" data-id="Metode Pembayaran">Payment Method</th>
                            <th data-en="Customer" data-id="Pelanggan">Customer</th>
                            <th data-en="Status" data-id="Status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td><span class="font-mono font-semibold text-blue-600">{{ $sale['id'] }}</span></td>
                            <td>{{ $sale['date'] }}</td>
                            <td>{{ $sale['time'] }}</td>
                            <td><span class="font-medium text-purple-600">{{ $sale['cashier'] }}</span></td>
                            <td class="text-center">{{ $sale['items'] }}</td>
                            <td><span class="font-semibold text-green-600">{{ $sale['formatted_amount'] }}</span></td>
                            <td>{{ $sale['payment_method'] }}</td>
                            <td>{{ $sale['customer'] }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($sale['status']) }}">
                                    {{ $sale['status'] }}
                                </span>
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
                    element.textContent = text;
                }
            });
            
            // Redraw table to update column headers
            if ($.fn.DataTable.isDataTable('#salesTable')) {
                $('#salesTable').DataTable().columns.adjust().draw();
            }
        }

        // Initialize DataTable with performance optimizations
        $(document).ready(function() {
            // Show loading indicator
            $('#loadingIndicator').removeClass('hidden');
            
            // Initialize DataTable
            var table = $('#salesTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'desc'], [2, 'desc']], // Sort by date and time descending
                deferRender: true, // Defer rendering for better performance
                processing: true, // Show processing indicator
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
                    },
                    processing: "Loading transactions..."
                },
                columnDefs: [
                    { className: "text-center", targets: [4, 8] },
                    { orderable: false, targets: [7, 8] } // Disable sorting on customer and status columns
                ],
                dom: 'lrtip', // Remove search box and length menu for better performance
                scrollX: true, // Enable horizontal scrolling for mobile
                scrollCollapse: true,
                pagingType: 'simple_numbers', // Use simple pagination for better performance
                initComplete: function() {
                    // Hide loading indicator when table is ready
                    $('#loadingIndicator').addClass('hidden');
                }
            });
            
            // Initialize language
            updateLanguage();
        });
    </script>
</body>
</html>

