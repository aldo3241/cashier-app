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
        <a href="{{ route('cashier.index') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-bold rounded-lg shadow-lg transition-all duration-200">
            <svg class="w-6 h-6 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span data-en="New Transaction" data-id="Transaksi Baru">New Transaction</span>
        </a>
    </div>
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
                            <th data-en="Transaction ID" data-id="ID Transaksi">Transaction ID</th>
                            <th data-en="Date" data-id="Tanggal">Date</th>
                            <th data-en="Time" data-id="Waktu">Time</th>
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
                    // Only update text content for non-th elements or update innerHTML for th
                    if (element.tagName === 'TH') {
                        element.textContent = text;
                    } else {
                        element.textContent = text;
                    }
                }
            });
            
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
                    { className: "text-center", targets: [3, 7] }
                ]
            });
            
            // Initialize language
            updateLanguage();
        });
    </script>
</body>
</html>

