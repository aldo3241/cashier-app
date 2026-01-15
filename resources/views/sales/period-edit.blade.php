<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan Kasir - Spiritosanto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    
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
                        <h1 class="text-2xl font-bold text-gray-800" data-en="Edit Period Report" data-id="Edit Laporan Kasir">Edit Laporan Kasir</h1>
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
                    <a href="{{ route('sales.period') }}" class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span data-en="Back" data-id="Kembali">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('sales.period-update', $report->kd_laporan_kasir) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Mulai -->
                    <div>
                        <label for="mulai" class="block text-sm font-medium text-gray-700 mb-1" data-en="Start Date" data-id="Mulai">Mulai <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="mulai" name="mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 pl-3 pr-10 py-2" value="{{ old('mulai', $report->mulai ? \Carbon\Carbon::parse($report->mulai)->format('Y-m-d H:i:s') : '') }}" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Akhir -->
                    <div>
                        <label for="akhir" class="block text-sm font-medium text-gray-700 mb-1" data-en="End Date" data-id="Akhir">Akhir <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="akhir" name="akhir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 pl-3 pr-10 py-2" value="{{ old('akhir', $report->akhir ? \Carbon\Carbon::parse($report->akhir)->format('Y-m-d H:i:s') : '') }}" required>
                             <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('akhir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Koreksi Pemasukkan -->
                 <div class="mb-6">
                    <label for="koreksi_pemasukkan" class="block text-sm font-medium text-gray-700 mb-1" data-en="Income Correction" data-id="Koreksi Pemasukkan">Koreksi Pemasukkan <span class="text-red-500">*</span></label>
                    <input type="number" id="koreksi_pemasukkan" name="koreksi_pemasukkan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 p-2" value="{{ old('koreksi_pemasukkan', $report->koreksi_pemasukkan) }}" required>
                     @error('koreksi_pemasukkan')
                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                     @enderror
                </div>

                <!-- Koreksi Pengeluaran -->
                 <div class="mb-6">
                    <label for="koreksi_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1" data-en="Expense Correction" data-id="Koreksi Pengeluaran">Koreksi Pengeluaran <span class="text-red-500">*</span></label>
                    <input type="number" id="koreksi_pengeluaran" name="koreksi_pengeluaran" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 p-2" value="{{ old('koreksi_pengeluaran', $report->koreksi_pengeluaran) }}" required>
                     @error('koreksi_pengeluaran')
                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                     @enderror
                </div>

                <!-- Catatan -->
                <div class="mb-8">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1" data-en="Note" data-id="Catatan">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 p-2" placeholder="Enter Catatan">{{ old('catatan', $report->catatan) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-purple-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <span data-en="Update" data-id="Update">Update</span>
                        <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-sm text-gray-600">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p>&copy; 2022 Spirito Santo. All Rights Reserved</p>
            <div class="mt-4 md:mt-0 space-x-4">
               <a href="#" class="hover:text-purple-700">About Us</a> |
               <a href="#" class="hover:text-purple-700">Help And FAQ</a> |
               <a href="#" class="hover:text-purple-700">Contact Us</a> |
               <a href="#" class="hover:text-purple-700">Privacy Policy</a> |
               <a href="#" class="hover:text-purple-700">Terms And Conditions</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#mulai", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                time_24hr: true,
                allowInput: true
            });
            
            flatpickr("#akhir", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                time_24hr: true,
                allowInput: true
            });

             // Language switcher with persistence
            let currentLanguage = localStorage.getItem('language') || 'id';

            const desktopLangToggle = document.getElementById('language-toggle');

            function toggleLanguageHandler(e) {
                if(e) e.preventDefault();
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
                        if (element.tagName === 'INPUT' && element.type === 'submit') {
                            element.value = text;
                        } else {
                            element.textContent = text;
                        }
                    }
                });
            }

            // Initialize Language
            updateLanguage();
        });
    </script>
</body>
</html>
