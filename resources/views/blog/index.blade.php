<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Diantara</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
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
            font-family: 'Inter', system-ui, sans-serif;
        }
        .blog-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .gradient-overlay {
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.8) 100%);
        }
        .promo-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .promo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .promo-card:hover::before {
            left: 100%;
        }
        .promo-card:hover {
            transform: scale(1.05);
        }
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(118, 129, 255, 0.1);
        }
        .category-pill {
            transition: all 0.2s ease;
        }
        .category-pill:hover {
            transform: scale(1.05);
        }
        .event-card {
            transition: all 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .event-card img {
            transition: transform 0.5s ease;
        }
        .event-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen overflow-x-hidden">
    @include('components.navigation')

    <!-- Main Content Wrapper -->
    <div class="flex-grow">
        <!-- Hero Section with Search -->
    <div class="bg-gradient-to-br from-primary/5 via-white to-purple-50 pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-10">
                <h1 class="text-6xl font-bold text-gray-900 mb-4 tracking-tight">Blog</h1>
                <p class="text-xl text-gray-600 mb-8">Artikel, tips, dan update terbaru seputar event</p>
                
                <!-- Search Bar -->
                <form action="{{ route('blog.index') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari artikel, tips, event..." 
                            class="search-input w-full px-6 py-4 pr-14 rounded-full border-2 border-gray-200 focus:border-primary focus:outline-none text-gray-700 text-lg"
                        >
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary hover:bg-primary-dark text-white rounded-full p-3 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                    <div class="mt-3 text-sm text-gray-600">
                        Hasil pencarian untuk: <span class="font-semibold text-primary">"{{ request('search') }}"</span>
                        <a href="{{ route('blog.index') }}" class="ml-2 text-gray-500 hover:text-primary">
                            <i class="fas fa-times"></i> Hapus
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Featured Article -->
    @if($posts->count() > 0)
    <div class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-6">
            @php $featured = $posts->first(); @endphp
            <a href="{{ route('blog.show', $featured->slug) }}" class="block group">
                <div class="relative rounded-2xl overflow-hidden mb-6 shadow-xl">
                    @if($featured->featured_image)
                        <img src="{{ asset('storage/' . $featured->featured_image) }}" 
                             alt="{{ $featured->title }}" 
                             class="w-full h-[500px] object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200&h=500&fit=crop" 
                             alt="{{ $featured->title }}" 
                             class="w-full h-[500px] object-cover group-hover:scale-105 transition-transform duration-700">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                        <span class="inline-block bg-primary px-4 py-1.5 rounded-full text-sm font-semibold mb-3">
                            {{ ucfirst($featured->category) }}
                        </span>
                        <h2 class="text-4xl font-bold mb-3 group-hover:text-primary-light transition-colors">
                            {{ $featured->title }}
                        </h2>
                        <p class="text-gray-200 mb-4 text-lg max-w-3xl">
                            {{ $featured->excerpt }}
                        </p>
                        <div class="flex items-center text-sm text-gray-300 space-x-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $featured->author->name }}</span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $featured->published_at->format('d M Y') }}</span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock"></i>
                                <span>{{ $featured->reading_time }} menit baca</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    <!-- Promo Section -->
    <div class="py-20 bg-gradient-to-br from-gray-50 to-purple-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-3">Promo Spesial</h2>
                <p class="text-gray-600">Jangan lewatkan penawaran terbaik kami!</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Promo Card 1 -->
                <a href="{{ route('catalog.index') }}" class="promo-card block bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-8 text-white shadow-lg hover:shadow-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-white/20 rounded-full p-3">
                            <i class="fas fa-tag text-2xl"></i>
                        </div>
                        <span class="bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                            TERBATAS
                        </span>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">
                        Diskon 45%
                    </h3>
                    <p class="text-white/90 mb-6 text-base">
                        Merdeka Bareng Diantara
                    </p>
                    <div class="flex items-center text-sm text-white/80">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>15 Agustus 2025</span>
                    </div>
                </a>

                <!-- Promo Card 2 -->
                <a href="{{ route('catalog.index') }}" class="promo-card block bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl p-8 text-white shadow-lg hover:shadow-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-white/20 rounded-full p-3">
                            <i class="fas fa-gift text-2xl"></i>
                        </div>
                        <span class="bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                            HOT DEAL
                        </span>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">
                        Cashback 100RB
                    </h3>
                    <p class="text-white/90 mb-6 text-base">
                        Agustusan Meriah Bareng DIANTARA
                    </p>
                    <div class="flex items-center text-sm text-white/80">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>15 Agustus 2025</span>
                    </div>
                </a>

                <!-- Promo Card 3 -->
                <a href="{{ route('catalog.index') }}" class="promo-card block bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-8 text-white shadow-lg hover:shadow-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-white/20 rounded-full p-3">
                            <i class="fas fa-percent text-2xl"></i>
                        </div>
                        <span class="bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                            SPESIAL
                        </span>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">
                        Diskon 15%
                    </h3>
                    <p class="text-white/90 mb-6 text-base">
                        Gajian Anti Galau!
                    </p>
                    <div class="flex items-center text-sm text-white/80">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>15 Agustus 2025</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Articles Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Artikel Terbaru</h2>
                
                <!-- Category Filter -->
                @if($categories->count() > 0)
                <div class="flex items-center space-x-2">
                    <a href="{{ route('blog.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('category') ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-colors">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('blog.index', ['category' => $cat]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('category') == $cat ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-colors">
                            {{ ucfirst($cat) }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
            
            @if($posts->skip(1)->count() > 0)
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($posts->skip(1) as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card block bg-white rounded-lg overflow-hidden border border-gray-200 hover:border-primary transition-colors">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=400&h=250&fit=crop" 
                             alt="{{ $post->title }}" 
                             class="w-full h-48 object-cover">
                    @endif
                    <div class="p-5">
                        <span class="text-xs text-primary font-medium">{{ ucfirst($post->category) }}</span>
                        <h3 class="font-bold text-gray-900 mt-1 mb-2 line-clamp-2">
                            {{ $post->title }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ $post->excerpt }}
                        </p>
                        <div class="flex items-center text-xs text-gray-500 space-x-2">
                            <span>{{ $post->published_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span>{{ $post->reading_time }} min</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-500">Belum ada artikel lainnya.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Exciting Events Just for You Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-2">Exciting Events Just for You</h2>
                    <p class="text-gray-600">Jelajahi event menarik yang akan datang</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="flex items-center space-x-2 text-primary hover:text-primary-dark font-semibold transition-colors group">
                    <span>Lihat Semua</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            
            @if($upcomingEvents && $upcomingEvents->count() > 0)
            <div class="grid md:grid-cols-4 gap-6">
                @foreach($upcomingEvents as $event)
                <a href="{{ route('events.show', $event->id) }}" class="event-card block bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        @if($event->flyer_path)
                            <img src="{{ asset('storage/' . $event->flyer_path) }}" 
                                 alt="{{ $event->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-purple-100 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-6xl text-primary/40"></i>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-primary">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M') }}
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 mb-2 text-base line-clamp-2 min-h-[3rem]">
                            {{ $event->title }}
                        </h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                            <span class="line-clamp-1">{{ $event->location }}</span>
                        </div>
                        @if($event->price && $event->price > 0)
                        <p class="text-primary font-bold text-lg">
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        </p>
                        @else
                        <p class="text-green-600 font-bold text-lg">
                            GRATIS
                        </p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada event yang akan datang</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-4 text-primary hover:text-primary-dark font-semibold">
                    Lihat Semua Event
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Travel and Recreation Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Travel and Recreation</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Travel Card 1 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&h=300&fit=crop&crop=center');"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            15 Glamping Jawa Timur Terbaik Hingga Terbaru 2025
                        </h3>
                        <div class="text-sm text-gray-500">
                            <p>BY DIANTARA TEAM</p>
                            <p>AUGUST 15, 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Travel Card 2 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=400&h=300&fit=crop&crop=center');"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            Intip 10 Rekomendasi Resto Kopitiam Bogor yang Patut Dicoba
                        </h3>
                        <div class="text-sm text-gray-500">
                            <p>BY DIANTARA TEAM</p>
                            <p>AUGUST 15, 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Travel Card 3 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop&crop=center');"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            Gua Belanda Bandung: Sejarah, Mitos, dan Aktivitas Seru di 2025
                        </h3>
                        <div class="text-sm text-gray-500">
                            <p>BY DIANTARA TEAM</p>
                            <p>AUGUST 15, 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- End Main Content Wrapper -->

    @include('components.footer')
</body>
</html>


