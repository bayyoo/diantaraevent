<!-- Alpine.js for dropdown functionality -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
/* Hide dropdowns when scrolling */
body.scrolling .dropdown-menu {
    display: none !important;
}

/* Hide all Alpine.js dropdowns when scrolling */
body.scrolling [x-show="open"] {
    display: none !important;
}

/* Remove force-hide at top; let Alpine control visibility */

/* Ensure proper z-index stacking */
.dropdown-menu {
    z-index: 9999 !important;
}

/* Allow dropdowns to overflow the navbar container */
.navbar-container {
    overflow: visible !important;
}

/* Let Alpine handle display; keep z-index managed via .dropdown-menu */
</style>

<script>
// Hide dropdowns when scrolling (optimized)
let scrollTimer = null;
let didCloseThisScroll = false;

function forceCloseAllDropdowns() {
    // Only toggle Alpine state; avoid mass style writes
    document.querySelectorAll('[x-data]').forEach(function(el) {
        if (el.__x && el.__x.$data && el.__x.$data.open) {
            el.__x.$data.open = false;
        }
    });
}

function onScroll() {
    // Mark scrolling state
    if (!document.body.classList.contains('scrolling')) {
        document.body.classList.add('scrolling');
        // Close once at the start of a scroll burst
        if (!didCloseThisScroll) {
            didCloseThisScroll = true;
            forceCloseAllDropdowns();
        }
    }

    // Top flag
    if (window.scrollY <= 10) {
        document.body.classList.add('at-top');
    } else {
        document.body.classList.remove('at-top');
    }

    // Debounce end-of-scroll cleanup
    if (scrollTimer) clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function() {
        document.body.classList.remove('scrolling');
        didCloseThisScroll = false;
    }, 200);
}

window.addEventListener('scroll', onScroll, { passive: true });

// Close dropdowns on page load
window.addEventListener('load', function() {
    forceCloseAllDropdowns();
    if (window.scrollY <= 10) {
        document.body.classList.add('at-top');
    }
});

// Wait for Alpine.js to load then force close
document.addEventListener('alpine:init', function() {
    setTimeout(forceCloseAllDropdowns, 100);
});

// Also listen for DOM changes
document.addEventListener('DOMContentLoaded', function() {
    forceCloseAllDropdowns();
});

// Also close dropdowns on window resize
window.addEventListener('resize', function() {
    forceCloseAllDropdowns();
});

// Close dropdowns when clicking anywhere
document.addEventListener('click', function(e) {
    // If click is not on a dropdown trigger, close all dropdowns
    if (!e.target.closest('[x-data]')) {
        forceCloseAllDropdowns();
    }
});
</script>

