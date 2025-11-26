<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Diantara Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .nexus-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        }
        
        .nexus-blue {
            color: #2563eb;
        }
        
        .bg-nexus {
            background-color: #2563eb;
        }
        
        .bg-nexus-light {
            background-color: #eff6ff;
        }
        
        .border-nexus {
            border-color: #2563eb;
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background-color: #f3f4f6;
        }
        
        .sidebar-link.active {
            background-color: #2563eb;
            color: white;
        }
        
        .nav-link {
            transition: all 0.2s ease;
            position: relative;
        }
        
        .nav-link.active {
            color: #2563eb !important;
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -16px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #2563eb;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <!-- Logo Only -->
                <img src="{{ asset('images/diantara-nexus-logo.png') }}" 
                     alt="Diantara Nexus" 
                     class="h-12 w-auto object-contain">
            </div>
            
            <!-- Top Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('diantaranexus.dashboard') }}" 
                   class="nav-link text-sm {{ request()->routeIs('diantaranexus.dashboard') ? 'active' : 'text-gray-600 hover:text-gray-900' }}">
                    HOME
                </a>
                <a href="{{ route('diantaranexus.events.index') }}" 
                   class="nav-link text-sm {{ request()->routeIs('diantaranexus.events.*') ? 'active' : 'text-gray-600 hover:text-gray-900' }}">
                    EVENT
                </a>
                <a href="{{ route('diantaranexus.attendance.index') }}" 
                   class="nav-link text-sm {{ request()->routeIs('diantaranexus.attendance.*') ? 'active' : 'text-gray-600 hover:text-gray-900' }}">
                    ABSENSI
                </a>
                <a href="{{ route('diantaranexus.organization.show') }}" 
                   class="nav-link text-sm {{ request()->routeIs('diantaranexus.organization.*') ? 'active' : 'text-gray-600 hover:text-gray-900' }}">
                    ORGANISASI
                </a>
            </div>
            
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- User Profile -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-nexus rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                            {{ substr(Auth::guard('partner')->user()->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">{{ Auth::guard('partner')->user()->name }}</div>
                        <div class="text-xs text-gray-500">
                            @if(session('selected_organization_id'))
                                @php
                                    $selectedOrg = Auth::guard('partner')->user()->organizations()->find(session('selected_organization_id'));
                                @endphp
                                {{ $selectedOrg ? $selectedOrg->name : 'No Organization' }}
                            @else
                                Partner
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
