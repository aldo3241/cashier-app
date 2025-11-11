<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaction Details - Inspizo Spiritosanto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        /* Custom styles for transaction details page */
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800" data-en="Transaction Details" data-id="Detail Transaksi">Transaction Details</h1>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button onclick="history.back()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900" data-en="Transaction Details" data-id="Detail Transaksi">Transaction Details</h1>
                            <p class="text-sm text-gray-600" data-en="Invoice" data-id="Faktur">Invoice: {{ $transaction->no_faktur_penjualan }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $transaction->status_bayar == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $transaction->status_bayar }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Transaction Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900" data-en="Transaction Summary" data-id="Ringkasan Transaksi">Transaction Summary</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500" data-en="Customer" data-id="Pelanggan">Customer</label>
                                <p class="text-lg text-gray-900">{{ $transaction->pelanggan->nama_lengkap ?? 'NONAME' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500" data-en="Date" data-id="Tanggal">Date</label>
                                <p class="text-lg text-gray-900">{{ \Carbon\Carbon::parse($transaction->date_created)->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500" data-en="Sub Total" data-id="Sub Total">Sub Total</label>
                                <p class="text-lg text-gray-900">Rp {{ number_format($transaction->sub_total, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500" data-en="Tax" data-id="Pajak">Tax</label>
                                <p class="text-lg text-gray-900">Rp {{ number_format($transaction->pajak, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500" data-en="Total Amount" data-id="Total Harga">Total Amount</label>
                                <p class="text-lg text-gray-900">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500" data-en="Amount Paid" data-id="Total Bayar">Amount Paid</label>
                                <p class="text-lg text-gray-900">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900" data-en="Quick Actions" data-id="Aksi Cepat">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($transaction->status_bayar == 'Belum Lunas')
                            <a href="{{ route('cashier.index') }}?continue={{ $transaction->kd_penjualan }}"
                               class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 p-3 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span data-en="Continue Transaction" data-id="Lanjutkan Transaksi">Continue Transaction</span>
                            </a>
                        @endif
                        <a href="{{ route('sales.my-sales') }}"
                           class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 p-3 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span data-en="Back to My Sales" data-id="Kembali ke Penjualan Saya">Back to My Sales</span>
                        </a>
                        <a href="{{ route('receipt.print', $transaction->kd_penjualan) }}" target="_blank"
                           class="w-full bg-green-100 hover:bg-green-200 text-green-800 p-3 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <span data-en="Print Receipt" data-id="Cetak Struk">Print Receipt</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mutations Overview -->
        <div class="space-y-6">
            <!-- Financial Mutation Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900" data-en="Financial Mutation" data-id="Mutasi Keuangan">Financial Mutation</h3>
                    </div>
                </div>
                <div class="p-4">
                    @if($keuangan)
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-green-600" data-en="Income" data-id="Pemasukan">Income</p>
                                        <p class="text-lg font-bold text-green-900">Rp {{ number_format($keuangan->masuk, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-red-600" data-en="Expense" data-id="Pengeluaran">Expense</p>
                                        <p class="text-lg font-bold text-red-900">Rp {{ number_format($keuangan->keluar, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($keuangan->deskripsi)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <h4 class="font-medium text-gray-900 text-sm mb-1" data-en="Description" data-id="Deskripsi">Description</h4>
                                    <p class="text-sm text-gray-700">{{ $keuangan->deskripsi }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500" data-en="No financial mutation found" data-id="Tidak ada mutasi keuangan ditemukan">No financial mutation found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stock Mutation Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900" data-en="Stock Mutation" data-id="Mutasi Stok">Stock Mutation</h3>
                    </div>
                </div>
                <div class="p-4">
                    @if($stokMutations->count() > 0)
                        <div class="grid grid-cols-1 {{ $stokMutations->count() >= 3 ? 'md:grid-cols-2 lg:grid-cols-3' : 'md:grid-cols-1' }} gap-3">
                            @foreach($stokMutations as $mutation)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    @if($stokMutations->count() >= 3)
                                        <!-- Vertical layout for 3+ items -->
                                        <div class="space-y-2">
                                            <h4 class="font-medium text-gray-900 text-sm truncate" title="{{ $mutation->produk->nama_produk ?? 'Unknown Product' }}">{{ $mutation->produk->nama_produk ?? 'Unknown Product' }}</h4>

                                            <div class="space-y-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500" data-en="Ref" data-id="Ref">Ref:</span>
                                                    <span class="text-xs text-gray-600 font-medium">{{ $mutation->no_ref }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500" data-en="Class" data-id="Klas">Class:</span>
                                                    <span class="text-xs text-gray-600">{{ $mutation->klasifikasi }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Date:</span>
                                                    <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($mutation->date_created)->format('d M H:i') }}</span>
                                                </div>
                                            </div>

                                            <div class="flex justify-center space-x-4 pt-2 border-t border-gray-200">
                                                @if($mutation->masuk > 0)
                                                    <div class="text-green-600 text-center">
                                                        <div class="text-xs" data-en="In" data-id="Masuk">In</div>
                                                        <div class="font-bold text-sm">{{ $mutation->masuk }}</div>
                                                    </div>
                                                @endif
                                                @if($mutation->keluar > 0)
                                                    <div class="text-red-600 text-center">
                                                        <div class="text-xs" data-en="Out" data-id="Keluar">Out</div>
                                                        <div class="font-bold text-sm">{{ $mutation->keluar }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Horizontal layout for 1-2 items -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-gray-900 text-sm truncate" title="{{ $mutation->produk->nama_produk ?? 'Unknown Product' }}">{{ $mutation->produk->nama_produk ?? 'Unknown Product' }}</h4>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span class="text-xs text-gray-500" data-en="Ref" data-id="Ref">Ref: {{ $mutation->no_ref }}</span>
                                                    <span class="text-xs text-gray-500" data-en="Class" data-id="Klas">Class: {{ $mutation->klasifikasi }}</span>
                                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($mutation->date_created)->format('d M H:i') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3 ml-4">
                                                @if($mutation->masuk > 0)
                                                    <div class="text-green-600 text-sm">
                                                        <span class="text-xs" data-en="In" data-id="Masuk">In:</span>
                                                        <span class="font-bold">{{ $mutation->masuk }}</span>
                                                    </div>
                                                @endif
                                                @if($mutation->keluar > 0)
                                                    <div class="text-red-600 text-sm">
                                                        <span class="text-xs" data-en="Out" data-id="Keluar">Out:</span>
                                                        <span class="font-bold">{{ $mutation->keluar }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($mutation->catatan)
                                        <div class="mt-2 p-2 bg-white rounded border-l-2 border-blue-200">
                                            <p class="text-xs text-gray-700">{{ $mutation->catatan }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-gray-500" data-en="No stock mutation found" data-id="Tidak ada mutasi stok ditemukan">No stock mutation found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Language switching functionality with persistence
    let currentLanguage = localStorage.getItem('language') || 'id'; // Default to Indonesian

    function updateLanguage() {
        const elements = document.querySelectorAll('[data-en][data-id]');

        elements.forEach(element => {
            const text = currentLanguage === 'id' ? element.getAttribute('data-id') : element.getAttribute('data-en');
            element.textContent = text;
        });

        // Update document language attribute
        document.documentElement.lang = currentLanguage;

        // Update language button
        const currentLang = document.getElementById('current-lang');
        if (currentLang) {
            currentLang.textContent = currentLanguage.toUpperCase();
        }
    }

    // Initialize language on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial language
        updateLanguage();

        // Language toggle functionality
        const languageToggle = document.getElementById('language-toggle');

        if (languageToggle) {
            languageToggle.addEventListener('click', function() {
                currentLanguage = currentLanguage === 'en' ? 'id' : 'en';

                // Save to localStorage
                localStorage.setItem('language', currentLanguage);

                // Update display
                updateLanguage();
            });
        }
    });
</script>
</body>
</html>
