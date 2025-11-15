<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Diantara') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7681FF',
                        'primary-dark': '#5A67D8',
                    }
                }
            }
        }
    </script>
    <style>
        html {
            font-size: 14px;
        }
        body {
            font-family: 'Montserrat', system-ui, sans-serif;
            font-size: 14px;
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(118, 129, 255, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Main Auth Card -->
    <div class="pt-6 pb-8 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                
                <!-- Header -->
                <div class="text-center mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1.5">Halo!</h1>
                    <p class="text-gray-600 text-xs">Silakan masukkan email Anda untuk masuk atau mendaftar.</p>
                </div>

                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded-xl mb-4 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded-xl mb-4">
                        <div class="flex items-center mb-1.5">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-sm">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-medium text-gray-700">Alamat Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="input-focus w-full px-3 py-2 text-sm border-0 border-b-2 border-gray-200 focus:outline-none focus:border-primary bg-transparent"
                            placeholder="Masukkan email Anda"
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-medium text-gray-700">Kata Sandi</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="input-focus w-full px-3 py-2 text-sm border-0 border-b-2 border-gray-200 focus:outline-none focus:border-primary bg-transparent"
                            placeholder="Masukkan kata sandi Anda"
                        >
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-right">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-primary hover:text-primary-dark font-medium transition-colors">
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-primary text-white py-2.5 px-4 text-sm rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 font-medium"
                    >
                        Masuk
                    </button>
                </form>

                <!-- Separator -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <!-- Social Login Options -->
                <div class="space-y-2.5">
                    <!-- Google Login -->
                    <a 
                        href="{{ route('auth.google') }}"
                        class="w-full flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200"
                    >
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Lanjutkan dengan Google
                    </a>

                    <!-- Apple Login -->
                    <button 
                        type="button"
                        class="w-full flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-gray-900 text-xs font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        Lanjutkan dengan Apple
                    </button>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mt-4">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember"
                        class="h-3.5 w-3.5 text-primary focus:ring-primary border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-xs text-gray-500">
                        Ingat saya
                    </label>
                </div>

                <!-- Help Section -->
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500 mb-3">
                        Jika terjadi masalah, silakan kunjungi pusat bantuan kami
                    </p>
                    <button 
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-primary bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200"
                    >
                        Pusat Bantuan
                    </button>
                </div>

                <!-- Sign Up Link -->
                <div class="text-center mt-4">
                    <p class="text-gray-600 text-xs">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-primary hover:text-primary-dark font-medium transition-colors">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')
    
    <!-- CSRF Token Auto-Refresh Script -->
    <script src="{{ asset('js/csrf-refresh.js') }}"></script>
</body>
</html>
