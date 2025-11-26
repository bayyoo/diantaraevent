<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - {{ config('app.name', 'Diantara') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/diantara_logo.png') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/diantara_logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/diantara_logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Montserrat', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '#7681FF',
                        'primary-dark': '#5A67D8',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', system-ui, sans-serif;
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background: #F3F4F6;
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background: #EEF2FF;
            color: #7681FF;
            font-weight: 600;
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(118, 129, 255, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.navigation')

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed top-28 right-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-lg z-40">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-28 right-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg z-40">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="fixed top-28 right-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg z-40 max-w-md">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-semibold">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8 pb-24">
        <div class="flex gap-8">
            <!-- Sidebar -->
            <div class="w-80 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- User Info Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 truncate">Hai, {{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 truncate">Atur akun kamu disini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="p-3">
                        <a href="{{ route('profile.edit') }}" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Pengaturan Akun</span>
                        </a>

                        <a href="{{ route('my-events.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            <span>Transaksi Event</span>
                        </a>

                        <a href="#" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span>Transaksi Atraksi</span>
                        </a>

                        <a href="{{ route('password.edit') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>Atur Kata Sandi</span>
                        </a>

                        <a href="{{ route('privacy.edit') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Privasi Akun</span>
                        </a>

                    </nav>

                    <!-- Logout Button -->
                    <div class="p-3 border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 w-full hover:bg-red-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Pengaturan Akun</h2>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Email Address -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                                placeholder="email@example.com"
                            >
                            <p class="text-xs text-gray-500 mt-1">Email Anda digunakan untuk login dan notifikasi</p>
                        </div>

                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                                placeholder="Masukkan nama lengkap"
                            >
                        </div>

                        <!-- Phone Number -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-gray-700">No. Handphone</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                                placeholder="08123456789"
                            >
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Alamat</label>
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3"
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                                placeholder="Masukkan alamat lengkap"
                            >{{ old('address', $user->address) }}</textarea>
                        </div>

                        <!-- Education -->
                        <div class="space-y-2">
                            <label for="education" class="block text-sm font-semibold text-gray-700">Pendidikan Terakhir</label>
                            <select 
                                id="education" 
                                name="education" 
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                            >
                                <option value="">Pilih pendidikan terakhir</option>
                                <option value="SD" {{ old('education', $user->education) == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('education', $user->education) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA/SMK" {{ old('education', $user->education) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3" {{ old('education', $user->education) == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ old('education', $user->education) == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('education', $user->education) == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('education', $user->education) == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div class="space-y-2">
                            <label for="birth_date" class="block text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                            <input 
                                type="date" 
                                id="birth_date" 
                                name="birth_date" 
                                value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                            >
                        </div>

                        <!-- Gender -->
                        <div class="space-y-2">
                            <label for="gender" class="block text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                            <select 
                                id="gender" 
                                name="gender" 
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                            >
                                <option value="">Pilih jenis kelamin</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Country -->
                        <div class="space-y-2">
                            <label for="country" class="block text-sm font-semibold text-gray-700">Negara</label>
                            <select 
                                id="country" 
                                name="country" 
                                class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50"
                            >
                                <option value="Indonesia" {{ old('country', $user->country) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                <option value="Malaysia" {{ old('country', $user->country) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                <option value="Singapore" {{ old('country', $user->country) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                <option value="Thailand" {{ old('country', $user->country) == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                <option value="Philippines" {{ old('country', $user->country) == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                            </select>
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                            <button 
                                type="button" 
                                onclick="window.location.reload()"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition-colors"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.fixed.top-28');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
