<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Diantara</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7681FF',
                        'primary-dark': '#5A67D8',
                        purple: {
                            500: '#9D4EDD',
                            600: '#7C3AED',
                        }
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .animate-slide-down {
            animation: slideDown 0.6s ease-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out;
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #7681FF;
            position: absolute;
            animation: confetti-fall 3s linear infinite;
        }
        
        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 min-h-screen">
    
    <!-- Confetti Effect -->
    <div id="confetti-container" class="fixed inset-0 pointer-events-none z-50"></div>
    
    <div class="max-w-7xl mx-auto px-6 py-4 max-w-4xl">
        
        <!-- Success Icon -->
        <div class="text-center mb-8 animate-scale-in">
            <div class="inline-block bg-gradient-to-br from-green-400 to-green-600 rounded-full p-6 shadow-2xl">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        
        <!-- Success Message -->
        <div class="text-center mb-8 animate-slide-down">
            <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-4">
                🎉 Pendaftaran Berhasil!
            </h1>
            <p class="text-xl text-gray-700 font-medium">
                Selamat! Anda telah terdaftar untuk event ini
            </p>
        </div>
        
        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-8 animate-fade-in">
            
            <!-- Event Info Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">{{ $event->title }}</h2>
                        <div class="flex items-center space-x-4 text-sm opacity-90">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $event->event_date->format('d M Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $event->event_time ? $event->event_time->format('H:i') : '00:00' }} WIB
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-bold">
                            TERDAFTAR ✓
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Participant Info -->
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Peserta
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="font-bold text-gray-800">{{ $participant->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="font-bold text-gray-800">{{ $participant->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Token Section -->
            <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Token Absensi Anda
                </h3>
                
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-8 text-center shadow-xl">
                    <p class="text-white text-sm mb-3 opacity-90">Simpan token ini untuk absensi</p>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 mb-4">
                        <p class="text-5xl font-black text-white tracking-widest font-mono" id="token">
                            {{ $participant->token }}
                        </p>
                    </div>
                    <button onclick="copyToken()" class="bg-white text-purple-600 px-6 py-2 rounded-full font-bold hover:bg-purple-50 transition-all duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span id="copy-text">Salin Token</span>
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50">
                <div class="grid md:grid-cols-2 gap-4">
                    @if($participant->ticket_path)
                    <a href="{{ route('ticket.download', $participant->id) }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl font-bold hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download E-Ticket
                    </a>
                    @endif
                    
                    <a href="{{ route('events.show', $event->id) }}" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali ke Event
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Important Info -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-2xl mb-8 animate-fade-in">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h4 class="font-bold text-yellow-800 mb-2">Penting untuk Diperhatikan:</h4>
                    <ul class="text-sm text-yellow-700 space-y-2">
                        <li class="flex items-start">
                            <span class="mr-2">✓</span>
                            <span>Token absensi telah dikirim ke email <strong>{{ $participant->email }}</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">✓</span>
                            <span>E-Ticket PDF sudah tersedia untuk didownload</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">✓</span>
                            <span>Absensi hanya dapat dilakukan pada <strong>hari H setelah event dimulai</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">✓</span>
                            <span>Sertifikat akan otomatis diterbitkan setelah Anda melakukan absensi</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">✓</span>
                            <span>Jangan bagikan token kepada orang lain</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Next Steps -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Langkah Selanjutnya
            </h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-purple-50 rounded-xl">
                    <div class="bg-purple-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-xl">1</div>
                    <h4 class="font-bold text-gray-800 mb-2">Cek Email</h4>
                    <p class="text-sm text-gray-600">Token absensi sudah dikirim ke email Anda</p>
                </div>
                <div class="text-center p-4 bg-pink-50 rounded-xl">
                    <div class="bg-pink-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-xl">2</div>
                    <h4 class="font-bold text-gray-800 mb-2">Download E-Ticket</h4>
                    <p class="text-sm text-gray-600">Simpan atau print e-ticket untuk check-in</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-xl">3</div>
                    <h4 class="font-bold text-gray-800 mb-2">Absensi di Hari H</h4>
                    <p class="text-sm text-gray-600">Gunakan token untuk absensi dan dapatkan sertifikat</p>
                </div>
            </div>
        </div>
        
        <!-- Footer Actions -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
    
    <script>
        // Copy token function
        function copyToken() {
            const token = document.getElementById('token').textContent.trim();
            navigator.clipboard.writeText(token).then(() => {
                const btn = document.getElementById('copy-text');
                btn.textContent = 'Tersalin! ✓';
                setTimeout(() => {
                    btn.textContent = 'Salin Token';
                }, 2000);
            });
        }
        
        // Confetti animation
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            const colors = ['#7681FF', '#9D4EDD', '#FF6B9D', '#FFD700', '#90EE90'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 3 + 's';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    container.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 5000);
                }, i * 30);
            }
        }
        
        // Run confetti on load
        window.addEventListener('load', createConfetti);
    </script>

    @include('components.footer')
</body>
</html>
