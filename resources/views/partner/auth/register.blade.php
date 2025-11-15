<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Partner Nexus</title>
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
                    <!-- Event Creation Illustration -->
                    <div class="w-full h-64 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl flex items-center justify-center mb-6 relative overflow-hidden">
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
                        
                        <!-- Main Illustration - Event Creation Scene -->
                        <div class="relative z-10 flex flex-col items-center">
                            <!-- Person with laptop -->
                            <div class="bg-white rounded-2xl shadow-xl p-6 mb-4 w-48 h-32 flex items-center justify-center">
                                <div class="flex items-center space-x-4">
                                    <!-- Person -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-2">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                    </div>
                                    
                                    <!-- Laptop -->
                                    <div class="bg-gray-100 rounded-lg p-2 w-16 h-10 flex items-center justify-center">
                                        <div class="bg-primary w-10 h-6 rounded flex items-center justify-center">
                                            <div class="w-6 h-3 bg-white rounded opacity-80"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Arrow pointing to event -->
                            <div class="text-primary mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>
                            
                            <!-- Event card result -->
                            <div class="bg-white rounded-xl shadow-lg p-4 w-40 h-24">
                                <div class="bg-gradient-to-r from-primary to-primary-dark h-3 rounded mb-2"></div>
                                <div class="space-y-1">
                                    <div class="w-full h-2 bg-gray-200 rounded"></div>
                                    <div class="w-3/4 h-2 bg-gray-200 rounded"></div>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <div class="text-primary">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Bergabung Sebagai Mitra</h3>
                    <h4 class="text-lg font-semibold text-gray-700 mb-3">Tak Pernah Semudah Ini</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Daftarkan organisasi Anda dan mulai mengelola event dengan platform terpercaya yang digunakan ribuan mitra.
                    </p>
                </div>
                
                <!-- Simple pagination dots -->
                <div class="flex justify-center space-x-2">
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="max-w-sm w-full">
                <!-- Logo for mobile -->
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/diantara-nexus-logo.png') }}" alt="Diantara Nexus" class="h-18 mx-auto">
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Daftar Partner Nexus</h2>
                    <p class="text-gray-600">Lengkapi data diri Anda</p>
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
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('diantaranexus.register.submit') }}" class="space-y-4">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Nama lengkap Anda"
                               required 
                               class="form-input w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none @error('name') border-red-500 @enderror">
                    </div>

                    <!-- Email -->
                    <div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Masukkan alamat email Anda"
                               required 
                               class="form-input w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none @error('email') border-red-500 @enderror">
                    </div>

                    <!-- Phone -->
                    <div class="flex space-x-2">
                        <select class="form-input px-2 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none bg-white w-16">
                            <option>+62</option>
                        </select>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="Nomor telepon Anda"
                               required 
                               class="form-input flex-1 px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none @error('phone') border-red-500 @enderror">
                    </div>

                    <!-- Organization Name -->
                    <div>
                        <input type="text" 
                               id="organization_name" 
                               name="organization_name" 
                               value="{{ old('organization_name') }}"
                               placeholder="Nama organisasi/perusahaan"
                               required 
                               class="form-input w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none @error('organization_name') border-red-500 @enderror">
                    </div>

                    <!-- Address -->
                    <div>
                        <textarea id="address" 
                                  name="address" 
                                  rows="2"
                                  placeholder="Alamat lengkap"
                                  required 
                                  class="form-input w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none resize-none @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Password Anda"
                               required 
                               class="form-input w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none @error('password') border-red-500 @enderror">
                        <button type="button" class="absolute right-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Konfirmasi Password"
                               required 
                               class="form-input w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none @error('password_confirmation') border-red-500 @enderror">
                        <button type="button" class="absolute right-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <label class="flex items-start space-x-3">
                            <input type="checkbox" 
                                   id="agree_terms" 
                                   name="agree_terms" 
                                   value="1"
                                   required
                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-1">
                            <span class="text-sm text-gray-700">
                                Dengan mendaftar saya setuju dengan 
                                <a href="#" class="text-primary hover:text-primary-dark font-medium">Syarat dan Ketentuan Nexus Experience Manager</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-primary-dark text-white py-2.5 px-4 rounded-lg font-medium text-sm transition-colors">
                        DAFTAR
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-sm text-gray-500">atau daftar dengan</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="flex items-center justify-center px-3 py-2 text-xs border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="font-medium">GOOGLE</span>
                    </button>
                    <button type="button" class="flex items-center justify-center px-3 py-2 text-xs border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="font-medium">APPLE</span>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 text-sm">
                        Sudah menjadi Partner Nexus? 
                        <a href="{{ route('diantaranexus.login') }}" class="text-primary hover:text-primary-dark font-medium">
                            Login
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
