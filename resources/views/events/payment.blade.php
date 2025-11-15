<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - {{ $event->title }} - {{ config('app.name', 'Diantara') }}</title>
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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.navigation')

    <!-- Main Content -->
    <div class="pt-8 pb-16 main-content">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="{{ route('events.show', $event) }}" class="hover:text-primary transition-colors">{{ $event->title }}</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-gray-900 font-medium">Payment</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Event Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Summary</h3>
                    
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

                    <!-- Price Summary -->
                    <div class="border-t pt-4">
                        <div id="priceSummary">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Ticket Price</span>
                                <span class="text-sm font-semibold text-gray-900" id="ticketPrice">IDR 0</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Service Fee</span>
                                <span class="text-sm font-semibold text-gray-900" id="serviceFee">IDR 0</span>
                            </div>
                            <div class="border-t pt-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-primary" id="totalPrice">IDR 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Purchase</h2>

                    <form action="#" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" id="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="ticket_type" id="ticketType" value="{{ request('ticket_type', 'regular') }}">
                        <input type="hidden" name="quantity" id="quantity" value="{{ request('quantity', 1) }}">
                        <input type="hidden" id="unit_price" value="{{ request('unit_price') }}">
                        <input type="hidden" id="service_fee_qs" value="{{ request('service_fee') }}">
                        <input type="hidden" id="total_price_qs" value="{{ request('total_price') }}">
                        
                        <!-- Personal Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" id="full_name" name="full_name" value="{{ Auth::user()->name }}" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" placeholder="+62 812 3456 7890" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">ID Number (KTP/NIK)</label>
                                    <input type="text" id="id_number" name="id_number" placeholder="1234567890123456" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="credit_card" class="text-primary focus:ring-primary" required>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-900">Credit/Debit Card</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Visa, Mastercard, JCB</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="text-primary focus:ring-primary" required>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="font-medium text-gray-900">Bank Transfer</span>
                                        </div>
                                        <p class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="e_wallet" class="text-primary focus:ring-primary" required>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-900">E-Wallet</span>
                                        </div>
                                        <p class="text-sm text-gray-500">GoPay, OVO, DANA, LinkAja</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-8">
                            <label class="flex items-start">
                                <input type="checkbox" name="terms" required class="mt-1 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-primary hover:text-primary-dark">Terms and Conditions</a> 
                                    and <a href="#" class="text-primary hover:text-primary-dark">Privacy Policy</a>
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex space-x-4">
                            <a href="{{ route('events.show', $event) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200 text-center">
                                Cancel
                            </a>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="flex-1 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                <span id="btnText">Complete Purchase</span>
                                <span id="btnLoader" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-2">Processing...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ticket pricing
        const qsUnit = parseInt(document.getElementById('unit_price').value || '0');
        const qsService = parseInt(document.getElementById('service_fee_qs').value || '0');
        const qsTotal = parseInt(document.getElementById('total_price_qs').value || '0');

        // Update pricing based on selected ticket
        function updatePricing() {
            const quantity = parseInt(document.getElementById('quantity').value);
            // Prefer querystring values from ticket page; fallback compute 3% fee
            const subtotal = (qsUnit && quantity) ? (qsUnit * quantity) : 0;
            const serviceFee = qsService || Math.round(subtotal * 0.03);
            const total = qsTotal || (subtotal + serviceFee);

            document.getElementById('ticketPrice').textContent = `IDR ${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('serviceFee').textContent = `IDR ${serviceFee.toLocaleString('id-ID')}`;
            document.getElementById('totalPrice').textContent = `IDR ${total.toLocaleString('id-ID')}`;
        }

        // Initialize pricing
        updatePricing();

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            const phone = document.getElementById('phone').value;
            const idNumber = document.getElementById('id_number').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            const terms = document.querySelector('input[name="terms"]');

            if (!phone || !idNumber || !paymentMethod || !terms.checked) {
                e.preventDefault();
                alert('Please fill in all required fields and accept the terms.');
                return;
            }

            // Phone number validation (Indonesian format)
            const phoneRegex = /^(\+62|62|0)[0-9]{9,13}$/;
            if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
                e.preventDefault();
                alert('Please enter a valid Indonesian phone number.');
                return;
            }

            // ID number validation (16 digits)
            if (idNumber.length !== 16 || !/^\d+$/.test(idNumber)) {
                e.preventDefault();
                alert('Please enter a valid 16-digit ID number.');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');

            e.preventDefault();

            try {
                const totalEl = document.getElementById('totalPrice').textContent.replace(/[^\d]/g,'');
                const amount = parseInt(totalEl || '0');
                const res = await fetch("{{ route('payment.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        event_id: document.getElementById('event_id').value,
                        participant_name: document.getElementById('full_name').value,
                        participant_email: document.getElementById('email').value,
                        participant_phone: phone,
                        amount: amount
                    })
                });
                const data = await res.json();
                if (!data.success) {
                    console.error('Create payment failed:', data);
                    throw new Error((data && (data.error || data.message)) || 'Failed to create payment');
                }

                // Open Midtrans Snap popup
                window.snap.pay(data.snap_token);
            } catch (err) {
                console.error('Payment error:', err);
                alert('Gagal memulai pembayaran: ' + (err && err.message ? err.message : err));
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            }
        });
    </script>
        </div>
    </div>

    @include('components.footer')
</body>
</html>
