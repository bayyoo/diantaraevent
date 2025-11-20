<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Partner Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#2563eb',
                        'primary-dark': '#1d4ed8',
                        'secondary': '#374151',
                    }
                }
            }
        }
    </script>
    <style>
        .btn-primary {
            background: #2563eb;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex">
        <!-- Left Side - Simple Illustration -->
        <div class="hidden lg:flex lg:w-1/2 bg-white items-center justify-center p-12">
            <div class="max-w-md text-center">
                <div class="mb-8">
                    <img src="{{ asset('images/diantara-nexus-logo.png') }}" alt="Diantara Nexus" class="h-28 mx-auto mb-6">
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-8 mb-8">
                    <!-- Event Management Illustration -->
                    <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center mb-6 relative overflow-hidden">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <defs>
                                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="1"/>
                                    </pattern>
                                </defs>
                                <rect width="100" height="100" fill="url(#grid)" />
                            </svg>
                        </div>
                        
                        <!-- Main Illustration -->
                        <div class="relative z-10 flex items-center space-x-6">
                            <!-- Dashboard mockup -->
                            <div class="bg-white rounded-lg shadow-lg p-4 w-32 h-24">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-3 h-3 bg-primary rounded-full"></div>
                                    <div class="flex space-x-1">
                                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="w-full h-2 bg-gray-200 rounded"></div>
                                    <div class="w-3/4 h-2 bg-gray-200 rounded"></div>
                                    <div class="w-1/2 h-2 bg-primary/30 rounded"></div>
                                </div>
                            </div>
                            
                            <!-- Arrow -->
                            <div class="text-primary">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                            
                            <!-- Event card mockup -->
                            <div class="bg-white rounded-lg shadow-lg p-3 w-28 h-20">
                                <div class="w-full h-3 bg-gradient-to-r from-primary to-blue-400 rounded mb-2"></div>
                                <div class="space-y-1">
                                    <div class="w-full h-1.5 bg-gray-200 rounded"></div>
                                    <div class="w-2/3 h-1.5 bg-gray-200 rounded"></div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="w-4 h-4 bg-green-100 rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    </div>
                                    <div class="text-xs text-gray-400">✓</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kelola Event Profesional</h3>
                    <h4 class="text-lg font-semibold text-gray-700 mb-3">Tak Pernah Semudah Ini</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Dari pembuatan event hingga manajemen tiket, kelola semua kebutuhan bisnis event Anda dalam satu platform terintegrasi.
                    </p>
                </div>
                
                <!-- Simple pagination dots -->
                <div class="flex justify-center space-x-2">
                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="max-w-sm w-full">
                <!-- Logo for mobile -->
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/diantara-nexus-logo.png') }}" alt="Diantara Nexus" class="h-18 mx-auto">
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Login Partner Nexus</h2>
                    <p class="text-gray-600">Masukkan alamat email Anda</p>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('diantaranexus.login.submit') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Masukkan alamat email Anda"
                               required 
                               autocomplete="email"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none @error('email') border-red-500 @enderror">
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Password Anda"
                               required 
                               autocomplete="current-password"
                               class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none @error('password') border-red-500 @enderror">
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                        <a href="{{ route('diantaranexus.password.request') }}" class="text-sm text-primary hover:text-primary-dark">Lupa Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full btn-primary text-white py-3 px-4 rounded-lg font-medium">
                        LOGIN
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-sm text-gray-500">atau login dengan</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-sm font-medium">GOOGLE</span>
                    </button>
                    <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="text-sm font-medium">APPLE</span>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 text-sm">
                        Belum terdaftar sebagai Partner Nexus? 
                        <a href="{{ route('diantaranexus.register') }}" class="text-primary hover:text-primary-dark font-medium">
                            Daftar
                        </a>
                    </p>
                    <p class="text-gray-500 text-sm mt-2">
                        Punya Pertanyaan? 
                        <a href="#" class="text-primary hover:text-primary-dark font-medium">
                            Hubungi CS Nexus
                        </a>
                    </p>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                        <img src="{{ asset('images/diantara-nexus-logo.png') }}" alt="Nexus" class="h-8">
                        <span>Portal Mitra Bisnis Terpercaya</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
