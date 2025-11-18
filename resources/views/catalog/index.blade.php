<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Event Catalog - {{ config('app.name', 'Diantara') }}</title>
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
        
        .event-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .event-card img {
            transition: transform 0.5s ease;
        }
        
        .event-card:hover img {
            transform: scale(1.05);
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Wishlist Button - Ensure it's always clickable */
        .wishlist-btn {
            position: relative;
            z-index: 100 !important;
            pointer-events: auto !important;
        }
        .wishlist-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen overflow-x-hidden">
    @include('components.navigation')

    <!-- Main Content -->
    <div class="pt-28 pb-8 flex-grow">
<div class="max-w-7xl mx-auto px-6 py-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <h1 class="text-lg font-bold text-gray-900 mb-1.5">Katalog Event</h1>
        <p class="text-xs text-gray-600">Temukan dan ikuti berbagai event menarik</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
        <form method="GET" action="{{ route('catalog.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search mr-2 text-primary"></i>Cari Event
                    </label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan judul, deskripsi, atau lokasi..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    >
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date_filter" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-primary"></i>Filter Tanggal
                    </label>
                    <select 
                        id="date_filter" 
                        name="date_filter"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    >
                        <option value="">Semua Event</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Event Mendatang</option>
                        <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>Event Selesai</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sort mr-2 text-primary"></i>Urutkan
                    </label>
                    <select 
                        id="sort" 
                        name="sort"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    >
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Tanggal (Terdekat)</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Tanggal (Terjauh)</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
                        <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-3">
                <button 
                    type="submit" 
                    class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm"
                >
                    <i class="fas fa-search mr-2"></i>Cari Event
                </button>
                <a 
                    href="{{ route('catalog.index') }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-6 rounded-xl transition-all duration-200 text-sm"
                >
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="mb-4">
        <p class="text-gray-600 text-sm">
            Menampilkan {{ $events->count() }} dari {{ $events->total() }} event
            @if(request('search'))
                untuk pencarian "<strong>{{ request('search') }}</strong>"
            @endif
        </p>
    </div>

    <!-- Featured Events Section -->
    @if($events->count() > 0)
        <!-- Exciting Events Just for You -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Exciting Events Just for You</h2>
                    <p class="text-gray-600 text-xs">Time to choose your kind of fun! These recommended events deserve a spot on your list.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="text-primary hover:text-primary-dark font-semibold flex items-center text-sm">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                @php
                    $randomImages = [
                        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1506157786151-b8491531f063?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&h=600&fit=crop',
                    ];
                @endphp
                @foreach($events->take(4) as $index => $event)
                    @php
                        $imageUrl = $randomImages[$index % count($randomImages)];
                        $isFree = !$event->price || $event->price == 0;
                        $price = $isFree ? 0 : $event->price;
                    @endphp
                    <a href="{{ route('events.show', $event) }}" class="event-card-link bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300 overflow-hidden group cursor-pointer flex flex-col h-full p-3">
                        <!-- Image Top -->
                        <div class="relative overflow-hidden rounded-xl">
                            @if($event->flyer_path)
                                <img 
                                    src="{{ asset('storage/' . $event->flyer_path) }}" 
                                    alt="{{ $event->title }}" 
                                    loading="lazy" decoding="async"
                                    class="w-full h-32 object-cover"
                                    onerror="this.src='{{ $imageUrl }}'"
                                >
                            @else
                                <img src="{{ $imageUrl }}" alt="{{ $event->title }}" loading="lazy" decoding="async" class="w-full h-32 object-cover" onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop'">
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="mt-3 flex-1 flex flex-col">
                            <h3 class="font-semibold text-gray-900 mb-1 text-[12px] leading-snug line-clamp-2 min-h-[2rem]">
                                {{ $event->title }}
                            </h3>
                            <div class="space-y-1.5 mb-3">
                                <div class="flex items-center text-[10px] text-gray-600"><i class="fas fa-user mr-1.5 text-gray-400"></i> {{ Str::limit($event->creator_name ?? 'Organizer', 28) }}</div>
                                <div class="flex items-center text-[10px] text-gray-600"><i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i> {{ Str::limit($event->location, 40) }}</div>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-auto">
                                <span class="text-[10px] text-gray-500">Mulai dari</span>
                                @if($isFree)
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold bg-green-50 text-green-600">Gratis</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold" style="background:#E8EAFF;color:#7681FF;">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- Latest Events Section -->
        <div class="mb-16">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest Events on Diantara</h2>
                    <p class="text-gray-600">If you're always up for something new, these freshly dropped events are worth to check!</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="text-primary hover:text-primary-dark font-semibold flex items-center">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $latestImages = [
                        'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1470229538611-16ba8c7ffbd7?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1501612780327-45045538702b?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1496024840928-4c417adf211d?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&h=600&fit=crop',
                    ];
                @endphp
                @foreach($events->skip(4)->take(4) as $index => $event)
                    @php
                        $imageUrl = $latestImages[$index % count($latestImages)];
                        $isFree = !$event->price || $event->price == 0;
                        $price = $isFree ? 0 : $event->price;
                    @endphp
                    <a href="{{ route('events.show', $event) }}" class="event-card-link bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300 overflow-hidden group cursor-pointer flex flex-col h-full p-3">
                        <!-- Image Top -->
                        <div class="relative overflow-hidden rounded-xl">
                            @if($event->flyer_path)
                                <img 
                                    src="{{ asset('storage/' . $event->flyer_path) }}" 
                                    alt="{{ $event->title }}" 
                                    loading="lazy" decoding="async"
                                    class="w-full h-32 object-cover"
                                    onerror="this.src='{{ $imageUrl }}'"
                                >
                            @else
                                <img src="{{ $imageUrl }}" alt="{{ $event->title }}" loading="lazy" decoding="async" class="w-full h-32 object-cover" onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop'">
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="mt-3 flex-1 flex flex-col">
                            <h3 class="font-semibold text-gray-900 mb-1 text-[12px] leading-snug line-clamp-2 min-h-[2rem]">
                                {{ $event->title }}
                            </h3>
                            <div class="space-y-1.5 mb-3">
                                <div class="flex items-center text-[10px] text-gray-600"><i class="fas fa-user mr-1.5 text-gray-400"></i> {{ Str::limit($event->creator_name ?? 'Organizer', 28) }}</div>
                                <div class="flex items-center text-[10px] text-gray-600"><i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i> {{ Str::limit($event->location, 40) }}</div>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-auto">
                                <span class="text-[10px] text-gray-500">Mulai dari</span>
                                @if($isFree)
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold bg-green-50 text-green-600">Gratis</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold" style="background:#E8EAFF;color:#7681FF;">IDR {{ number_format($price / 1000, 0, ',', '.') }}K</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-12">
            <div class="flex items-center space-x-2">
                @if ($events->onFirstPage())
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $events->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition-all duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                    @if ($page == $events->currentPage())
                        <span class="px-4 py-2 text-white bg-primary rounded-lg font-semibold">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if ($events->hasMorePages())
                    <a href="{{ $events->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition-all duration-200">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Pagination Info -->
        <div class="text-center mt-4 text-sm text-gray-600">
            Menampilkan {{ $events->firstItem() }} - {{ $events->lastItem() }} dari {{ $events->total() }} event
        </div>
    @else
        <!-- No Events Found -->
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada event ditemukan</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search'))
                    Coba ubah kata kunci pencarian atau filter yang digunakan.
                @else
                    Belum ada event yang tersedia saat ini.
                @endif
            </p>
            <a 
                href="{{ route('catalog.index') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200"
            >
                Lihat Semua Event
            </a>
        </div>
    @endif
