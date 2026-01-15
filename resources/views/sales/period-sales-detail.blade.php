<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Kasir #{{ $report->kd_laporan_kasir }} - Spiritosanto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
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
                        <h1 class="text-2xl font-bold text-gray-800" data-en="Rincian Laporan Kasir" data-id="Rincian Laporan Kasir">Rincian Laporan Kasir</h1>
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
                    <a href="{{ route('sales.period') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span data-en="Back" data-id="Kembali">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[95%] mx-auto px-4 py-8"> <!-- Using wider container like screenshot -->
        
        <!-- Info Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="grid grid-cols-[140px_1fr] gap-2 text-sm text-gray-700">
                        <div class="font-medium">No. Laporan:</div>
                        <div>#LAP{{ $report->kd_laporan_kasir }}</div>
                        
                        <div class="font-medium">Mulai:</div>
                        <div>{{ \Carbon\Carbon::parse($report->mulai)->format('Y-m-d H:i:s') }}</div>
                        
                        <div class="font-medium">Akhir:</div>
                        <div>{{ \Carbon\Carbon::parse($report->akhir)->format('Y-m-d H:i:s') }}</div>
                        
                        <div class="font-medium">Dibuat oleh:</div>
                        <div>{{ $user->username ?? 'System' }}</div> <!-- Placeholder as columns missing in model -->
                        
                        <div class="font-medium">Tgl Laporan:</div>
                        <div>{{ now()->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Boxes Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Kotak Keuangan -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2 uppercase">Kotak Keuangan</h3>
                <p class="text-xs text-gray-500 mb-4 font-mono">/ lapkekotak.kd_laporan_kasir / {{ $report->kd_laporan_kasir }}</p>
                
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="py-2 px-2 font-semibold">Kotak</th>
                            <th class="py-2 px-2 font-semibold text-right">Masuk</th>
                            <th class="py-2 px-2 font-semibold text-right">Keluar</th>
                            <th class="py-2 px-2 font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $totalMasuk = 0; $totalKeluar = 0; $totalTotal = 0; @endphp
                        @foreach($kotakStats as $stat)
                        @php 
                            $total = $stat->masuk - $stat->keluar; 
                            $totalMasuk += $stat->masuk;
                            $totalKeluar += $stat->keluar;
                            $totalTotal += $total;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-2">{{ $stat->keuangan_kotak }}</td>
                            <td class="py-2 px-2 text-right">{{ number_format($stat->masuk, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-right">{{ number_format($stat->keluar, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-right font-medium">{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold border-t">
                         <tr>
                            <td class="py-3 px-2"></td>
                            <td class="py-3 px-2 text-right">
                                <div class="text-xs text-gray-500">Total Masuk =</div>
                                {{ number_format($totalMasuk, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-2 text-right">
                                <div class="text-xs text-gray-500">Total Keluar =</div>
                                {{ number_format($totalKeluar, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-2 text-right">
                                <div class="text-xs text-gray-500">Total Total =</div>
                                {{ number_format($totalTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Kategori Pembayaran -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2 uppercase">Kategori Pembayaran</h3>
                <p class="text-xs text-gray-500 mb-4 font-mono">/ lapkekategori.kd_laporan_kasir / {{ $report->kd_laporan_kasir }}</p>
                
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="py-2 px-2 font-semibold">Kategori</th>
                            <th class="py-2 px-2 font-semibold text-right">Masuk</th>
                            <th class="py-2 px-2 font-semibold text-right">Keluar</th>
                            <th class="py-2 px-2 font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $catTotalMasuk = 0; $catTotalKeluar = 0; $catTotalTotal = 0; @endphp
                        @foreach($kategoriStats as $stat)
                        @php 
                            $total = $stat->masuk - $stat->keluar; 
                            $catTotalMasuk += $stat->masuk;
                            $catTotalKeluar += $stat->keluar;
                            $catTotalTotal += $total;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-2">{{ $stat->keuangan_kategori }}</td>
                            <td class="py-2 px-2 text-right">{{ number_format($stat->masuk, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-right">{{ number_format($stat->keluar, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-right font-medium">{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold border-t">
                         <tr>
                            <td class="py-3 px-2"></td>
                            <td class="py-3 px-2 text-right">
                                <div class="text-xs text-gray-500">Total Masuk =</div>
                                {{ number_format($catTotalMasuk, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-2 text-right">
                                <div class="text-xs text-gray-500">Total Keluar =</div>
                                {{ number_format($catTotalKeluar, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-2 text-right">
                                <div class="text-xs text-gray-500">Total Total =</div>
                                {{ number_format($catTotalTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <!-- Mutasi Keuangan -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-medium text-gray-700 mb-2">Mutasi Keuangan</h3>
            <p class="text-xs text-gray-500 mb-4 font-mono">/ lapkemutasi.kd_laporan_kasir / {{ $report->kd_laporan_kasir }}</p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-gray-600">Date Created</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Masuk</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Keluar</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Kotak</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Kategori</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Referensi</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Catatan</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($mutasiKeuangan as $mutasi)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $mutasi->date_created->format('Y-m-d H:i:s') }}</td>
                            <td class="py-3 px-4">{{ number_format($mutasi->masuk, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ number_format($mutasi->keluar, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ $mutasi->keuangan_kotak }}</td>
                            <td class="py-3 px-4">{{ $mutasi->keuangan_kategori }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $mutasi->referensi }}</td>
                            <td class="py-3 px-4">{{ $mutasi->catatan }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $mutasi->dibuat_oleh }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($mutasiKeuangan->isEmpty())
                    <div class="p-4 text-center text-gray-500">No data available</div>
                @endif
            </div>
            
            <div class="mt-4 pt-4 border-t flex justify-between font-bold text-sm">
                 <div></div>
                 <div class="flex space-x-8">
                     <div>Total Masuk = <span class="text-gray-900">{{ number_format($mutasiKeuangan->sum('masuk'), 0, ',', '.') }}</span></div>
                     <div>Total Keluar = <span class="text-gray-900">{{ number_format($mutasiKeuangan->sum('keluar'), 0, ',', '.') }}</span></div>
                 </div>
            </div>
        </div>

        <!-- Mutasi Penjualan Detail -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-medium text-gray-700 mb-2">Mutasi Penjualan Detail</h3>
            <p class="text-xs text-gray-500 mb-4 font-mono">/ lapkepenjdet.kd_laporan_kasir / {{ $report->kd_laporan_kasir }}</p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap" id="salesDetailTable">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-gray-600">Date Created</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Faktur</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Produk</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Qty</th>
                            <th class="py-3 px-4 font-semibold text-gray-600 text-right">Hpp</th>
                            <th class="py-3 px-4 font-semibold text-gray-600 text-right">Harga Jual</th>
                            <th class="py-3 px-4 font-semibold text-gray-600 text-right">Diskon</th>
                            <th class="py-3 px-4 font-semibold text-gray-600 text-right">Sub Total</th>
                            <th class="py-3 px-4 font-semibold text-gray-600 text-right">Laba</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Status Bayar</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Catatan</th>
                            <th class="py-3 px-4 font-semibold text-gray-600">Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php 
                            $totalQty = 0; 
                            $totalSubTotal = 0; 
                            $totalProfit = 0; 
                        @endphp
                        @foreach($salesDetails as $detail)
                        @php
                            $totalQty += $detail->qty;
                            $totalSubTotal += $detail->sub_total;
                            $totalProfit += $detail->laba;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">
                                {{ $detail->date_created->format('Y-m-d') }}<br>
                                <span class="text-xs text-gray-500">{{ $detail->date_created->format('H:i:s') }}</span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $detail->no_faktur_penjualan }}</td>
                            <td class="py-3 px-4 font-medium">{{ $detail->nama_produk }}</td>
                            <td class="py-3 px-4 text-center">{{ $detail->qty }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($detail->hpp, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($detail->diskon, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($detail->laba, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ $detail->status_bayar }}</td>
                            <td class="py-3 px-4">{{ $detail->catatan }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $detail->dibuat_oleh }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t font-bold">
                        <tr>
                            <td colspan="3"></td>
                            <td class="py-4 px-4 text-center">
                                <div class="text-xs text-gray-500">Total Qty =</div>
                                {{ $totalQty }}
                            </td>
                            <td colspan="3"></td>
                            <td class="py-4 px-4 text-right">
                                <div class="text-xs text-gray-500">Total Sub Total =</div>
                                {{ number_format($totalSubTotal, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="text-xs text-gray-500">Total Pendapatan Kotor =</div>
                                {{ number_format($totalProfit, 0, ',', '.') }}
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Pagination / Load more could go here -->
        </div>
        
        <!-- Footer Info -->
        <div class="mt-8 text-sm text-gray-500">
             &copy; 2022 Spiritosanto. All Rights Reserved
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        // Language switcher with persistence
        let currentLanguage = localStorage.getItem('language') || 'id';

        const desktopLangToggle = document.getElementById('language-toggle');

        function toggleLanguageHandler() {
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
            localStorage.setItem('language', currentLanguage);
            updateLanguage();
        }

        if (desktopLangToggle) desktopLangToggle.addEventListener('click', toggleLanguageHandler);

        function updateLanguage() {
            const langButton = document.getElementById('current-lang');
            const elements = document.querySelectorAll('[data-en], [data-id]');

            if (langButton) langButton.textContent = currentLanguage.toUpperCase();

            elements.forEach(element => {
                if (element.hasAttribute(`data-${currentLanguage}`)) {
                    const text = element.getAttribute(`data-${currentLanguage}`);
                     // Handle button text within spans if needed, or direct text content
                    // For simple elements like h1 or spans
                     if (element.tagName === 'INPUT' && element.type === 'submit') {
                        element.value = text;
                     } else {
                        element.textContent = text;
                     }
                }
            });
            
            // Re-initialize DataTable if needed or simple redraw, but here static content is main focus
        }

        $(document).ready(function() {
            // Optional: convert tables to DataTables if sorting/filtering needed
            /*
            $('#salesDetailTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });
            */
            
            updateLanguage();
        });
    </script>
</body>
</html>
