<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - {{ config('app.name', 'Diantara') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '#7f4ca5',
                        'primary-dark': '#6b3d8a',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        
        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .animate-scale-in {
            animation: scale-in 0.3s ease-out;
        }
        
        /* ULTIMATE WHITE SPACE KILLER */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #111827 !important; /* Dark gray-900 */
            overflow-x: hidden !important;
        }
        
        /* Force footer to extend beyond viewport */
        footer {
            margin-bottom: -100px !important;
            padding-bottom: 100px !important;
        }
        
        /* Ensure body ends with footer */
        body::after {
            content: '';
            display: block;
            height: 0;
            background: #111827;
        }
    </style>
</head>
<body class="bg-gray-900 flex flex-col min-h-screen overflow-x-hidden m-0 p-0">
    @include('components.navigation')

    <!-- Success Modal -->
    @if (session('success'))
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 overflow-hidden animate-scale-in">
            <div class="p-8 text-center">
                @php
                    $message = session('success');
                    $isReview = str_contains($message, 'review') || str_contains($message, 'Review');
                @endphp
                
                @if($isReview)
                    <!-- Review Success -->
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #FEF3C7;">
                        <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Review Berhasil!</h3>
                    <p class="text-gray-600 text-sm mb-6">{{ $message }}</p>
                @else
                    <!-- Registration Success -->
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #E8EAFF;">
                        <svg class="w-12 h-12" style="color: #7882FF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h3>
                    <p class="text-gray-600 text-sm mb-6">Token absensi telah dikirim ke email Anda</p>
                @endif
                
                <button onclick="closeModal()" class="w-full text-white font-semibold py-3 px-6 rounded-xl transition duration-200" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 overflow-hidden animate-scale-in">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Oops!</h3>
                <p class="text-gray-600 text-sm mb-6">{{ session('error') }}</p>
                <button onclick="closeErrorModal()" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="pt-8 pb-16 flex-grow bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="{{ route('catalog.index') }}" class="hover:text-primary transition-colors">Events</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-gray-900 font-medium">{{ $event->title }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Event Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Title & Organizer -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                    @php
                        $organizerName = $event->creator->name ?? ($event->organization->name ?? ($event->partner->name ?? 'Organizer'));
                    @endphp
                    <p class="text-gray-600 mb-6">by {{ $organizerName }}</p>
                    <!-- Location -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $event->location }}
                        </h3>
                        <p class="text-gray-600 text-sm">{{ $event->location }}</p>
                        <a href="#" class="text-primary hover:text-primary-dark text-sm font-medium">View on Map</a>
                    </div>

                    <!-- Date & Time -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            {{ $event->event_date->format('d M \'y') }}
                            @if($event->event_time)
                                - {{ $event->event_time->format('H:i') }} WIB
                            @endif
                        </h3>
                        <a href="#" class="text-primary hover:text-primary-dark text-sm font-medium">View Schedules</a>
                    </div>

                    <!-- Share Buttons -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Share this event</h3>
                        <div class="flex space-x-3">
                            <a href="#" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- About this experience -->
                @if($event->description)
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">About this experience</h3>
                        <div class="text-gray-600 prose max-w-none">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                        <a href="#" class="text-primary hover:text-primary-dark text-sm font-medium mt-2 inline-block">Read More</a>
                    </div>
                @endif

                <!-- Tags -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Entertainment & Performance</span>
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Workshop</span>
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Technology</span>
                    </div>
                </div>

                <!-- When you book on Diantara -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">When you book on Diantara</h3>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <span class="text-sm">You'll Receive Diantara e-Ticket</span>
                    </div>
                </div>

                <!-- Created by -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Created by</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: #7882FF;">
                                <span class="text-white font-semibold">{{ substr($organizerName, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $organizerName }}</p>
                                <p class="text-sm text-gray-600">285 followers - 6 experiences</p>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600 ml-1">4.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-sm font-medium" style="color: #7882FF;" onmouseover="this.style.color='#6270E8'" onmouseout="this.style.color='#7882FF'">View profile</button>
                            <button class="text-white px-3 py-1 rounded text-sm transition-colors" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">Follow</button>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Reviews & Ratings</h3>
                        @auth
                            <button onclick="document.getElementById('reviewModal').classList.remove('hidden')" class="text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">
                                Write a Review
                            </button>
                        @endauth
                    </div>

                    <!-- Rating Summary -->
                    <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
                        <div class="text-center mr-8">
                            <div class="text-4xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</div>
                            <div class="flex text-yellow-400 mt-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Based on {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</div>
                        </div>
                        <div class="flex-1">
                            @foreach([5,4,3,2,1] as $star)
                                <div class="flex items-center mb-1">
                                    <span class="text-sm text-gray-600 w-8">{{ $star }}</span>
                                    <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $ratingDistribution[$star]['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 ml-2 w-8">{{ $ratingDistribution[$star]['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-6">
                        @forelse($reviews as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-0">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: #7882FF;">
                                        <span class="text-white font-semibold">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                @auth
                                                    @if($review->user_id === auth()->id())
                                                        <button onclick="editReview({{ $review->id }}, {{ $review->rating }}, '{{ addslashes($review->comment) }}')" class="text-sm hover:text-purple-600 transition-colors" style="color: #7882FF;">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <form action="{{ route('events.review.destroy', [$event, $review]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No reviews yet. Be the first to review this event!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking Section -->
            <div class="lg:col-span-1">
                <div>
                    <!-- Event Image -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                        @if($event->flyer_path)
                            <img src="{{ asset('storage/' . $event->flyer_path) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover" onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop'">
                        @else
                            @php
                                $eventImages = [
                                    'Workshop Laravel untuk Pemula' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&h=400&fit=crop&crop=center',
                                    'Seminar Digital Marketing 2024' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&crop=center',
                                    'Kompetisi Programming Contest' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                    'Webinar UI/UX Design Trends' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                                    'Bootcamp React Native' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop&crop=center',
                                    'Tech Talk: AI & Machine Learning' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop&crop=center',
                                    'SCREAM OK DANCE 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&crop=center',
                                    'BERISIK ASIK Music Festival' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                    'SUCI FEST 2025' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=600&h=400&fit=crop&crop=center',
                                    'Disney THE LITTLE MERMAID JR' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                    'COOKING COMPETITION' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=center',
                                    'FESTIVAL KULINER NUSANTARA' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&h=400&fit=crop&crop=center',
                                    'ROBOTICS COMPETITION' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=600&h=400&fit=crop&crop=center',
                                    'FESTIVAL BUDAYA BALI' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&crop=center',
                                    'GAMING COMPETITION 2025' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                    'FUTSAL CHAMPIONSHIP' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&h=400&fit=crop&crop=center',
                                    'ART EXHIBITION: Modern Indonesian Art' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01988ec?w=600&h=400&fit=crop&crop=center',
                                    'PHOTOGRAPHY EXHIBITION' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&crop=center',
                                    'BOOK FAIR & LITERATURE FESTIVAL' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=600&h=400&fit=crop&crop=center',
                                ];
                                $imageUrl = $eventImages[$event->title] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop&crop=center';
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $event->title }}" class="w-full h-64 object-cover" onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop'">
                        @endif
                        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 text-sm font-medium transition-colors">
                            View all pictures (2)
                        </button>
                    </div>

                    <!-- Price & Booking -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        @php
                            $isFree = !$event->price || $event->price == 0;
                        @endphp
                        <div class="mb-4">
                            @if($isFree)
                                <div class="bg-green-50 border-2 border-green-400 rounded-xl p-4 text-center">
                                    <div class="text-sm text-green-600 font-medium mb-1">Event Gratis</div>
                                    <div class="text-3xl font-bold text-green-600">FREE</div>
                                    <div class="text-sm text-green-600 mt-1">Tanpa biaya pendaftaran</div>
                                </div>
                            @else
                                <div class="text-sm text-gray-600 mb-1">Mulai dari</div>
                                <div class="text-3xl font-bold" style="color: #7882FF;">Rp {{ number_format($event->price, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500 mt-1">per tiket</div>
                            @endif
                        </div>
                        
                        <!-- Buy Tickets Button -->
                        @auth
                            @php
                                $userRegistrations = \App\Models\Participant::where('user_id', auth()->id())
                                    ->where('event_id', $event->id)
                                    ->count();
                                $canRegisterAgain = $event->allow_multiple_registration && 
                                    (!$event->max_registrations_per_user || $userRegistrations < $event->max_registrations_per_user);
                            @endphp
                            
                            @if($isRegistered)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <div>
                                                @if(isset($hasAttended) && $hasAttended)
                                                    <span class="text-green-800 font-medium">Kamu sudah melakukan absensi untuk event ini</span>
                                                @else
                                                    <span class="text-green-800 font-medium">You're registered for this event</span>
                                                @endif
                                                @if($event->allow_multiple_registration)
                                                    <p class="text-sm text-green-600 mt-1">
                                                        Registered: {{ $userRegistrations }} time(s)
                                                        @if($event->max_registrations_per_user)
                                                            (Max: {{ $event->max_registrations_per_user }})
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($canRegisterAgain)
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Can register again</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($canRegisterAgain)
                                    @php
                                        $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00'));
                                        $now = \Carbon\Carbon::now();
                                        $registrationOpen = $now->lt($eventDateTime);
                                    @endphp
                                    
                                    @if($registrationOpen)
                                        @if($isFree)
                                            <form action="{{ route('events.register', ['event' => $event->id]) }}" method="POST" class="mb-4">
                                                @csrf
                                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Daftar Lagi (Gratis)
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('events.tickets', ['event' => $event->id]) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center mb-4">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Beli Tiket Lagi
                                            </a>
                                        @endif
                                    @endif
                                @endif
                        @else
                            @php
                                $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00'));
                                $now = \Carbon\Carbon::now();
                                $registrationOpen = $now->lt($eventDateTime);
                            @endphp

                            @if($registrationOpen)
                                @if($isFree)
                                    <form action="{{ route('events.register', ['event' => $event->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Daftar Sekarang (Gratis)
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('events.tickets', ['event' => $event->id]) }}" class="w-full text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Beli Tiket
                                    </a>
                                @endif
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                            <span class="text-red-800 font-medium">Registration Closed</span>
                                        </div>
                                    </div>
                                @endif
                        @endif
                    @else
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Login Required</h3>
                                        <p class="text-blue-800 mb-4">
                                            You need to be logged in to buy tickets for this event.
                                        </p>
                                        <div class="flex space-x-3">
                                            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                                                Login
                                            </a>
                                            <a href="{{ route('register') }}" class="bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                                                Register
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div> <!-- End max-w-7xl container -->
    </div> <!-- End main content flex-grow -->

    <div style="width: 100vw; margin-left: calc(-50vw + 50%);">
        @include('components.footer')
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Write a Review</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="reviewForm" action="{{ route('events.review.store', $event) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="reviewMethod" name="_method" value="POST">
                <input type="hidden" id="reviewId" value="">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Your Rating</label>
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-btn">
                                <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0">
                </div>

                <!-- Comment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Your Review</label>
                    <textarea id="commentInput" name="comment" rows="4" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Share your experience..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-3">
                    <button type="button" onclick="closeReviewModal()" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 px-4 py-3 text-white rounded-lg font-medium transition-colors" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setRating(rating) {
            document.getElementById('ratingInput').value = rating;
            const stars = document.querySelectorAll('.star-btn svg');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
        
        function editReview(reviewId, rating, comment) {
            // Set modal title
            document.getElementById('modalTitle').textContent = 'Edit Review';
            
            // Set form action and method
            const form = document.getElementById('reviewForm');
            form.action = '{{ route('events.show', $event) }}'.replace('/{{ $event->id }}', '/{{ $event->id }}/review/' + reviewId);
            document.getElementById('reviewMethod').value = 'PUT';
            document.getElementById('reviewId').value = reviewId;
            
            // Set rating
            setRating(rating);
            
            // Set comment
            document.getElementById('commentInput').value = comment;
            
            // Change button text
            document.getElementById('submitBtn').textContent = 'Update Review';
            
            // Show modal
            document.getElementById('reviewModal').classList.remove('hidden');
        }
        
        function closeReviewModal() {
            // Reset form
            document.getElementById('modalTitle').textContent = 'Write a Review';
            document.getElementById('reviewForm').action = '{{ route('events.review.store', $event) }}';
            document.getElementById('reviewMethod').value = 'POST';
            document.getElementById('reviewId').value = '';
            document.getElementById('ratingInput').value = '0';
            document.getElementById('commentInput').value = '';
            document.getElementById('submitBtn').textContent = 'Submit Review';
            
            // Reset stars
            const stars = document.querySelectorAll('.star-btn svg');
            stars.forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            
            // Hide modal
            document.getElementById('reviewModal').classList.add('hidden');
        }

        // Modal functions
        function closeModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.style.opacity = '0';
                setTimeout(() => modal.remove(), 300);
            }
        }
        
        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.style.opacity = '0';
                setTimeout(() => modal.remove(), 300);
            }
        }
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeErrorModal();
            }
        });
        
        // Close modal on backdrop click
        document.addEventListener('click', function(e) {
            if (e.target.id === 'successModal' || e.target.id === 'errorModal') {
                closeModal();
                closeErrorModal();
            }
        });
    </script>
</body>
</html>
