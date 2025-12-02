<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - PT Inspizo Multi Inspirasi</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
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

    /* Mobile Menu Drawer Styles (from cashier.blade.php) */
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
<body class="bg-gray-100">
    <!-- Mobile Top Header (Visible on small screens) -->
    <header id="mobile-top-header" class="fixed top-0 left-0 right-0 lg:hidden bg-white shadow-md z-40 px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo/Title Center -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('cashier.index') }}" class="p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Back to Cashier">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-800" data-en="Profile Settings" data-id="Pengaturan Profil">Profile Settings</h1>
            </div>
            <!-- Menu Toggle Right -->
            <button id="mobile-menu-btn" class="p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Open menu">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <header class="hidden lg:block bg-white shadow-sm p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('cashier.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Profil Pengguna</h1>
        </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-6">
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Dasar</h2>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" id="username" value="{{ $user->username }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                <p class="text-xs text-gray-500 mt-1">Username tidak dapat diubah</p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="photo_profile" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                <input type="file" id="photo_profile" name="photo_profile" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @if($user->photo_profile)
                                    <p class="text-xs text-gray-500 mt-1">Foto saat ini: {{ $user->photo_profile }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ubah Password</h2>
                    <form method="POST" action="{{ route('change-password.update') }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                                <input type="password" id="current_password" name="current_password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" id="password" name="password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">ID Pengguna</span>
                            <p class="text-gray-800">{{ $user->kd }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Dibuat Oleh</span>
                            <p class="text-gray-800">{{ $user->dibuat_oleh ?? 'System' }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Tanggal Dibuat</span>
                            <p class="text-gray-800">{{ $user->date_created ? $user->date_created->format('d M Y H:i') : 'N/A' }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Terakhir Diperbarui</span>
                            <p class="text-gray-800">{{ $user->date_updated ? $user->date_updated->format('d M Y H:i') : 'N/A' }}</p>
                        </div>
                </div>

                <!-- Security Tips -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">Tips Keamanan</h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li>• Gunakan password yang kuat dan unik</li>
                        <li>• Jangan bagikan kredensial login Anda</li>
                        <li>• Logout setelah selesai menggunakan sistem</li>
                        <li>• Laporkan aktivitas mencurigakan</li>
                    </ul>
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

</body>
</html>

    <script>
        // Language helper function
        function getText(enText, idText) {
            // Since this page does not use complex modals, this simple check is enough
            return localStorage.getItem('language') === 'en' ? enText : idText;
        }

        // Mobile Menu Drawer Functionality (Shared)
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

        // Language toggle functions
        function updateLanguage() {
            const elements = document.querySelectorAll('[data-en], [data-id]');
            const mobileLangButton = document.getElementById('mobile-current-lang');
            const currentLanguage = localStorage.getItem('language') || 'id';

            if (mobileLangButton) mobileLangButton.textContent = currentLanguage.toUpperCase();

            elements.forEach(element => {
                const translation = element.getAttribute(`data-${currentLanguage}`);
                if (translation) {
                    element.textContent = translation;
                }
            });
        }

        function toggleLanguageHandler() {
            let currentLanguage = localStorage.getItem('language') || 'id';
            currentLanguage = currentLanguage === 'en' ? 'id' : 'en';
            localStorage.setItem('language', currentLanguage);
            updateLanguage();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set initial language state
            updateLanguage();

            // Attach event listeners for mobile menu button
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', window.openMobileMenu);
            }

            // Attach event listener for mobile language toggle
            const mobileLangToggle = document.getElementById('mobile-language-toggle');
            if (mobileLangToggle) mobileLangToggle.addEventListener('click', toggleLanguageHandler);
        });

    </script>
