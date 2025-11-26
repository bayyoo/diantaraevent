<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Partner Dashboard') - Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Montserrat', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'nexus': '#2563eb',
                        'nexus-dark': '#1d4ed8',
                        'nexus-light': '#3b82f6',
                    }
                }
            }
        }
    </script>
    <style>
        .nexus-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            transform: translateX(4px);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-r border-gray-200 flex-shrink-0 shadow-sm">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-10">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-nexus">Nexus</h1>
                        <p class="text-sm text-gray-500">Partner Portal</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('diantaranexus.dashboard') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium {{ request()->routeIs('diantaranexus.dashboard') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-chart-line w-5 text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('diantaranexus.events.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium {{ request()->routeIs('diantaranexus.events.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-calendar-alt w-5 text-lg"></i>
                        <span>Events</span>
                    </a>
                    
                    <a href="#" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium text-gray-700">
                        <i class="fas fa-ticket-alt w-5 text-lg"></i>
                        <span>Tickets</span>
                    </a>
                    
                    <a href="#" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium text-gray-700">
                        <i class="fas fa-users w-5 text-lg"></i>
                        <span>Attendees</span>
                    </a>
                    
                    <a href="#" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium text-gray-700">
                        <i class="fas fa-chart-bar w-5 text-lg"></i>
                        <span>Analytics</span>
                    </a>
                    
                    <a href="#" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium text-gray-700">
                        <i class="fas fa-percentage w-5 text-lg"></i>
                        <span>Promotions</span>
                    </a>
                    
                    <a href="#" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl font-medium text-gray-700">
                        <i class="fas fa-qrcode w-5 text-lg"></i>
                        <span>Check-in</span>
                    </a>
                </nav>

                <!-- User Section -->
                <div class="mt-12 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3 px-4 py-3">
                        <div class="w-10 h-10 bg-nexus rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">
                                {{ substr(Auth::guard('partner')->user()->name, 0, 2) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ Auth::guard('partner')->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                @if(session('selected_organization_id'))
                                    @php
                                        $selectedOrg = Auth::guard('partner')->user()->organizations()->find(session('selected_organization_id'));
                                    @endphp
                                    {{ $selectedOrg ? $selectedOrg->name : 'No Organization' }}
                                @else
                                    No Organization Selected
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-1">
                        <a href="{{ route('diantaranexus.profile') }}" 
                           class="sidebar-link flex items-center space-x-3 px-4 py-2.5 rounded-lg font-medium text-gray-700 text-sm">
                            <i class="fas fa-user w-4"></i>
                            <span>Profile</span>
                        </a>
                        
                        <a href="{{ route('diantaranexus.dashboard') }}?switch_org=1" 
                           class="sidebar-link flex items-center space-x-3 px-4 py-2.5 rounded-lg font-medium text-gray-700 text-sm">
                            <i class="fas fa-exchange-alt w-4"></i>
                            <span>Switch Organization</span>
                        </a>
                        
                        <form method="POST" action="{{ route('diantaranexus.logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="sidebar-link flex items-center space-x-3 px-4 py-2.5 rounded-lg font-medium text-gray-700 text-sm w-full text-left">
                                <i class="fas fa-sign-out-alt w-4"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-8 py-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 mt-1">@yield('page-subtitle', 'Welcome to Diantara Nexus Partner Portal')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Quick search..." 
                                   class="w-80 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-2">
                            <button class="nexus-gradient text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-all">
                                <i class="fas fa-plus mr-2"></i>
                                New Event
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
