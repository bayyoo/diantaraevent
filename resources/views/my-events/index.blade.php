<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Events - {{ config('app.name', 'Diantara') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/images/logos/diantara.png') }}" sizes="any">
    <link rel="icon" href="{{ asset('storage/images/logos/diantara.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('storage/images/logos/diantara.png') }}">
    
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
        
        .event-card {
            transition: all 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
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
                        <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Pengaturan Akun</span>
                        </a>

                        <a href="{{ route('my-events.index') }}" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-lg">
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

                        <a href="{{ route('wishlist.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>Wishlist</span>
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
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Event Saya</h2>
                    <p class="text-gray-600 mt-1">Lihat semua event yang sudah kamu daftarkan</p>
                </div>

                @if($participants->count() > 0)
                    <div class="space-y-4">
                        @foreach($participants as $participant)
                            <div class="event-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="flex">
                                    <!-- Event Image -->
                                    <div class="w-48 h-48 flex-shrink-0">
                                        @if($participant->event->flyer_path)
                                            <img 
                                                src="{{ asset($participant->event->flyer_path) }}" 
                                                alt="{{ $participant->event->title }}" 
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <img 
                                                src="{{ asset('images/diantara-nexus-logo.png') }}" 
                                                alt="{{ $participant->event->title }}" 
                                                class="w-full h-full object-cover bg-gray-100"
                                            >
                                        @endif
                                    </div>

                                    <!-- Event Details -->
                                    <div class="flex-1 p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $participant->event->title }}</h3>
                                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ Str::limit($participant->event->description, 150) }}</p>
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            @if($participant->attended_at)
                                                <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Sudah Hadir
                                                </span>
                                            @else
                                                <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Terdaftar
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $participant->event->event_date->format('d M Y') }}
                                            </div>

                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $participant->event->event_time }}
                                            </div>

                                            <div class="flex items-center text-sm text-gray-600 col-span-2">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $participant->event->location }}
                                            </div>
                                        </div>

                                        <!-- Attendance Progress -->
                                        @php 
                                            $totalSessions = $participant->event->sessions->count();
                                            $attended = $attendanceCounts[$participant->event_id] ?? 0;
                                        @endphp
                                        <div class="mb-3">
                                            @if($totalSessions > 0)
                                                <div class="text-sm text-gray-700 mb-1">Progres Absensi: <strong>{{ $attended }}/{{ $totalSessions }}</strong></div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                    <div class="bg-green-500 h-2" style="width: {{ max(0, min(100, $totalSessions ? ($attended/$totalSessions*100) : 0)) }}%"></div>
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">Event tanpa sesi (absen sekali)</div>
                                            @endif
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('events.show', $participant->event) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Detail
                                            </a>

                                            <a href="{{ route('attendance.show', $participant->event) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"></path>
                                                </svg>
                                                Absensi / Input Token
                                            </a>

                                            @php $certificate = $certificatesByEvent[$participant->event_id] ?? null; @endphp
                                            @if($certificate)
                                                <a href="{{ route('certificates.download', $certificate->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Download Sertifikat
                                                </a>
                                            @endif

                                            <div class="flex-1"></div>
                                            
                                            <div class="text-xs text-gray-500">
                                                Token: <span class="font-mono font-semibold text-gray-700">{{ $participant->token }}</span>
                                            </div>
                                        </div>

                                        <!-- Testing: Session Check-in Buttons (dev) -->
                                        @if(app()->environment('local'))
                                            @if($totalSessions > 0)
                                                <div class="mt-3 text-xs text-gray-500">Testing Check-in Sesi:</div>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    @foreach($participant->event->sessions as $s)
                                                        <button type="button" class="px-3 py-1 border rounded text-sm" onclick="checkinSession({{ $participant->event->id }}, {{ $s->id }}, this)">Sesi {{ $s->order_index ?? $loop->iteration }}</button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Event Terdaftar</h3>
                        <p class="text-gray-600 mb-6">Kamu belum mendaftar event apapun. Yuk jelajahi event menarik!</p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Jelajahi Event
                        </a>
                    </div>
                @endif
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
