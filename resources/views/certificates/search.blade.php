<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Sertifikat - {{ config('app.name', 'Diantara') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.navigation')

    <!-- Main Content -->
    <div class="pt-28 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-full mb-6">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Cari Sertifikat</h1>
                <p class="text-lg text-gray-600">Masukkan nama atau email untuk mencari sertifikat kehadiran Anda</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <form action="{{ route('certificate.search') }}" method="GET" class="space-y-6">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-primary"></i>
                            Nama Lengkap atau Email
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            value="{{ request('search') }}"
                            placeholder="Contoh: John Doe atau john@example.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            required
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Cari Sertifikat
                    </button>
                </form>
            </div>

            <!-- Flash Messages -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                </div>
            @endif>

            <!-- Search Results -->
            @if(isset($certificates) && $certificates->count() > 0)
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-certificate text-primary mr-2"></i>
                        Sertifikat Ditemukan ({{ $certificates->count() }})
                    </h2>

                    @foreach($certificates as $participant)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                                        {{ $participant->event->title }}
                                    </h3>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-user w-5 text-primary"></i>
                                            <span class="ml-2">{{ $participant->user->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope w-5 text-primary"></i>
                                            <span class="ml-2">{{ $participant->user->email }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar w-5 text-primary"></i>
                                            <span class="ml-2">{{ $participant->event->event_date->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-check-circle w-5 text-green-600"></i>
                                            <span class="ml-2 text-green-600 font-semibold">Sudah Hadir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-6 flex gap-2">
                                    <a 
                                        href="{{ route('certificate.view', $participant) }}" 
                                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors"
                                        target="_blank"
                                        title="Lihat Sertifikat"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Lihat
                                    </a>
                                    <a 
                                        href="{{ route('certificate.generate', $participant) }}" 
                                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-colors"
                                        title="Unduh Sertifikat"
                                    >
                                        <i class="fas fa-download mr-2"></i>
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(request('search'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
                    <i class="fas fa-search text-yellow-600 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sertifikat Tidak Ditemukan</h3>
                    <p class="text-gray-600">
                        Tidak ada sertifikat yang ditemukan untuk "<strong>{{ request('search') }}</strong>".
                        <br>Pastikan Anda sudah menghadiri event dan nama/email yang dimasukkan benar.
                    </p>
                </div>
            @endif

            <!-- Info Box -->
            <div class="mt-12 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-bold text-blue-900 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi
                </h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                        <span>Sertifikat hanya tersedia untuk peserta yang sudah melakukan absensi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                        <span>Gunakan nama lengkap atau email yang Anda gunakan saat mendaftar</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                        <span>Sertifikat akan otomatis tersedia setelah Anda melakukan absensi</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('components.footer')

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.bg-red-50, .bg-blue-50, .bg-green-50');
            flashMessages.forEach(msg => {
                if (msg.querySelector('.fas')) {
                    msg.style.opacity = '0';
                    msg.style.transition = 'opacity 0.3s';
                    setTimeout(() => msg.remove(), 300);
                }
            });
        }, 5000);
    </script>
</body>
</html>
