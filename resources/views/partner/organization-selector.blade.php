<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Organisasi - Diantara Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .organization-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .organization-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #2563eb;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <img src="{{ asset('images/diantara-nexus-logo.png') }}" 
                     alt="Diantara Nexus" 
                     class="h-10 w-auto object-contain">
                
                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600">
                        Welcome, {{ Auth::guard('partner')->user()->name }}
                    </div>
                    <form method="POST" action="{{ route('diantaranexus.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Title Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Pilih Organisasi</h1>
            <p class="text-lg text-gray-600 mb-2">
                Silahkan pilih organisasi yang ingin Anda akses.
            </p>
            <p class="text-sm text-gray-500">
                Anda dapat berganti organisasi sewaktu-waktu dari dashboard.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative max-w-md mx-auto">
                <input type="text" 
                       id="searchInput"
                       placeholder="Cari Organisasi" 
                       class="search-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Organizations List -->
        <div class="space-y-4 mb-8" id="organizationsList">
            @foreach($organizations as $organization)
            <div class="organization-card bg-white rounded-xl p-6 shadow-sm organization-item" 
                 data-name="{{ strtolower($organization->name) }}">
                <div class="flex items-center justify-between">
                    <!-- Organization Info -->
                    <div class="flex items-center space-x-4">
                        <!-- Organization Icon -->
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-blue-600 text-lg"></i>
                        </div>
                        
                        <!-- Organization Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $organization->name }}</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($organization->type) }}</p>
                            @if($organization->description)
                                <p class="text-xs text-gray-400 mt-1">{{ Str::limit($organization->description, 60) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Select Button -->
                    <form method="POST" action="{{ route('diantaranexus.select-organization', $organization->id) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="btn-success text-white px-6 py-2 rounded-lg font-medium hover:opacity-90 transition-all">
                            <i class="fas fa-check mr-2"></i>
                            PILIH
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Create New Organization Button -->
        <div class="text-center">
            <a href="{{ route('diantaranexus.setup-organization') }}" 
               class="btn-primary text-white px-8 py-3 rounded-lg font-medium hover:opacity-90 transition-all inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                BUAT ORGANISASI BARU
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Punya Pertanyaan? 
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                    Hubungi CS Nexus
                </a>
            </p>
        </div>
    </main>

    <!-- JavaScript for Search -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const organizationItems = document.querySelectorAll('.organization-item');
            
            organizationItems.forEach(item => {
                const organizationName = item.getAttribute('data-name');
                if (organizationName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Add loading state to buttons
        document.querySelectorAll('form button[type="submit"]').forEach(button => {
            button.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                this.disabled = true;
            });
        });
    </script>
</body>
</html>
