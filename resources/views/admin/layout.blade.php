<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                        'primary-dark': '#2563eb',
                        'primary-light': '#dbeafe',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link.active {
            background-color: #3b82f6;
            color: white;
        }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover:not(.active) {
            background-color: #f3f4f6;
        }
        .primary-bg {
            background-color: #3b82f6;
        }
        
        /* Pagination Styling */
        .pagination {
            display: inline-flex;
            background: #374151;
            border-radius: 0.375rem;
            overflow: hidden;
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }
        
        .pagination li {
            display: inline-block;
        }
        
        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #9ca3af;
            background: transparent;
            border-right: 1px solid #4b5563;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .pagination li:last-child a,
        .pagination li:last-child span {
            border-right: none;
        }
        
        .pagination a:hover {
            background: #4b5563;
            color: white;
        }
        
        .pagination .active span {
            background: #3b82f6;
            color: white;
        }
        
        .pagination .disabled span {
            color: #6b7280;
            cursor: not-allowed;
        }
        
        .pagination svg {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0">
            <div class="p-5">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-8">
                    <div class="text-xl font-semibold text-gray-800">Admin Panel</div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-chart-line w-4"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.events.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('admin.events.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-calendar-alt w-4"></i>
                        <span>Events</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-users w-4"></i>
                        <span>Users</span>
                    </a>
                    
                    <a href="{{ route('admin.participants.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('admin.participants.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-user-check w-4"></i>
                        <span>Participants</span>
                    </a>

                    <!-- Partners Section -->
                    <div class="pt-3 mt-3 border-t border-gray-200">
                        <h4 class="px-3 text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Partners</h4>
                        
                        <a href="{{ route('admin.partners.index') }}" 
                           class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('admin.partners.*') ? 'active' : 'text-gray-700' }}">
                            <i class="fas fa-handshake w-4"></i>
                            <span>Partners</span>
                            @php
                                $pendingPartners = \App\Models\Partner::where('status', 'pending')->count();
                            @endphp
                            @if($pendingPartners > 0)
                                <span class="ml-auto bg-yellow-500 text-white text-xs font-medium px-1.5 py-0.5 rounded">{{ $pendingPartners }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.partner-events.index') }}" 
                           class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('admin.partner-events.*') ? 'active' : 'text-gray-700' }}">
                            <i class="fas fa-calendar-check w-4"></i>
                            <span>Partner Events</span>
                            @php
                                $pendingEvents = \App\Models\PartnerEvent::where('status', 'pending_review')->count();
                            @endphp
                            @if($pendingEvents > 0)
                                <span class="ml-auto bg-yellow-500 text-white text-xs font-medium px-1.5 py-0.5 rounded">{{ $pendingEvents }}</span>
                            @endif
                        </a>
                    </div>

                    <div class="pt-3 mt-3 border-t border-gray-200">
                        <a href="{{ route('home') }}" target="_blank"
                           class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-700">
                            <i class="fas fa-external-link-alt w-4"></i>
                            <span>View Site</span>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- User Info at Bottom -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 primary-bg rounded-lg flex items-center justify-center">
                        <span class="text-white font-medium text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500">@yield('page-subtitle', 'Event Management System')</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search..." 
                                   class="w-64 pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-transparent text-sm">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-colors">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-5">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Session Timeout Script -->
    <script src="{{ asset('js/session-timeout.js') }}"></script>
    
    <!-- CSRF Token Auto-Refresh Script -->
    <script src="{{ asset('js/csrf-refresh.js') }}"></script>
</body>
</html>