<!-- Main Navigation Bar -->
<nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50 navbar-container">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Top Section -->
        <div class="flex justify-between items-center h-14">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2">
                    <!-- Logo Icon - Diantara PNG -->
                    <div class="w-9 h-9 flex items-center justify-center">
                        <img src="{{ asset('storage/images/logos/diantara.png') }}" 
                             alt="Diantara Logo" 
                             class="w-8 h-8 object-contain">
                    </div>
                    <!-- Logo Text -->
                    <div class="flex flex-col">
                        <span class="text-base font-bold text-gray-900 leading-tight">DIANTARA</span>
                    </div>
                </a>
            </div>
            
            <!-- Center Search Bar -->
            <div class="flex-1 max-w-xl mx-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#7681FF] focus:border-transparent" 
                           placeholder="Search for event, theme park, etc....">
                </div>
            </div>
            
            <!-- Right Side Actions -->
            <div class="flex items-center space-x-3">
                <!-- Language Selector -->
                <div class="relative">
                    <button class="flex items-center space-x-1 text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.87 15.07l-2.54-2.51.03-.03c1.74-1.94 2.98-4.17 3.71-6.53H17V4h-7V2H8v2H1v1.99h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/>
                        </svg>
                        <span class="text-xs font-medium">EN</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Diantara Nexus - Smart Redirect -->
                @auth('partner')
                    <!-- Partner is logged in - go to dashboard -->
                    <a href="/diantaranexus/dashboard" class="bg-[#7681FF] hover:bg-[#5A67D8] text-white px-3 py-1.5 rounded-lg font-medium text-xs transition-colors">
                        Partner with Us
                    </a>
                @else
                    <!-- Partner not logged in - go to login page -->
                    <a href="/diantaranexus/login" class="text-[#7681FF] hover:text-[#5A67D8] font-medium text-xs transition-colors">
                        Partner with Us
                    </a>
                @endauth
                
                @auth
                    <!-- User Menu (When Logged In) -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1.5 bg-gray-100 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg transition-colors">
                            <div class="w-7 h-7 bg-[#7681FF] rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-xs font-medium text-gray-900">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <a href="{{ route('my-events.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i> My Tickets
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Menu (When Not Logged In) -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('register') }}" class="text-[#7681FF] hover:text-[#5A67D8] font-medium text-xs transition-colors">
                            Sign Up
                        </a>
                        <a href="{{ route('login') }}" class="bg-[#7681FF] hover:bg-[#5A67D8] text-white px-3 py-1.5 rounded-lg font-medium text-xs transition-colors">
                            Log In
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Bottom Section -->
        <div class="flex justify-between items-center h-10 border-t border-gray-100">
            <!-- Left Side Selectors -->
            <div class="flex items-center space-x-4">
                <!-- Select City -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-1.5 text-gray-600 hover:text-gray-900 cursor-pointer transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-xs font-medium">Select City</span>
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition class="dropdown-menu absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                        <a href="{{ route('catalog.index', ['city' => 'jakarta']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Jakarta</a>
                        <a href="{{ route('catalog.index', ['city' => 'bandung']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Bandung</a>
                        <a href="{{ route('catalog.index', ['city' => 'surabaya']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Surabaya</a>
                        <a href="{{ route('catalog.index', ['city' => 'yogyakarta']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Yogyakarta</a>
                        <a href="{{ route('catalog.index', ['city' => 'bali']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Bali</a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <a href="{{ route('catalog.index') }}" class="block px-4 py-2 text-sm text-primary font-medium hover:bg-gray-100">All Cities</a>
                    </div>
                </div>
                
                <!-- Separator -->
                <div class="w-px h-4 bg-gray-300"></div>
                
                <!-- Categories -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-1.5 text-gray-600 hover:text-gray-900 cursor-pointer transition-colors">
                        <span class="text-xs font-medium">Categories</span>
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition class="dropdown-menu absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                        <a href="{{ route('catalog.index', ['category' => 'workshop']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-laptop-code mr-2 text-primary"></i> Workshop
                        </a>
                        <a href="{{ route('catalog.index', ['category' => 'seminar']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-chalkboard-teacher mr-2 text-primary"></i> Seminar
                        </a>
                        <a href="{{ route('catalog.index', ['category' => 'competition']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-trophy mr-2 text-primary"></i> Competition
                        </a>
                        <a href="{{ route('catalog.index', ['category' => 'concert']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-music mr-2 text-primary"></i> Concert
                        </a>
                        <a href="{{ route('catalog.index', ['category' => 'exhibition']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-palette mr-2 text-primary"></i> Exhibition
                        </a>
                        <a href="{{ route('catalog.index', ['category' => 'conference']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users mr-2 text-primary"></i> Conference
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <a href="{{ route('catalog.index') }}" class="block px-4 py-2 text-sm text-primary font-medium hover:bg-gray-100">All Categories</a>
                    </div>
                </div>
            </div>
            
            <!-- Right Side Links -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('certificate.search.page') }}" class="text-gray-600 hover:text-gray-900 font-medium text-xs transition-colors">
                    <i class="fas fa-certificate mr-1 text-xs"></i> Cari Sertifikat
                </a>
                <a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-xs transition-colors">
                    Blog
                </a>
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 font-medium text-xs transition-colors">
                    About Us
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer to prevent content from hiding behind fixed nav -->
<div class="h-24"></div>