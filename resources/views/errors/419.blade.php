<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - {{ config('app.name', 'Diantara') }}</title>
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
        body {
            font-family: 'Montserrat', system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Sesi Anda Telah Berakhir</h1>
            
            <!-- Description -->
            <p class="text-gray-600 mb-2">
                Halaman ini telah dibuka terlalu lama dan sesi Anda telah kedaluwarsa.
            </p>
            <p class="text-sm text-gray-500 mb-6">
                Untuk keamanan, sesi akan otomatis berakhir setelah <strong>5 menit</strong> tidak aktif.
            </p>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Tips untuk menghindari error ini:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Jangan biarkan halaman terbuka terlalu lama</li>
                            <li>Selesaikan form dalam waktu 5 menit</li>
                            <li>Refresh halaman jika sudah lama idle</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button 
                    onclick="window.location.reload()" 
                    class="w-full bg-primary text-white py-3 px-4 rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 font-medium"
                >
                    Muat Ulang Halaman
                </button>
                
                <a 
                    href="{{ url()->previous() }}" 
                    class="block w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200 font-medium"
                >
                    Kembali
                </a>
                
                <a 
                    href="{{ route('home') }}" 
                    class="block text-sm text-primary hover:text-primary-dark font-medium transition-colors"
                >
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Additional Help -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                Masih mengalami masalah? 
                <a href="#" class="text-primary hover:text-primary-dark font-medium">Hubungi Support</a>
            </p>
        </div>
    </div>

    <script>
        // Auto reload after 5 seconds
        setTimeout(() => {
            window.location.reload();
        }, 5000);
        
        // Show countdown
        let countdown = 5;
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    </script>

    @include('components.footer')
</body>
</html>
