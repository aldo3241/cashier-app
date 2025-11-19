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
            background-color: #fed7aa;
            color: #c2410c;
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
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_transactions'] }}</p>
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
                            <p class="text-sm text-gray-600" data-en="Total Revenue Today" data-id="Total Pendapatan Hari Ini">Total Revenue Today</p>
                            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
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
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($stats['average_sale'], 0, ',', '.') }}</p>
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
                            <p class="text-2xl font-bold text-orange-600">{{ $stats['total_items'] }}</p>
                        </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800" data-en="Sales Transactions" data-id="Transaksi Penjualan">Sales Transactions</h2>
                <a href="{{ route('cashier.index', ['new' => 1]) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-bold rounded-lg shadow-lg transition-all duration-200">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span data-en="New Transaction" data-id="Transaksi Baru">New Transaction</span>
                </a>
            </div>

            <!-- Loading indicator -->
            <div id="loadingIndicator" class="hidden px-6 py-4 text-center">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                        <!-- Data will be loaded via AJAX -->
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
        // Language switcher with persistence
        let currentLanguage = localStorage.getItem('language') || 'id'; // Default to Indonesian

        document.getElementById('language-toggle').addEventListener('click', function() {
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
            localStorage.setItem('language', currentLanguage);
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

        // Initialize DataTable with server-side processing
        $(document).ready(function() {
            try {
                console.log('Initializing DataTable with server-side processing...');

                // Show loading indicator
                $('#loadingIndicator').removeClass('hidden');

                var table = $('#salesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('api.sales.my-sales-data') }}",
                        type: 'GET',
                        error: function(xhr, error, thrown) {
                            console.error('DataTables AJAX Error:', error, thrown);
                            console.error('Response:', xhr.responseText);
                        }
                    },
                    responsive: true,
                    pageLength: 25,
                    order: [[1, 'desc']], // Sort by date descending
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
                        { className: "text-center", targets: [3, 8] }
                    ],
                    columns: [
                        { data: 'invoice_number', name: 'invoice_number' },
                        { data: 'date', name: 'date' },
                        { data: 'time', name: 'time' },
                        { data: 'items', name: 'items' },
                        { data: 'formatted_amount', name: 'amount' },
                        { data: 'payment_method', name: 'payment_method' },
                        { data: 'customer', name: 'customer' },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    return '<span class="status-badge status-' + data.toLowerCase().replace(' ', '-') + '">' + data + '</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'id',
                            name: 'id',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    if (row.status_bayar === 'Lunas') {
                                        return '<a href="/sales/transaction/' + data + '" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><span>View Details</span></a>';
                                    } else if (row.status_bayar === 'Belum Lunas') {
                                        return '<a href="/api/cart/continue/' + data + '" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Continue</span></a>';
                                    } else {
                                        return '<span class="text-gray-400 text-sm">No Details</span>';
                                    }
                                }
                                return data;
                            }
                        }
                    ]
                });

                console.log('DataTable with server-side processing initialized successfully');

                // Hide loading indicator when table is ready
                $('#loadingIndicator').addClass('hidden');

                // Initialize language
                updateLanguage();

            } catch (error) {
                console.error('DataTable initialization error:', error);
            }
        });

    </script>
</body>
</html>

