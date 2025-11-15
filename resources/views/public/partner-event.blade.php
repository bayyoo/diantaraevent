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
        body { font-family: 'Inter', system-ui, sans-serif; }
        @keyframes scale-in { from {opacity:0;transform:scale(0.9);} to {opacity:1;transform:scale(1);} }
        .animate-scale-in { animation: scale-in 0.3s ease-out; }
        html, body { margin:0 !important; padding:0 !important; background-color:#ffffff !important; overflow-x:hidden !important; }
        footer { margin-bottom:-100px !important; padding-bottom:100px !important; }
        body::after { content:''; display:block; height:0; background:#ffffff; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen overflow-x-hidden m-0 p-0">
    @include('components.navigation')

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
                    <p class="text-gray-600 mb-6">by {{ $event->organization->name ?? $event->partner->name ?? 'Organizer' }}</p>

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
                    </div>

                    <!-- Date & Time -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M \'y H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M \'y H:i') }} WIB
                        </h3>
                    </div>

                    <!-- Share Buttons -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Share this event</h3>
                        <div class="flex space-x-3">
                            <a href="#" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- About this experience -->
                    @if($event->description)
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">About this experience</h3>
                            <div class="text-gray-600 prose max-w-none">{!! nl2br(e($event->description)) !!}</div>
                        </div>
                    @endif

                    <!-- Reviews & Ratings -->
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Reviews & Ratings</h3>
                            @auth
                                <button onclick="document.getElementById('partnerReviewForm').scrollIntoView({behavior:'smooth'})" class="text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">
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
                                <div class="text-sm text-gray-600 mt-1">Based on {{ $totalReviews }} {{ \Illuminate\Support\Str::plural('review', $totalReviews) }}</div>
                            </div>
                            <div class="flex-1">
                                @foreach([5,4,3,2,1] as $star)
                                    <div class="flex items-center mb-1">
                                        <span class="text-sm text-gray-600 w-8">{{ $star }}</span>
                                        <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $ratingDistribution[$star]['percentage'] ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2 w-8">{{ $ratingDistribution[$star]['count'] ?? 0 }}</span>
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
                                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex text-yellow-400 mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <p class="text-gray-600 text-sm">{{ $review->comment }}</p>

                                            @auth
                                                @if($review->user_id === auth()->id())
                                                    <div class="mt-2 flex items-center space-x-2">
                                                        <form action="{{ route('public.partner-events.review.destroy', [$event, $review]) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">Delete</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endauth
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

                        @auth
                            <!-- Write Review Form -->
                            <div id="partnerReviewForm" class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-3">Write a Review</h4>
                                <form method="POST" action="{{ route('public.partner-events.review.store', $event) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="text-sm text-gray-700">Rating</label>
                                        <select name="rating" class="w-48 border border-gray-300 rounded px-3 py-2 text-sm">
                                            @for($i=5;$i>=1;$i--)
                                                <option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-700">Comment</label>
                                        <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Tulis pengalamanmu..."></textarea>
                                    </div>
                                    <button type="submit" class="text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="background-color: #7882FF;" onmouseover="this.style.backgroundColor='#6270E8'" onmouseout="this.style.backgroundColor='#7882FF'">Submit Review</button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking Section -->
            <div class="lg:col-span-1">
                <div>
                    <!-- Event Image -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                        @if($event->poster)
                            <img src="{{ Storage::url($event->poster) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                        @endif
                        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 text-sm font-medium transition-colors">View all pictures</button>
                    </div>

                    <!-- Price & Booking -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        @php $isFree = ($minPrice ?? 0) == 0; @endphp
                        <div class="mb-4">
                            @if($isFree)
                                <div class="bg-green-50 border-2 border-green-400 rounded-xl p-4 text-center">
                                    <div class="text-sm text-green-600 font-medium mb-1">Event Gratis</div>
                                    <div class="text-3xl font-bold text-green-600">FREE</div>
                                    <div class="text-sm text-green-600 mt-1">Tanpa biaya pendaftaran</div>
                                </div>
                            @else
                                <div class="text-sm text-gray-600 mb-1">Mulai dari</div>
                                <div class="text-3xl font-bold" style="color:#7882FF;">Rp {{ number_format($minPrice, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500 mt-1">per tiket</div>
                            @endif
                        </div>

                        <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center mb-2">
                            Daftar / Beli Tiket
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    @include('components.footer')
</body>
</html>
