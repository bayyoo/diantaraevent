@php
    use Illuminate\Support\Str;
    // If controller passed $tickets from partner, use them; otherwise create an empty collection
    $tickets = $tickets ?? collect();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select Tickets - {{ $event->title }} - {{ config('app.name', 'Diantara') }}</title>
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
        
        /* Fix scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Prevent horizontal overflow */
        body, html {
            overflow-x: hidden;
        }
        
        /* Ensure proper spacing for fixed navigation */
        .main-content {
            min-height: calc(100vh - 140px);
        }
        
        /* Ticket selection styles */
        .ticket-card {
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }
        
        .ticket-card.selected {
            border-color: #7681FF !important;
            border-width: 3px !important;
            background: linear-gradient(135deg, rgba(118, 129, 255, 0.1) 0%, rgba(118, 129, 255, 0.05) 100%) !important;
            box-shadow: 0 15px 35px rgba(118, 129, 255, 0.25), 0 0 0 1px rgba(118, 129, 255, 0.1) !important;
            transform: translateY(-3px) scale(1.02);
        }
        
        .ticket-card.selected .radio-indicator {
            opacity: 1 !important;
            background-color: #7681FF !important;
            width: 16px !important;
            height: 16px !important;
        }
        
        .ticket-card.selected .radio-container {
            border-color: #7681FF !important;
            border-width: 3px !important;
            background-color: #7681FF !important;
        }
        
        .ticket-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            border-color: #7681FF;
        }
        
        .ticket-card:active {
            transform: translateY(0px);
        }
        
        .selected-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #7681FF;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }
        
        .ticket-card.selected .selected-badge {
            opacity: 1;
            transform: scale(1);
        }

        /* Robust CSS: when the hidden radio is checked, style the visual circle and card */
        label > input[type="radio"]:checked + .ticket-card {
            border-color: #7681FF;
            border-width: 3px;
            background: linear-gradient(135deg, rgba(118,129,255,0.1) 0%, rgba(118,129,255,0.05) 100%);
            box-shadow: 0 15px 35px rgba(118,129,255,0.25), 0 0 0 1px rgba(118,129,255,0.1);
        }
        label > input[type="radio"]:checked + .ticket-card .radio-container {
            background-color: #7681FF !important;
            border-color: #7681FF !important;
            border-width: 3px !important;
        }
        label > input[type="radio"]:checked + .ticket-card .radio-indicator {
            opacity: 1 !important;
            background-color: #7681FF !important;
            width: 16px !important;
            height: 16px !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.navigation')

    <!-- Main Content -->
    <div class="pt-8 pb-16 main-content">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="{{ route('events.show', $event) }}" class="hover:text-primary transition-colors">{{ $event->title }}</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-gray-900 font-medium">Select Tickets</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Event Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                    
                    <!-- Event Image -->
                    <div class="mb-4">
                        @if($event->flyer_path)
                            <img src="{{ Storage::url($event->flyer_path) }}" alt="{{ $event->title }}" class="w-full h-32 object-cover rounded-lg">
                        @else
                            @php
                                $eventImages = [
                                    'Workshop Laravel untuk Pemula' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=300&fit=crop&crop=center',
                                    'Seminar Digital Marketing 2024' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=300&fit=crop&crop=center',
                                    'Kompetisi Programming Contest' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=300&fit=crop&crop=center',
                                    'Webinar UI/UX Design Trends' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=400&h=300&fit=crop&crop=center',
                                    'Bootcamp React Native' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=400&h=300&fit=crop&crop=center',
                                    'Tech Talk: AI & Machine Learning' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=300&fit=crop&crop=center',
                                    'SCREAM OK DANCE 2025' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&h=300&fit=crop&crop=center',
                                    'BERISIK ASIK Music Festival' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=400&h=300&fit=crop&crop=center',
                                    'SUCI FEST 2025' => 'https://images.unsplash.com/photo-1571266028243-e68f857f258a?w=400&h=300&fit=crop&crop=center',
                                    'Disney THE LITTLE MERMAID JR' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop&crop=center',
                                ];
                                $imageUrl = $eventImages[$event->title] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop&crop=center';
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $event->title }}" class="w-full h-32 object-cover rounded-lg">
                        @endif
                    </div>

                    <h4 class="font-semibold text-gray-900 mb-2">{{ $event->title }}</h4>
                    
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $event->event_date->format('d F Y') }}
                            @if($event->event_time)
                                - {{ $event->event_time->format('H:i') }} WIB
                            @endif
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $event->location }}
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-4" id="orderSummary" style="display: none;">
                        <h4 class="font-semibold text-gray-900 mb-3">Order Summary</h4>
                        <div id="ticketSummary"></div>
                        <div class="border-t pt-2 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-bold text-primary" id="totalPrice">IDR 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Ticket Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Your Tickets</h2>

                    <form id="ticketForm" action="{{ route('events.payment', $event) }}" method="GET">
                        <!-- Ticket Types -->
                        <div class="space-y-4 mb-8">
                            @if($tickets->isNotEmpty())
                                @foreach($tickets as $t)
                                    @php
                                        $label = $t->name;
                                        $desc = $t->description ?? '';
                                        $price = (int) $t->price;
                                        $badge = '';
                                        if (Str::contains(Str::lower($label), 'early')) $badge = 'LIMITED TIME';
                                        elseif (Str::contains(Str::lower($label), 'vip')) $badge = 'PREMIUM';
                                        elseif (Str::contains(Str::lower($label), 'regular')) $badge = 'POPULAR';
                                        $benefits = [];
                                        if (!empty($t->benefits)) {
                                            if (is_string($t->benefits)) {
                                                $decoded = json_decode($t->benefits, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $benefits = array_filter($decoded, fn($b) => is_string($b) && trim($b) !== '');
                                                }
                                            } elseif (is_array($t->benefits)) {
                                                $benefits = array_filter($t->benefits, fn($b) => is_string($b) && trim($b) !== '');
                                            }
                                        }
                                    @endphp
                                    <label class="block cursor-pointer">
                                        <input type="radio" name="ticket_type" value="{{ Str::slug($label) }}" class="sr-only" data-price="{{ $price }}" data-name="{{ $label }}">
                                        <div class="ticket-card border-2 border-gray-200 rounded-xl p-6 hover:border-primary hover:shadow-lg transition-all duration-200 bg-white relative">
                                            <div class="selected-badge">SELECTED</div>
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <h3 class="text-lg font-semibold text-gray-900 mr-3">{{ $label }}</h3>
                                                        @if($badge)
                                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">{{ $badge }}</span>
                                                        @endif
                                                    </div>
                                                    @if($desc)
                                                        <p class="text-sm text-gray-600 mb-3">{{ $desc }}</p>
                                                    @endif
                                                    @if(!empty($benefits))
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600">
                                                            @foreach($benefits as $b)
                                                                <span class="flex items-center">
                                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    {{ $b }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-right ml-6">
                                                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                                    <div class="mt-3">
                                                        <div class="radio-container w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                            <div class="radio-indicator w-3 h-3 rounded-full opacity-0 transition-opacity duration-200" style="background-color: #7681FF;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @else
                                @php
                                    // Fallback placeholder tiers if no tickets provided
                                    // Gunakan harga event sebagai dasar jika tersedia
                                    if ($event->price && $event->price > 0) {
                                        $basePrice = (int) $event->price;
                                    } else {
                                        // Jika belum ada price di event, gunakan seed berbasis ID seperti sebelumnya
                                        $seed = $event->id * 7;
                                        $basePrice = (($seed % 451) + 50) * 1000;
                                    }

                                    $earlyBirdPrice = round($basePrice * 0.75);
                                    $regularPrice = $basePrice;
                                    $vipPrice = round($basePrice * 1.75);
                                @endphp
                                <!-- Placeholder tiers fallback -->
                                <label class="block cursor-pointer">
                                    <input type="radio" name="ticket_type" value="early_bird" class="sr-only" data-price="{{ $earlyBirdPrice }}" data-name="Early Bird">
                                    <div class="ticket-card border-2 border-gray-200 rounded-xl p-6 hover:border-primary hover:shadow-lg transition-all duration-200 bg-white relative">
                                        <div class="selected-badge">SELECTED</div>
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">Early Bird</h3>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">LIMITED TIME</span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-3">Get your ticket early and save more! Limited time offer.</p>
                                            </div>
                                            <div class="text-right ml-6">
                                                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($earlyBirdPrice, 0, ',', '.') }}</div>
                                                <div class="text-sm text-gray-500 line-through">Rp {{ number_format($regularPrice, 0, ',', '.') }}</div>
                                                <div class="text-sm text-green-600 font-medium">Save 25%</div>
                                                <div class="mt-3">
                                                    <div class="radio-container w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                        <div class="radio-indicator w-3 h-3 rounded-full opacity-0 transition-opacity duration-200" style="background-color: #7681FF;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="ticket_type" value="regular" class="sr-only" data-price="{{ $regularPrice }}" data-name="Regular">
                                    <div class="ticket-card border-2 border-gray-200 rounded-xl p-6 hover:border-primary hover:shadow-lg transition-all duration-200 bg-white relative">
                                        <div class="selected-badge">SELECTED</div>
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">Regular</h3>
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">POPULAR</span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-3">Standard ticket with full access to the event.</p>
                                            </div>
                                            <div class="text-right ml-6">
                                                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($regularPrice, 0, ',', '.') }}</div>
                                                <div class="mt-3">
                                                    <div class="radio-container w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                        <div class="radio-indicator w-3 h-3 rounded-full opacity-0 transition-opacity duration-200" style="background-color: #7681FF;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="ticket_type" value="vip" class="sr-only" data-price="{{ $vipPrice }}" data-name="VIP">
                                    <div class="ticket-card border-2 border-gray-200 rounded-xl p-6 hover:border-primary hover:shadow-lg transition-all duration-200 bg-white relative">
                                        <div class="selected-badge">SELECTED</div>
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">VIP</h3>
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">PREMIUM</span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-3">Premium experience with exclusive benefits and perks.</p>
                                            </div>
                                            <div class="text-right ml-6">
                                                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($vipPrice, 0, ',', '.') }}</div>
                                                <div class="mt-3">
                                                    <div class="radio-container w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                        <div class="radio-indicator w-3 h-3 rounded-full opacity-0 transition-opacity duration-200" style="background-color: #7681FF;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        </div>

                        <!-- Quantity Selection -->
                        <div class="mb-8" id="quantitySection" style="display: none;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quantity</h3>
                            <div class="flex items-center space-x-4">
                                <button type="button" id="decreaseBtn" class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" 
                                       class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button type="button" id="increaseBtn" class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <span class="text-sm text-gray-600">tickets</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <a href="{{ route('events.show', $event) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200 text-center">
                                Back to Event
                            </a>
                             <button type="submit" id="continueBtn" 
                                     class="flex-1 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Continue to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ticket selection logic
        const ticketRadios = document.querySelectorAll('input[name="ticket_type"]');
        const quantitySection = document.getElementById('quantitySection');
        const continueBtn = document.getElementById('continueBtn');
        const orderSummary = document.getElementById('orderSummary');
        const ticketSummary = document.getElementById('ticketSummary');
        const totalPrice = document.getElementById('totalPrice');
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decreaseBtn');
        const increaseBtn = document.getElementById('increaseBtn');

        let selectedTicket = null;

         // Handle ticket selection
         ticketRadios.forEach(radio => {
             radio.addEventListener('change', function() {
                 if (this.checked) {
                     selectedTicket = {
                         type: this.value,
                         name: this.dataset.name,
                         price: parseInt(this.dataset.price)
                     };
                     quantitySection.style.display = 'block';
                     orderSummary.style.display = 'block';
                     continueBtn.disabled = false;
                     continueBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                     updateOrderSummary();
                     updateVisualSelection();
                 }
             });
         });

         // Handle click on ticket cards - more robust approach
         document.querySelectorAll('.ticket-card').forEach(card => {
             card.addEventListener('click', function(e) {
                 // Prevent if clicking on radio directly (let it handle naturally)
                 if (e.target.type === 'radio') return;
                 
                 // Find the radio input within this card
                 const radio = this.querySelector('input[type="radio"]');
                 if (radio) {
                     // Uncheck all radios first
                     ticketRadios.forEach(r => r.checked = false);
                     
                     // Check this radio
                     radio.checked = true;
                     
                     // Trigger change event
                     const event = new Event('change', { bubbles: true });
                     radio.dispatchEvent(event);
                 }
             });
         });

         // Initialize disabled state
         continueBtn.classList.add('opacity-50', 'cursor-not-allowed');

         // Update visual selection
         function updateVisualSelection() {
             // Remove selected state from all cards
             document.querySelectorAll('.ticket-card').forEach(card => {
                 card.classList.remove('selected');
                 
                 // Reset radio appearance
                 const indicator = card.querySelector('.radio-indicator');
                 const container = card.querySelector('.radio-container');
                 if (indicator) {
                     indicator.style.opacity = '0';
                 }
                 if (container) {
                     container.style.backgroundColor = 'white';
                     container.style.borderColor = '#d1d5db';
                     container.style.borderWidth = '2px';
                 }
             });

             // Add selected state to current card
             const selectedRadio = document.querySelector('input[name="ticket_type"]:checked');
             if (selectedRadio) {
                 const selectedCard = selectedRadio.closest('.ticket-card');
                 selectedCard.classList.add('selected');
                 
                 // Force radio appearance
                 const indicator = selectedCard.querySelector('.radio-indicator');
                 const container = selectedCard.querySelector('.radio-container');
                 if (indicator) {
                     indicator.style.opacity = '1';
                     indicator.style.backgroundColor = '#7681FF';
                     indicator.style.width = '16px';
                     indicator.style.height = '16px';
                 }
                 if (container) {
                     container.style.backgroundColor = '#7681FF';
                     container.style.borderColor = '#7681FF';
                     container.style.borderWidth = '3px';
                 }
             }
         }

        // Handle quantity changes
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateOrderSummary();
            }
        });

        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
                updateOrderSummary();
            }
        });

        quantityInput.addEventListener('change', function() {
            const value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 10) this.value = 10;
            updateOrderSummary();
        });

        // Update order summary
        function updateOrderSummary() {
            if (selectedTicket) {
                const quantity = parseInt(quantityInput.value);
                const subtotal = selectedTicket.price * quantity;
                const serviceFee = Math.round(subtotal * 0.03); // 3% service fee
                const total = subtotal + serviceFee;

                ticketSummary.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">${selectedTicket.name} × ${quantity}</span>
                        <span class="text-sm font-semibold text-gray-900">Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Service Fee</span>
                        <span class="text-sm font-semibold text-gray-900">Rp ${serviceFee.toLocaleString('id-ID')}</span>
                    </div>
                `;

                totalPrice.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            }
        }

        // Form submission
        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            if (!selectedTicket) {
                e.preventDefault();
                alert('Please select a ticket type.');
                return;
            }
            
            // Add hidden inputs to form to pass data to payment page
            const form = this;
            
            // Remove existing hidden inputs if any
            const existingInputs = form.querySelectorAll('input[type="hidden"]');
            existingInputs.forEach(input => input.remove());
            
            // Add ticket type
            const ticketTypeInput = document.createElement('input');
            ticketTypeInput.type = 'hidden';
            ticketTypeInput.name = 'ticket_type';
            ticketTypeInput.value = selectedTicket.type;
            form.appendChild(ticketTypeInput);
            
            // Add ticket name
            const ticketNameInput = document.createElement('input');
            ticketNameInput.type = 'hidden';
            ticketNameInput.name = 'ticket_name';
            ticketNameInput.value = selectedTicket.name;
            form.appendChild(ticketNameInput);
            
            // Add unit price
            const unitPriceInput = document.createElement('input');
            unitPriceInput.type = 'hidden';
            unitPriceInput.name = 'unit_price';
            unitPriceInput.value = selectedTicket.price;
            form.appendChild(unitPriceInput);
            
            // Add total price
            const quantity = parseInt(quantityInput.value);
            const subtotal = selectedTicket.price * quantity;
            const serviceFee = Math.round(subtotal * 0.03);
            const total = subtotal + serviceFee;
            
            const totalPriceInput = document.createElement('input');
            totalPriceInput.type = 'hidden';
            totalPriceInput.name = 'total_price';
            totalPriceInput.value = total;
            form.appendChild(totalPriceInput);
            
            // Add service fee
            const serviceFeeInput = document.createElement('input');
            serviceFeeInput.type = 'hidden';
            serviceFeeInput.name = 'service_fee';
            serviceFeeInput.value = serviceFee;
            form.appendChild(serviceFeeInput);
        });
    </script>
        </div>
    </div>

    @include('components.footer')
</body>
</html>
