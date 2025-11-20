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

    <!-- Featured Article (simple banner) -->
    @if($posts->count() > 0)
    <div class="bg-white py-16">
        <div class="max-w-5xl mx-auto px-6">
            @php 
                $featured = $posts->first(); 
                $featuredDefaultImage = match($featured->category) {
                    'event' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=400&fit=crop',
                    'tips' => 'https://images.unsplash.com/photo-1516387938699-a93567ec168e?w=800&h=400&fit=crop',
                    'promo' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=400&fit=crop',
                    default => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800&h=400&fit=crop',
                };
            @endphp
            <a href="{{ route('blog.show', $featured->slug) }}" class="block group bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 hover:border-primary/60 transition-colors">
                <div class="md:flex">
                    <div class="md:w-1/2 h-60 md:h-64 overflow-hidden">
                        @if($featured->featured_image)
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" 
                                 alt="{{ $featured->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <img src="{{ $featuredDefaultImage }}" 
                                 alt="{{ $featured->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @endif
                    </div>
                    <div class="md:w-1/2 p-6 md:p-8 flex flex-col justify-center">
                        <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-semibold mb-3">
                            {{ ucfirst($featured->category) }}
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 leading-tight">
                            {{ $featured->title }}
                        </h2>
                        <p class="text-sm md:text-base text-gray-600 mb-4 line-clamp-3">
                            {{ $featured->excerpt }}
                        </p>
                        <div class="flex flex-wrap items-center text-xs text-gray-500 gap-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $featured->author->name }}</span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $featured->published_at->format('d M Y') }}</span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center gap-2">
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
                @php
                    $defaultImage = match($post->category) {
                        'event' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400&h=250&fit=crop',
                        'tips' => 'https://images.unsplash.com/photo-1516387938699-a93567ec168e?w=400&h=250&fit=crop',
                        'promo' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=250&fit=crop',
                        default => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=400&h=250&fit=crop',
                    };
                @endphp
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card block bg-white rounded-lg overflow-hidden border border-gray-200 hover:border-primary transition-colors">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <img src="{{ $defaultImage }}" 
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

    <!-- Extra sections (promo, events, travel) removed for a cleaner blog page -->
    </div> <!-- End Main Content Wrapper -->

    @include('components.footer')
</body>
</html>