</div> <!-- End max-w-7xl container -->
</div> <!-- End main content flex-grow -->

    <script>
        // Wishlist toggle functionality
        async function toggleWishlist(eventId, button) {
            console.log('toggleWishlist called', eventId, button);
            
            // Prevent multiple clicks
            if (button.disabled) {
                console.log('Button already processing');
                return;
            }
            
            button.disabled = true;
            
            const icon = button.querySelector('i');
            if (!icon) {
                console.error('Icon not found in button');
                button.disabled = false;
                return;
            }
            
            const isInWishlist = icon.classList.contains('fas');
            console.log('isInWishlist:', isInWishlist);
            console.log('Icon classes:', icon.className);
            
            try {
                const url = isInWishlist 
                    ? `/wishlist/${eventId}` 
                    : '/wishlist';
                
                const method = isInWishlist ? 'DELETE' : 'POST';
                
                console.log('Sending request:', method, url);
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: method === 'POST' ? JSON.stringify({ event_id: eventId }) : null
                });
                
                console.log('Response status:', response.status);
                
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    // Toggle the visual state
                    if (isInWishlist) {
                        // Remove from wishlist
                        icon.classList.remove('fas', 'text-red-500');
                        icon.classList.add('far', 'text-gray-600');
                        console.log('Removed from wishlist - new classes:', icon.className);
                    } else {
                        // Add to wishlist
                        icon.classList.remove('far', 'text-gray-600');
                        icon.classList.add('fas', 'text-red-500');
                        console.log('Added to wishlist - new classes:', icon.className);
                    }
                    
                    // Show notification
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                showNotification('Terjadi kesalahan saat memproses permintaan', 'error');
            } finally {
                // Re-enable button after a short delay
                setTimeout(() => {
                    button.disabled = false;
                }, 500);
            }
        }

        // Show notification helper
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-28 right-6 px-4 py-3 rounded-xl shadow-lg z-50 ${
                type === 'success' 
                    ? 'bg-green-50 border border-green-200 text-green-800' 
                    : 'bg-red-50 border border-red-200 text-red-800'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>

    @include('components.footer')
</body>
</html>
