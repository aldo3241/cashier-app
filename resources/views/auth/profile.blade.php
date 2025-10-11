<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - FreshFood POS</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
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
                        
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email Terverifikasi</span>
                            <p class="text-gray-800">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">✓ Ya ({{ $user->email_verified_at->format('d M Y') }})</span>
                                @else
                                    <span class="text-red-600">✗ Belum</span>
                                @endif
                            </p>
                        </div>
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
</body>
</html>
