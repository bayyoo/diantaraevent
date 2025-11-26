<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Diantara') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/diantara_logo.png') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/diantara_logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/diantara_logo.png') }}">
    
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
    <script>
        // Ensure event cards always navigate on click (no logs for performance)
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.event-card-link').forEach(function (a) {
                a.style.cursor = 'pointer';
                a.addEventListener('click', function (e) {
                    const href = a.getAttribute('href');
                    if (href && href !== '#') {
                        setTimeout(function(){
                            if (!e.defaultPrevented) return;
                            window.location.assign(href);
                        }, 0);
                    }
                }, { capture: true, passive: true });
            });
        });
    </script>
    <style>
        html {
            font-size: 14px;
        }
        
        body {
            font-family: 'Montserrat', system-ui, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }
        
        /* Modern Button Styles */
        .btn-primary {
            background: #7681FF;
            color: white;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: #5A67D8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(118, 129, 255, 0.25);
        }
        
        /* Clean Card Styles */
        .card-modern {
            background: white;
            border: 1px solid #F1F5F9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }
        .card-modern:hover {
            box-shadow: 0 8px 18px rgba(0,0,0,0.06);
            transform: translateY(-1px);
            border-color: #E2E8F0;
        }
        
        /* Category Cards */
        .category-card {
            border-radius: 20px;
        }
        .category-card .category-image-wrapper {
            overflow: hidden;
            border-radius: 20px;
            transition: box-shadow 0.3s ease;
        }
        .category-card:hover .category-image-wrapper {
            box-shadow: 0 20px 40px rgba(119, 130, 255, 0.3);
        }
        .category-card:hover .category-image {
            transform: scale(1.1);
        }
        .category-card .category-image {
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }
        
        /* Featured Event Carousel */
        .carousel-container {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,0.10);
            background: transparent;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .carousel-slides {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }
        .carousel-slide {
            min-width: 100%;
            position: relative;
            will-change: opacity, transform;
        }
        /* Optimize carousel image opacity transitions */
        .carousel-slide img { will-change: opacity; }
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.9);
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 10;
            /* removed heavy blur to improve scroll performance */
        }
        .carousel-nav:hover {
            background: white;
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .carousel-nav.prev { left: 16px; }
        .carousel-nav.next { right: 16px; }
        
        .carousel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            gap: 8px;
            z-index: 10;
        }
        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        .carousel-dot.active {
            background: rgba(255, 255, 255, 0.9);
            transform: scale(1.3);
        }
        .carousel-dot:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: scale(1.1);
        }
        
        /* Empty State */
        .empty-state {
            background: #FAFBFC;
            border: 2px dashed #E2E8F0;
        }
        
        /* Event Cards */
        .event-card-container {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 16px;
            overflow: hidden;
        }
        .event-card-container:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }
        
        /* Wishlist Button - Ensure it's always clickable */
        .wishlist-btn {
            position: relative !important;
            z-index: 99999 !important;
            pointer-events: auto !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .wishlist-btn:hover {
            transform: scale(1.1) !important;
            background-color: #f3f4f6 !important;
        }
        
        /* Ensure parent container doesn't block */
        .bg-white.rounded-2xl {
            overflow: visible !important;
        }
        .bg-white.rounded-2xl .relative {
            overflow: visible !important;
        }

        /* Make event grid always clickable and above other layers */
        .events-grid-container { position: relative; z-index: 5; pointer-events: auto; isolation: isolate; }
        .event-card-link { position: relative; z-index: 20; pointer-events: auto; display: block; }
        .events-grid-container a, .events-grid-container a * { pointer-events: auto !important; }
        /* Any stray overlay in the grid becomes non-interactive */
        .events-grid-container .absolute { pointer-events: none; }
        .events-grid-container .absolute a, .events-grid-container .absolute button { pointer-events: auto; }
        .carousel-container { position: relative; z-index: 0; }
        /* Prevent any stray absolute elements from overlaying below sections */
        .carousel-container .absolute { pointer-events: none; }
        .carousel-dots { pointer-events: auto !important; }
        /* Hard-force full-width responsive grids for events */
        .selected-events-grid, .latest-events-grid { display: grid !important; width: 100% !important; min-width: 0 !important; gap: 1rem; grid-template-columns: repeat(5, minmax(0, 1fr)); }
        @media (max-width: 1024px) { .selected-events-grid, .latest-events-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
        @media (max-width: 768px) { .selected-events-grid, .latest-events-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 640px) { .selected-events-grid, .latest-events-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        
        /* Defer rendering for heavy sections to improve initial scroll */
        .defer-section { content-visibility: visible; contain-intrinsic-size: auto; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .carousel-container {
                border-radius: 12px;
                margin: 0 0.5rem;
                max-width: 100%;
            }
            /* On mobile, show the whole banner like an ad strip */
            .carousel-container img {
                object-fit: contain !important;
                background-color: #f3f4f6;
            }
            .category-card:hover .category-image {
                transform: scale(1.1);
            }
            .event-card-container:hover {
                transform: translateY(-1px);
            }
            .carousel-nav {
                width: 32px;
                height: 32px;
            }
            .carousel-nav.prev { left: 6px; }
            .carousel-nav.next { right: 6px; }
            .carousel-slide .relative {
                height: 200px !important;
            }
        }
        
        @media (max-width: 640px) {
            .carousel-container {
                margin: 0 0.25rem;
            }
            .carousel-slide h3 {
                font-size: 1.5rem !important;
            }
            .carousel-slide p {
                font-size: 0.875rem !important;
            }
        }
        
        /* Base page background should be white and use natural scroll */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #ffffff !important;
            overflow-x: hidden !important;
        }
        /*
         * Prevent double vertical scrollbars by hiding scrollbars on
         * internal containers that use overflow-y:auto while leaving
         * the main page (body) scrollable.
         */
        .overflow-y-auto {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .overflow-y-auto::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        /* Hide scrollbars for any inner scrollable containers so only
           the main page scrollbar (body/html) remains visible. This
           targets all elements except html and body on this page. */
        *:not(html):not(body) {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        *:not(html):not(body)::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
    </style>
</head>
<body class="bg-gray-900 flex flex-col overflow-x-hidden m-0 p-0">
    @include('components.navigation')

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed top-24 right-4 bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded-lg shadow-lg z-40 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-24 right-4 bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded-lg shadow-lg z-40 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Wrapper -->
    <div class="flex-grow bg-white">
        <!-- Event Highlight Carousel -->
    <div class="bg-gray-50 py-2">
        <div class="max-w-7xl mx-auto px-6">
            <div class="carousel-container overflow-hidden">
                <!-- Slides -->
                <div class="relative" style="height: 160px">
                    @php
                        $carouselImages = [
                            asset('images/banners/gambar1.png'),
                            asset('images/banners/gambar2.png'),
                            asset('images/banners/gambar3.png'),
                        ];
                    @endphp
                    
                    @foreach($carouselImages as $index => $image)
                        <img 
                            src="{{ $image }}" 
                            alt="Event {{ $index + 1 }}" 
                            loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" 
                            id="carousel-slide-{{ $index }}"
                        >
                    @endforeach
                </div>
                
                <!-- Navigation Dots -->
                <div class="carousel-dots flex justify-center items-center p-3 space-x-2 bg-transparent z-10">
                    @foreach($carouselImages as $index => $image)
                        <button 
                            onclick="goToSlide({{ $index }})" 
                            class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-[#7681FF] scale-110 shadow-sm' : 'bg-[#E8EAFF] hover:bg-[#dce2ff]' }}"
                            id="carousel-dot-{{ $index }}"
                            aria-label="Go to slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let currentSlide = 0;
        const totalSlides = {{ count($carouselImages) }};
        let autoSlideInterval;
        const slideDuration = 3000; // 3 seconds per slide

        function goToSlide(index) {
            // Get all slides
            const slides = document.querySelectorAll('[id^="carousel-slide-"]');
            
            // Hide current slide using inline style
            const currentSlideEl = document.querySelector(`#carousel-slide-${currentSlide}`);
            const currentDotEl = document.querySelector(`#carousel-dot-${currentSlide}`);
            
            if (currentSlideEl) {
                currentSlideEl.style.opacity = '0';
                currentSlideEl.style.transition = 'opacity 0.8s ease-in-out';
            }
            
            if (currentDotEl) {
                currentDotEl.classList.remove('bg-blue-600', 'w-8');
                currentDotEl.classList.add('bg-gray-300');
            }
            
            // Update current slide
            currentSlide = (index + totalSlides) % totalSlides;
            // removed console log for performance
            
            // Show new slide
            setTimeout(() => {
                const newSlideEl = document.querySelector(`#carousel-slide-${currentSlide}`);
                const newDotEl = document.querySelector(`#carousel-dot-${currentSlide}`);
                
                if (newSlideEl) {
                    newSlideEl.style.opacity = '1';
                    newSlideEl.style.transition = 'opacity 0.8s ease-in-out';
                }
                
                if (newDotEl) {
                    newDotEl.classList.remove('bg-gray-300');
                    newDotEl.classList.add('bg-blue-600', 'w-8');
                }
            }, 50);
            
            // Reset auto-slide timer
            resetAutoSlide();
        }
        
        function nextSlide() {
            goToSlide(currentSlide + 1);
        }
        
        function startAutoSlide() {
            // Clear any existing interval
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
            // Start new interval
            autoSlideInterval = setInterval(() => {
                nextSlide();
            }, slideDuration);
        }
        
        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }
        
        // Start auto-slide on page load
        document.addEventListener('DOMContentLoaded', () => {
            
            // Initial setup
            const slides = document.querySelectorAll('[id^="carousel-slide-"]');
            
            slides.forEach((slide, index) => {
                slide.style.opacity = index === 0 ? '1' : '0';
            });
            
            // Start auto-sliding
            startAutoSlide();
            
            // Pause on hover
            const carousel = document.querySelector('.carousel-container');
            if (carousel) {
                carousel.addEventListener('mouseenter', () => {
                    clearInterval(autoSlideInterval);
                }, { passive: true });
                
                carousel.addEventListener('mouseleave', () => {
                    resetAutoSlide();
                }, { passive: true });
            }
            
            // Touch support for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            if (carousel) {
                carousel.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                    clearInterval(autoSlideInterval);
                }, { passive: true });
                
                carousel.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                    resetAutoSlide();
                }, { passive: true });
            }
            
            function handleSwipe() {
                const diff = touchStartX - touchEndX;
                const swipeThreshold = 50; // Minimum distance for swipe
                
                if (diff > swipeThreshold) {
                    // Swipe left - go to next slide
                    nextSlide();
                } else if (diff < -swipeThreshold) {
                    // Swipe right - go to previous slide
                    goToSlide(currentSlide - 1);
                }
            }
        });
    </script>

    <!-- Event Categories Section -->
    <div class="bg-white py-4">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-3">
                <h2 class="text-base font-bold text-gray-900 mb-1">Explore by Category</h2>
                <p class="text-[11px] text-gray-600">Find events that match your interests and passion</p>
            </div>

            <!-- Icon-style categories -->
            <div class="flex items-stretch justify-center gap-4 md:gap-6 overflow-x-auto md:overflow-x-visible py-1.5">
                @foreach($eventCategories as $category)
                    @php
                        $name = strtolower($category['name']);
                        $icon = 'fa-circle';
                        if (str_contains($name, 'edukasi') || str_contains($name, 'career') || str_contains($name, 'karier')) {
                            $icon = 'fa-briefcase';
                        } elseif (str_contains($name, 'hiburan') || str_contains($name, 'pertunjukan') || str_contains($name, 'entertainment')) {
                            $icon = 'fa-masks-theater';
                        } elseif (str_contains($name, 'travel') || str_contains($name, 'outdoor') || str_contains($name, 'wisata')) {
                            $icon = 'fa-flag';
                        } elseif (str_contains($name, 'olahraga') || str_contains($name, 'sport')) {
                            $icon = 'fa-dumbbell';
                        } elseif (str_contains($name, 'amal') || str_contains($name, 'charity')) {
                            $icon = 'fa-ribbon';
                        } elseif (str_contains($name, 'seni') || str_contains($name, 'budaya') || str_contains($name, 'art')) {
                            $icon = 'fa-palette';
                        } elseif (str_contains($name, 'relaksasi') || str_contains($name, 'wellness')) {
                            $icon = 'fa-spa';
                        } elseif (str_contains($name, 'belanja') || str_contains($name, 'shopping')) {
                            $icon = 'fa-cart-shopping';
                        } elseif (str_contains($name, 'tempat wisata') || str_contains($name, 'landmark')) {
                            $icon = 'fa-landmark';
                        }
                    @endphp
                    <a href="{{ route('catalog.index', ['category' => strtolower($category['name'])]) }}" class="flex flex-col items-center min-w-[60px] group">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center shadow-sm transition-transform group-hover:scale-105" style="background-color:#E8EAFF;">
                            @php
                                // Specific icon per category
                                $iconMap = [
                                    'workshop' => 'fa-screwdriver-wrench',
                                    'seminar' => 'fa-chalkboard-user',
                                    'competition' => 'fa-trophy',
                                    'concert' => 'fa-music',
                                    'music' => 'fa-music',
                                    'conference' => 'fa-people-group',
                                    'business' => 'fa-briefcase',
                                    'technology' => 'fa-microchip',
                                    'entertainment' => 'fa-masks-theater',
                                    'sport' => 'fa-dumbbell',
                                    'olahraga' => 'fa-dumbbell',
                                    'travel' => 'fa-flag',
                                    'outdoor' => 'fa-flag',
                                    'wisata' => 'fa-landmark',
                                    'charity' => 'fa-hand-holding-heart',
                                    'amal' => 'fa-hand-holding-heart',
                                    'art' => 'fa-palette',
                                    'seni' => 'fa-palette',
                                    'budaya' => 'fa-palette',
                                    'wellness' => 'fa-spa',
                                    'relaksasi' => 'fa-spa',
                                    'shopping' => 'fa-cart-shopping',
                                    'belanja' => 'fa-cart-shopping',
                                ];
                                $nameKey = strtolower($category['name']);
                                $iconKey = collect(array_keys($iconMap))->first(function($k) use ($nameKey){ return str_contains($nameKey, $k); });
                                $resolved = $iconKey ? $iconMap[$iconKey] : 'fa-circle';
                            @endphp
                            <i class="fa-solid {{ $resolved }} text-lg md:text-xl" style="color:#7681FF;"></i>
                        </div>
                        <div class="mt-1.5 text-[10px] md:text-[11px] text-gray-700 text-center leading-tight max-w-[72px]">
                            {{ $category['name'] }}
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Selected Events Section -->
    <div class="bg-gray-50 py-4 defer-section">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Section Header (simplified) -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Exciting Events Just for You</h2>
            </div>

            @php
                $homepageEvents = isset($homepageEvents) ? $homepageEvents : collect();
                $excitingEvents = $homepageEvents->take(4);
            @endphp
            @if($excitingEvents->count() > 0)
                <!-- Modern Event Cards -->
                <div class="mb-4 events-grid-container">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            @foreach($excitingEvents as $event)
                        @php
                            // Determine category based on event title
                            $category = 'General';
                            $categoryColor = 'bg-gray-500';
                            if (str_contains(strtolower($event->title), 'workshop') || str_contains(strtolower($event->title), 'tech talk') || str_contains(strtolower($event->title), 'seminar')) {
                                $category = 'Technology';
                                $categoryColor = 'bg-[#7681FF]';
                            } elseif (str_contains(strtolower($event->title), 'music') || str_contains(strtolower($event->title), 'festival') || str_contains(strtolower($event->title), 'konser')) {
                                $category = 'Music';
                                $categoryColor = 'bg-[#7681FF]';
                            } elseif (str_contains(strtolower($event->title), 'dance') || str_contains(strtolower($event->title), 'comedy') || str_contains(strtolower($event->title), 'disney')) {
                                $category = 'Entertainment';
                                $categoryColor = 'bg-pink-500';
                            } elseif (str_contains(strtolower($event->title), 'competition') || str_contains(strtolower($event->title), 'championship') || str_contains(strtolower($event->title), 'gaming')) {
                                $category = 'Competition';
                                $categoryColor = 'bg-red-500';
                            } elseif (str_contains(strtolower($event->title), 'business') || str_contains(strtolower($event->title), 'networking') || str_contains(strtolower($event->title), 'startup')) {
                                $category = 'Business';
                                $categoryColor = 'bg-green-500';
                            }

                            // Event images mapping
                                        $eventImages = [
                                'SCREAM OK DANCE 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                'BERISIK ASIK Music Festival' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                'SUCI FEST 2025' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop&crop=center',
                                'Disney THE LITTLE MERMAID JR' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                'Workshop Laravel untuk Pemula' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&h=400&fit=crop&crop=center',
                                'Tech Talk: AI & Machine Learning' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                'JAZZ FESTIVAL 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                'ROCK N ROLL NIGHT' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                'MAGIC SHOW SPECTACULAR' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                'Workshop Digital Marketing' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                                'Workshop Photography & Videography' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                'Workshop UI/UX Design' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                                'Workshop Data Science & AI' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                'Seminar Entrepreneurship' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=600&h=400&fit=crop&crop=center',
                                'Fintech Conference 2025' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                'Blockchain & Cryptocurrency Summit' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                'IMPACTNATION JAPAN FESTIVAL 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                'MAGNIFEST 2025' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=600&h=400&fit=crop&crop=center',
                                'FESTIVAL KULINER NUSANTARA' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&h=400&fit=crop&crop=center',
                                'FESTIVAL BUDAYA BALI' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                'GAMING COMPETITION 2025' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                'FUTSAL CHAMPIONSHIP' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&h=400&fit=crop&crop=center',
                                'COOKING COMPETITION' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                'ART EXHIBITION: Modern Indonesian Art' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01988ec?w=600&h=400&fit=crop&crop=center',
                                'PHOTOGRAPHY EXHIBITION' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                'BOOK FAIR & LITERATURE FESTIVAL' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                                'YOGA & MEDITATION WORKSHOP' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop&crop=center',
                                'HEALTH & FITNESS EXPO' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop&crop=center',
                                'STARTUP PITCH COMPETITION' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                                'BUSINESS NETWORKING EVENT' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                'ROBOTICS COMPETITION' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                'HACKATHON 2025' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                            ];
                            $imageUrl = $eventImages[$event->title] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop&crop=center';
                            
                            // Use price from database
                            $isFree = !$event->price || $event->price == 0;
                            $price = $isFree ? 0 : $event->price;
                                    @endphp
                        <a href="{{ $event->link_url ?? '#' }}" class="event-card-link bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300 overflow-hidden group cursor-pointer flex flex-col h-full p-3">
                            <!-- Image Top -->
                            <div class="relative overflow-hidden rounded-xl">
                                @if($event->flyer_path)
                                    <img 
                                        src="{{ asset($event->flyer_path) }}" 
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
                
                
                <!-- Section Separator -->
                <div class="border-t border-gray-200 my-12"></div>
                
                <!-- Second Section: Latest Events -->
                @if($events->count() > 6)
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
                        'SCREAM OK DANCE 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                        'BERISIK ASIK Music Festival' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                        'SUCI FEST 2025' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                        'Disney THE LITTLE MERMAID JR' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                        'Workshop Laravel untuk Pemula' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&h=400&fit=crop&crop=center',
                        'Tech Talk: AI & Machine Learning' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                        'JAZZ FESTIVAL 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                        'ROCK N ROLL NIGHT' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                        'MAGIC SHOW SPECTACULAR' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01988ec?w=600&h=400&fit=crop&crop=center',
                        'Workshop Digital Marketing' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                        'Workshop Photography & Videography' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                        'Workshop UI/UX Design' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                        'Workshop Data Science & AI' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                        'Seminar Entrepreneurship' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=600&h=400&fit=crop&crop=center',
                        'Fintech Conference 2025' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                        'Blockchain & Cryptocurrency Summit' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                        'IMPACTNATION JAPAN FESTIVAL 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                        'MAGNIFEST 2025' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=600&h=400&fit=crop&crop=center',
                        'FESTIVAL KULINER NUSANTARA' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&h=400&fit=crop&crop=center',
                        'FESTIVAL BUDAYA BALI' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                        'GAMING COMPETITION 2025' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                        'FUTSAL CHAMPIONSHIP' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&h=400&fit=crop&crop=center',
                        'COOKING COMPETITION' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                        'ART EXHIBITION: Modern Indonesian Art' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01988ec?w=600&h=400&fit=crop&crop=center',
                        'PHOTOGRAPHY EXHIBITION' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                        'BOOK FAIR & LITERATURE FESTIVAL' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                        'YOGA & MEDITATION WORKSHOP' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop&crop=center',
                        'HEALTH & FITNESS EXPO' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop&crop=center',
                        'STARTUP PITCH COMPETITION' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                        'BUSINESS NETWORKING EVENT' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                        'ROBOTICS COMPETITION' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                        'HACKATHON 2025' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                    ];
                @endphp
                @foreach($homepageEvents->skip(4)->take(4) as $event)
                                @php
                                    // Determine category based on event title
                                    $category = 'General';
                                    $categoryColor = 'bg-gray-500';
                                    if (str_contains(strtolower($event->title), 'workshop') || str_contains(strtolower($event->title), 'tech talk') || str_contains(strtolower($event->title), 'seminar')) {
                                        $category = 'Technology';
                                        $categoryColor = 'bg-[#7681FF]';
                                    } elseif (str_contains(strtolower($event->title), 'music') || str_contains(strtolower($event->title), 'festival') || str_contains(strtolower($event->title), 'konser')) {
                                        $category = 'Music';
                                        $categoryColor = 'bg-[#7681FF]';
                                    } elseif (str_contains(strtolower($event->title), 'dance') || str_contains(strtolower($event->title), 'comedy') || str_contains(strtolower($event->title), 'disney')) {
                                        $category = 'Entertainment';
                                        $categoryColor = 'bg-pink-500';
                                    } elseif (str_contains(strtolower($event->title), 'competition') || str_contains(strtolower($event->title), 'championship') || str_contains(strtolower($event->title), 'gaming')) {
                                        $category = 'Competition';
                                        $categoryColor = 'bg-red-500';
                                    } elseif (str_contains(strtolower($event->title), 'business') || str_contains(strtolower($event->title), 'networking') || str_contains(strtolower($event->title), 'startup')) {
                                        $category = 'Business';
                                        $categoryColor = 'bg-green-500';
                                    }

                                    // Event images mapping
                                    $eventImages = [
                                        'SCREAM OK DANCE 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                        'BERISIK ASIK Music Festival' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                        'SUCI FEST 2025' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                        'Disney THE LITTLE MERMAID JR' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                        'Workshop Laravel untuk Pemula' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&h=400&fit=crop&crop=center',
                                        'Tech Talk: AI & Machine Learning' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                        'JAZZ FESTIVAL 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                        'ROCK N ROLL NIGHT' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                        'MAGIC SHOW SPECTACULAR' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                        'Workshop Digital Marketing' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                                        'Workshop Photography & Videography' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                        'Workshop UI/UX Design' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                                        'Workshop Data Science & AI' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                        'Seminar Entrepreneurship' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                                        'Fintech Conference 2025' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                        'Blockchain & Cryptocurrency Summit' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                        'IMPACTNATION JAPAN FESTIVAL 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                        'MAGNIFEST 2025' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                        'FESTIVAL KULINER NUSANTARA' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                        'FESTIVAL BUDAYA BALI' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                        'GAMING COMPETITION 2025' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                        'FUTSAL CHAMPIONSHIP' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                        'COOKING COMPETITION' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                        'ART EXHIBITION: Modern Indonesian Art' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                        'PHOTOGRAPHY EXHIBITION' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                        'BOOK FAIR & LITERATURE FESTIVAL' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                                        'YOGA & MEDITATION WORKSHOP' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop&crop=center',
                                        'HEALTH & FITNESS EXPO' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop&crop=center',
                                        'STARTUP PITCH COMPETITION' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                                        'BUSINESS NETWORKING EVENT' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                        'ROBOTICS COMPETITION' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                        'HACKATHON 2025' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                    ];
                                    $imageUrl = $eventImages[$event->title] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop&crop=center';
                                    
                                    // Use price from database
                                    $isFree = !$event->price || $event->price == 0;
                                    $price = $isFree ? 0 : $event->price;
                                @endphp
                                <a href="{{ $event->link_url ?? '#' }}" class="bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300 overflow-hidden group cursor-pointer event-card-link flex flex-col h-full p-3">
                                    <!-- Image Top -->
                                    <div class="relative overflow-hidden rounded-xl">
                                        @if($event->flyer_path)
                                            <img 
                                                src="{{ asset($event->flyer_path) }}" 
                                                alt="{{ $event->title }}" 
                                                loading="lazy" decoding="async"
                                                class="w-full h-40 object-cover"
                                                onerror="this.src='{{ $imageUrl }}'"
                                            >
                                        @else
                                            <img src="{{ $imageUrl }}" alt="{{ $event->title }}" loading="lazy" decoding="async" class="w-full h-40 object-cover" onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop'">
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="mt-3 flex-1 flex flex-col">
                                        <h3 class="font-semibold text-gray-900 mb-1 text-[13px] leading-snug line-clamp-2 min-h-[2.2rem]">
                                            {{ $event->title }}
                                        </h3>
                                        <div class="space-y-1.5 mb-3">
                                            <div class="flex items-center text-[11px] text-gray-600"><i class="fas fa-user mr-1.5 text-gray-400"></i> {{ Str::limit($event->creator_name ?? 'Organizer', 28) }}</div>
                                            <div class="flex items-center text-[11px] text-gray-600"><i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                                            <div class="flex items-center text-[11px] text-gray-600"><i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i> {{ Str::limit($event->location, 34) }}</div>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-auto">
                                            <span class="text-[11px] text-gray-500">Mulai dari</span>
                                            @if($isFree)
                                                <span class="px-2 py-1 rounded-full text-[12px] font-semibold bg-green-50 text-green-600">Gratis</span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-[12px] font-semibold" style="background:#E8EAFF;color:#7681FF;">IDR {{ number_format($price / 1000, 0) }}K</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Feature Highlight Section -->
                <div class="my-16">
                    <div class="bg-gradient-to-r from-primary/5 to-purple-500/5 rounded-2xl p-8 text-center">
                        <div class="max-w-3xl mx-auto">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">What Our Community Says</h3>
                            <p class="text-gray-600">Real experiences from real people who joined our events</p>
                        </div>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-gray-50 rounded-xl">
                                <div class="flex justify-center mb-4">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-4 italic">"Amazing platform! Found the perfect workshop for my career development. The booking process was super smooth."</p>
                                <div class="flex items-center justify-center">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face" alt="Sarah" class="w-10 h-10 rounded-full mr-3">
                                    <div class="text-left">
                                        <div class="font-semibold text-gray-900">Sarah Johnson</div>
                                        <div class="text-sm text-gray-500">Software Developer</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center p-6 bg-gray-50 rounded-xl">
                                <div class="flex justify-center mb-4">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-4 italic">"Love the variety of events! From tech talks to music festivals, there's something for everyone. Highly recommended!"</p>
                                <div class="flex items-center justify-center">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face" alt="Mike" class="w-10 h-10 rounded-full mr-3">
                                    <div class="text-left">
                                        <div class="font-semibold text-gray-900">Mike Chen</div>
                                        <div class="text-sm text-gray-500">Marketing Manager</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center p-6 bg-gray-50 rounded-xl">
                                <div class="flex justify-center mb-4">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-4 italic">"Great customer service and easy-to-use platform. The events are well-organized and worth every penny!"</p>
                                <div class="flex items-center justify-center">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face" alt="Lisa" class="w-10 h-10 rounded-full mr-3">
                                    <div class="text-left">
                                        <div class="font-semibold text-gray-900">Lisa Wang</div>
                                        <div class="text-sm text-gray-500">UX Designer</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{--
                <!-- Newsletter Section -->
                <div class="my-16">
                    <div class="bg-gradient-to-r from-primary to-purple-600 rounded-2xl p-8 text-white text-center">
                        <div class="max-w-2xl mx-auto">
                            <h3 class="text-2xl font-bold mb-2">Never Miss an Amazing Event!</h3>
                            <p class="mb-6 opacity-90">Subscribe to our newsletter and be the first to know about new events, exclusive offers, and early bird discounts.</p>
                            
                            <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                                <input type="email" placeholder="Enter your email address" class="flex-1 px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20">
                                <button class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                                    Subscribe
                                </button>
                            </div>
                            
                            <p class="text-sm opacity-75 mt-4">Join 25,000+ subscribers. No spam, unsubscribe anytime.</p>
                        </div>
                    </div>
                </div>
                --}}
                
                {{--
                <!-- Fourth Section: Upcoming This Week -->
                @if($events->count() > 15)
                    <div class="mb-8 max-w-7xl mx-auto bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Upcoming This Week</h2>
                                <p class="text-sm text-gray-600 mt-1">Don't miss these exciting events happening this week!</p>
                            </div>
                            <a href="{{ route('catalog.index') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">Lihat Semua</a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            @foreach($events->skip(15)->take(5) as $event)
                                @php
                                    // Determine category based on event title
                                    $category = 'General';
                                    $categoryColor = 'bg-gray-500';
                                    if (str_contains(strtolower($event->title), 'workshop') || str_contains(strtolower($event->title), 'tech talk') || str_contains(strtolower($event->title), 'seminar')) {
                                        $category = 'Technology';
                                        $categoryColor = 'bg-[#7681FF]';
                                    } elseif (str_contains(strtolower($event->title), 'music') || str_contains(strtolower($event->title), 'festival') || str_contains(strtolower($event->title), 'konser')) {
                                        $category = 'Music';
                                        $categoryColor = 'bg-purple-500';
                                    } elseif (str_contains(strtolower($event->title), 'dance') || str_contains(strtolower($event->title), 'comedy') || str_contains(strtolower($event->title), 'disney')) {
                                        $category = 'Entertainment';
                                        $categoryColor = 'bg-pink-500';
                                    } elseif (str_contains(strtolower($event->title), 'competition') || str_contains(strtolower($event->title), 'championship') || str_contains(strtolower($event->title), 'gaming')) {
                                        $category = 'Competition';
                                        $categoryColor = 'bg-red-500';
                                    } elseif (str_contains(strtolower($event->title), 'business') || str_contains(strtolower($event->title), 'networking') || str_contains(strtolower($event->title), 'startup')) {
                                        $category = 'Business';
                                        $categoryColor = 'bg-green-500';
                                    }

                                    // Event images mapping
                                    $eventImages = [
                                        'SCREAM OK DANCE 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                        'BERISIK ASIK Music Festival' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                        'SUCI FEST 2025' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                        'Disney THE LITTLE MERMAID JR' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                        'Workshop Laravel untuk Pemula' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&h=400&fit=crop&crop=center',
                                        'Tech Talk: AI & Machine Learning' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                    ];
                                    $imageUrl = $eventImages[$event->title] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop&crop=center';
                                    
                                    // Use price from database
                                    $isFree = !$event->price || $event->price == 0;
                                    $price = $isFree ? 0 : $event->price;
                                @endphp
                                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer">
                                    <!-- Image Section -->
                                    <div class="relative overflow-hidden">
                                        @if($event->flyer_path)
                                            <img 
                                                src="{{ asset($event->flyer_path) }}" 
                                                alt="{{ $event->title }}" 
                                                class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-105"
                                                onerror="this.src='{{ $imageUrl }}'"
                                            >
                                        @else
                                            <img src="{{ $imageUrl }}" alt="{{ $event->title }}" class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-105" onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop'">
                                        @endif
                                        
                                        <!-- Category Badge -->
                                        <div class="absolute top-2 left-2">
                                            <span class="{{ $categoryColor }} text-white text-xs font-medium px-2 py-1 rounded">
                                                {{ $category }}
                                            </span>
                                        </div>
                                        
                                        <!-- Price Badge -->
                                        @if($isFree)
                                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
                                                FREE
                                            </div>
                                        @else
                                            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                                IDR {{ number_format($price / 1000, 0) }}K
                                            </div>
                                        @endif
                                        
                                        <!-- Upcoming Badge -->
                                        <div class="absolute bottom-2 left-2">
                                            <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded flex items-center">
                                                <i class="fas fa-clock mr-1"></i> Soon
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content Section -->
                                    <div class="p-3">
                                        <h3 class="font-semibold text-gray-900 mb-2 text-sm leading-tight line-clamp-2 min-h-[2.5rem]">
                                            {{ $event->title }}
                                        </h3>
                                        
                                        <div class="flex items-center text-xs text-gray-600 mb-1">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                        </div>
                                        
                                        <div class="flex items-center text-xs text-gray-600 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($event->location, 25) }}
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">
                                                by {{ $event->creator_name }}
                                            </span>
                                            @if($event->creator_type === 'partner')
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                                                    Partner
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                --}}
            @else
                <!-- Empty State -->
                <div class="empty-state rounded-3xl p-16 text-center max-w-lg mx-auto">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Event</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">No events are currently available or match your search criteria. Check back soon for new opportunities!</p>
                    @if(request('search'))
                        <a href="/" class="btn-primary inline-flex items-center px-6 py-3 font-medium rounded-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            View All Events
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Load More Events Section -->
    @if(isset($events) && $events->hasPages())
        <div class="bg-white py-8">
            <div class="max-w-4xl mx-auto px-6 text-center">
                @if($events->hasMorePages())
                    <a href="{{ route('catalog.index') }}" class="btn-primary inline-flex items-center px-8 py-4 text-lg font-medium rounded-xl">
                        <span>View All {{ $events->total() }} Events</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                @endif
                
                <div class="mt-4 text-sm text-gray-500">
                    Showing {{ min(20, $events->total()) }} of {{ $events->total() }} events on homepage
                </div>
                
                <div class="mt-2 text-xs text-gray-400">
                    {{ $events->total() - min(20, $events->total()) > 0 ? (($events->total() - min(20, $events->total())) . ' more events available in catalog') : 'All events displayed' }}
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Call to Action Section -->
    <div class="bg-gradient-to-br from-gray-50 to-blue-50 py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Ready to Discover More?
                </h2>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Join thousands of professionals expanding their knowledge and network through amazing events
                </p>
            </div>
            
            <!-- Feature Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Easy Discovery</h3>
                    <p class="text-sm text-gray-600">Find events that match your interests with our smart search</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Instant Booking</h3>
                    <p class="text-sm text-gray-600">Book your spot in seconds with our streamlined process</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-orange-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-orange-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Get Certified</h3>
                    <p class="text-sm text-gray-600">Earn certificates for workshops and professional events</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-purple-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Build Network</h3>
                    <p class="text-sm text-gray-600">Connect with like-minded people and grow your network</p>
                </div>
            </div>
            
            <!-- CTA Buttons -->
            <div class="text-center">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('catalog.index') }}" 
                       class="inline-flex items-center bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-compass mr-3"></i>
                        Explore All Events
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    
                    <a href="#" 
                       class="inline-flex items-center bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200">
                        <i class="fas fa-play mr-3"></i>
                        Watch Demo
                    </a>
                </div>
                
                <p class="text-gray-500 mt-6">🎉 Join 50,000+ happy event-goers who trust Diantara</p>
            </div>
        </div>
    </div>

    <script>
        // Carousel functionality moved to BannerCarousel.vue component
        let currentPage = {{ $events->currentPage() ?? 1 }};

        function updateSlidePosition() {
            const translateX = -currentSlide * 100;
            slidesContainer.style.transform = `translateX(${translateX}%)`;
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlidePosition();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlidePosition();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlidePosition();
        // Dropdown functionality
        function initDropdowns() {
            // Find buttons by their text content
            const buttons = document.querySelectorAll('button');
            
            buttons.forEach(button => {
                if (button.textContent.includes('Select City')) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        showCityDropdown(this);
                    });
                } else if (button.textContent.includes('Categories')) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        showCategoriesDropdown(this);
                    });
                }
            });
        }

        function showCityDropdown(button) {
            // Remove existing dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(dropdown => dropdown.remove());
            
            const dropdown = document.createElement('div');
            dropdown.className = 'dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50';
            dropdown.innerHTML = `
                <div class="py-2">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Jakarta</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Bandung</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Surabaya</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Yogyakarta</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Medan</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Semarang</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Makassar</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">All Cities</a>
                </div>
            `;
            
            button.parentElement.appendChild(dropdown);
            
            // Close dropdown when clicking outside
            setTimeout(() => {
                document.addEventListener('click', function closeDropdown(e) {
                    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.remove();
                        document.removeEventListener('click', closeDropdown);
                    }
                });
            }, 0);
        }

        function showCategoriesDropdown(button) {
            // Remove existing dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(dropdown => dropdown.remove());
            
            const dropdown = document.createElement('div');
            dropdown.className = 'dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50';
            dropdown.innerHTML = `
                <div class="py-2">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Technology</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Business</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Education</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Entertainment</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Sports</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Health & Wellness</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Arts & Culture</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">All Categories</a>
                </div>
            `;
            
            button.parentElement.appendChild(dropdown);
            
            // Close dropdown when clicking outside
            setTimeout(() => {
                document.addEventListener('click', function closeDropdown(e) {
                    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.remove();
                        document.removeEventListener('click', closeDropdown);
                    }
                });
            }, 0);
        }

        // Initialize dropdowns when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initDropdowns();
        });

        // Load More Events functionality
        async function loadMoreEvents() {
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const loadMoreText = document.getElementById('loadMoreText');
            const loadMoreIcon = document.getElementById('loadMoreIcon');
            const loadingIcon = document.getElementById('loadingIcon');
            
            if (!loadMoreBtn) return;
            
            // Show loading state
            loadMoreText.textContent = 'Loading...';
            loadMoreIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');
            loadMoreBtn.disabled = true;
            
            try {
                const url = new URL(window.location);
                url.searchParams.set('page', currentPage + 1);
                
                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract new event cards
                    const newEvents = doc.querySelectorAll('.event-card-container');
                    const eventsContainer = document.querySelector('.events-grid-container');
                    
                    if (eventsContainer && newEvents.length > 0) {
                        newEvents.forEach(event => {
                            eventsContainer.appendChild(event);
                        });
                        currentPage++;
                        
                        // Check if there are more pages
                        const hasMorePages = doc.querySelector('#loadMoreBtn');
                        if (!hasMorePages) {
                            loadMoreBtn.style.display = 'none';
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading more events:', error);
            } finally {
                // Reset button state
                loadMoreText.textContent = 'Load More Events';
                loadMoreIcon.classList.remove('hidden');
                loadingIcon.classList.add('hidden');
                loadMoreBtn.disabled = false;
            }
        }

        // Smooth scroll for category clicks
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                // Add smooth scroll to events section
                document.querySelector('.events-section')?.scrollIntoView({ 
                    behavior: 'smooth' 
                });
            });
        });

        // Initialize
        updateSlidePosition();
        
        // Flash message auto-hide
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.fixed.top-20');
            flashMessages.forEach(msg => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);

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
                    
                    // Optional: Show a brief notification
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
    </div> <!-- End Main Content Wrapper -->

    @include('components.footer')
</body>
</html>
