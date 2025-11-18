<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Diantara</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        .btn-primary {
            background: #7681FF;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: #5A67D8;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(118, 129, 255, 0.25);
        }
        .btn-outline {
            border: 2px solid #7681FF;
            color: #7681FF;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .btn-outline:hover {
            background: #7681FF;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(118, 129, 255, 0.25);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #7681FF 0%, #5A67D8 100%);
        }
        .hero-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(118, 129, 255, 0.1) 1px, transparent 0);
            background-size: 20px 20px;
        }
        .floating-icon {
            animation: float 6s ease-in-out infinite;
        }
        .floating-icon:nth-child(2) { animation-delay: -1s; }
        .floating-icon:nth-child(3) { animation-delay: -2s; }
        .floating-icon:nth-child(4) { animation-delay: -3s; }
        .floating-icon:nth-child(5) { animation-delay: -4s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .section-padding {
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .section-padding {
                padding: 1.5rem 0;
            }
        }
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.navigation')

    <!-- Main Content Wrapper -->
    <div class="flex-grow">
        <!-- Hero Section -->
    <div class="section-padding bg-white pt-28">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- Left Content -->
                <div class="space-y-6">
                    <div class="space-y-4">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                            Harness the power of 
                            <span class="text-primary">experience</span>
                            <br>businesses
                        </h1>
                        <p class="text-base text-gray-600 leading-relaxed max-w-lg">
                            We amplify your businesses into the world of unforgettable experience for your customers.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="/catalog" class="btn-primary px-6 py-3 rounded-xl text-sm font-semibold text-center">
                            Explore Events
                        </a>
                        <a href="{{ route('register') }}" class="btn-outline px-6 py-3 rounded-xl text-sm font-semibold text-center">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Right Content - Visual -->
                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-96 h-96">
                        <!-- Main Circle -->
                        <div class="w-full h-full bg-gradient-to-br from-primary/10 to-primary/5 rounded-full flex items-center justify-center relative">
                            <div class="w-80 h-80 bg-white rounded-full shadow-2xl flex items-center justify-center border-4 border-primary/10">
                                <span class="text-3xl font-bold text-primary">DIANTARA</span>
                            </div>
                            
                            <!-- Floating Icons -->
                            <div class="floating-icon absolute top-8 left-1/2 transform -translate-x-1/2">
                                <div class="w-14 h-14 bg-primary rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="floating-icon absolute top-20 right-12">
                                <div class="w-14 h-14 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="floating-icon absolute bottom-20 right-12">
                                <div class="w-14 h-14 bg-pink-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="floating-icon absolute bottom-12 left-12">
                                <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="floating-icon absolute top-20 left-12">
                                <div class="w-14 h-14 bg-cyan-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Diantara Explanation Section -->
    <div class="section-padding bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left - Product Mockups -->
                <div class="relative">
                    <div class="space-y-8">
                        <!-- Laptop Mockup -->
                        <div class="relative">
                            <div class="bg-gray-900 rounded-xl p-3 shadow-2xl">
                                <div class="bg-white rounded-lg p-4 md:p-6">
                                    <div class="text-center mb-4 md:mb-6">
                                        <h3 class="text-lg font-semibold text-gray-800">Experience Creator</h3>
                                    </div>
                                    <div class="rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                                        <img src="{{ asset('images/diantar.png') }}" 
                                             alt="Diantara Experience Creator" 
                                             class="w-full h-56 md:h-64 object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Mockup -->
                        <div class="relative ml-20">
                            <div class="bg-gray-900 rounded-2xl p-2 shadow-2xl w-52">
                                <div class="bg-white rounded-xl p-4">
                                    <div class="text-center mb-4">
                                        <h3 class="text-sm font-semibold text-gray-800">Customer</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="bg-gray-100 rounded-lg p-3 h-12 flex items-center justify-center">
                                            <div class="w-6 h-6 bg-primary/20 rounded"></div>
                                        </div>
                                        <div class="bg-gray-100 rounded-lg p-2 h-8 flex items-center justify-center">
                                            <div class="w-4 h-4 bg-primary/20 rounded"></div>
                                        </div>
                                        <div class="bg-gray-100 rounded-lg p-2 h-8 flex items-center justify-center">
                                            <div class="w-4 h-4 bg-primary/20 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Connection Line -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-0.5 bg-primary/40 border-t-2 border-dashed border-primary/60"></div>
                    </div>
                </div>

                <!-- Right - Text Content -->
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold text-gray-900">Diantara.</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Our solution is end-to-end, ranges from web and app-based event management, online booking and registration system to on-ground attendance management handling. Our features also supports promotion for your businesses and connect with your customers.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Break - People Image -->
    <div class="relative h-96 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/30 to-primary/20"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white">
                <h3 class="text-5xl font-bold mb-4">Connecting People</h3>
                <p class="text-2xl">Through Amazing Experiences</p>
            </div>
        </div>
    </div>

    <!-- Our Mission Section -->
    <div class="section-padding bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-12">Our Mission.</h2>
            <div class="space-y-8 text-lg text-gray-600 leading-relaxed">
                <p>
                    Our mission - <strong class="text-primary">Connecting People with Experience</strong> - is the premise that should be achieved by experience businesses. We believe fairness in partnership not only continuing growth on us, but our partners as well. That is why we "run" with them to growth, sustainability, hassle-free business process and be ready to overcome future disruptions. Our digital ecosystem is customer-centric, make them our ultimate priority.
                </p>
                <p>
                    We help drive innovation across industries: from entertainment, tourism to education, let's thrive together!
                </p>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="section-padding gradient-bg relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-4xl mx-auto px-6 text-center text-white">
            <h2 class="text-4xl font-bold mb-6">Be a part of Diantara Community!</h2>
            <p class="text-xl mb-10 leading-relaxed">
                Join a community with constant innovation and creativity, impacting experience business owners ranging from many industries: event management, MICE, travel and tourism and many more!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg">
                    Join Now
                </a>
                <a href="/catalog" class="border-2 border-white text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white hover:text-primary transition-all duration-300 shadow-lg">
                    Explore Events
                </a>
            </div>
        </div>
    </div>
    </div> <!-- End Main Content Wrapper -->

    @include('components.footer')
</body>
</html>
