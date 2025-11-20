@extends('partner.layout')

@section('title', 'Create Event - Step 2')
@section('page-title', 'Create New Event')
@section('page-subtitle', 'Step 2 of 3: Ticket Configuration')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <span class="ml-3 text-sm text-green-600">Basic Information</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 nexus-gradient rounded-full flex items-center justify-center text-white font-semibold text-sm">2</div>
                <span class="ml-3 text-sm font-medium text-nexus">Tickets</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">3</div>
                <span class="ml-3 text-sm text-gray-500">Media & Finalize</span>
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="nexus-gradient h-2 rounded-full" style="width: 66.66%"></div>
        </div>
    </div>

    <!-- Event Summary Card -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                <p class="text-sm text-gray-600">{{ $event->start_date->format('M d, Y \a\t H:i') }} - {{ $event->end_date->format('M d, Y \a\t H:i') }}</p>
                <p class="text-sm text-gray-600">{{ $event->location }}</p>
            </div>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Draft</span>
        </div>
    </div>

    <!-- Tickets Configuration -->
    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Configure Your Tickets</h2>
            <p class="text-gray-600">Set up different ticket types with various pricing and benefits. You can create multiple ticket tiers to maximize revenue.</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('diantaranexus.events.create.step2.store', $event->id) }}" id="ticketForm">
            @csrf

            <!-- Tickets Container -->
            <div id="ticketsContainer" class="space-y-6">
                @if($tickets->count() > 0)
                    @foreach($tickets as $index => $ticket)
                        <div class="ticket-item border border-gray-200 rounded-xl p-6 bg-gray-50" data-ticket-index="{{ $index }}">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-900">Ticket Type {{ $index + 1 }}</h4>
                                @if($index > 0)
                                    <button type="button" onclick="removeTicket(this)" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Ticket Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ticket Name *</label>
                                    <input type="text" 
                                           name="tickets[{{ $index }}][name]" 
                                           value="{{ old('tickets.'.$index.'.name', $ticket->name) }}"
                                           placeholder="e.g., Early Bird, VIP, Regular"
                                           required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                                </div>

                                <!-- Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR) *</label>
                                    <input type="text" 
                                           name="tickets[{{ $index }}][price]" 
                                           value="{{ old('tickets.'.$index.'.price', $ticket->price) }}"
                                           placeholder="0"
                                           required 
                                           class="price-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Quantity *</label>
                                    <input type="number" 
                                           name="tickets[{{ $index }}][quantity]" 
                                           value="{{ old('tickets.'.$index.'.quantity', $ticket->quantity) }}"
                                           min="1" 
                                           placeholder="100"
                                           required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                                </div>

                                <!-- Sale Start -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Start Date *</label>
                                    <input type="datetime-local" 
                                           name="tickets[{{ $index }}][sale_start]" 
                                           value="{{ old('tickets.'.$index.'.sale_start', $ticket->sale_start ? $ticket->sale_start->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                                           required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                                </div>

                                <!-- Sale End -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale End Date *</label>
                                    <input type="datetime-local" 
                                           name="tickets[{{ $index }}][sale_end]" 
                                           value="{{ old('tickets.'.$index.'.sale_end', $ticket->sale_end ? $ticket->sale_end->format('Y-m-d\TH:i') : $event->start_date->format('Y-m-d\TH:i')) }}"
                                           required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="tickets[{{ $index }}][description]" 
                                              rows="3" 
                                              placeholder="Brief description of this ticket type"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">{{ old('tickets.'.$index.'.description', $ticket->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Benefits Section -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Ticket Benefits (Optional)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 benefits-grid">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tickets[{{ $index }}][benefits][]" value="Access to all sessions" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                        <span class="ml-2 text-sm text-gray-700">Access to all sessions</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tickets[{{ $index }}][benefits][]" value="Welcome kit" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                        <span class="ml-2 text-sm text-gray-700">Welcome kit</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tickets[{{ $index }}][benefits][]" value="Lunch included" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                        <span class="ml-2 text-sm text-gray-700">Lunch included</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tickets[{{ $index }}][benefits][]" value="Certificate" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                        <span class="ml-2 text-sm text-gray-700">Certificate</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tickets[{{ $index }}][benefits][]" value="Networking session" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                        <span class="ml-2 text-sm text-gray-700">Networking session</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tickets[{{ $index }}][benefits][]" value="Priority seating" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                        <span class="ml-2 text-sm text-gray-700">Priority seating</span>
                                    </label>
                                    <div class="md:col-span-2 lg:col-span-3 flex flex-col sm:flex-row gap-2 items-stretch mt-2">
                                        <input type="text" class="custom-benefit-input w-full px-3 py-2 border border-dashed border-gray-300 rounded-lg text-sm" placeholder="Tambah benefit lain (mis. Snack, Meet & Greet)">
                                        <button type="button" class="add-custom-benefit px-4 py-2 border border-nexus text-nexus rounded-lg text-sm hover:bg-nexus hover:text-white transition-all">+ Tambah Benefit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default First Ticket -->
                    <div class="ticket-item border border-gray-200 rounded-xl p-6 bg-gray-50" data-ticket-index="0">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">Ticket Type 1</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Ticket Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ticket Name *</label>
                                <input type="text" 
                                       name="tickets[0][name]" 
                                       value="{{ old('tickets.0.name', 'Regular') }}"
                                       placeholder="e.g., Early Bird, VIP, Regular"
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR) *</label>
                                <input type="text" 
                                       name="tickets[0][price]" 
                                       value="{{ old('tickets.0.price', '100000') }}"
                                       placeholder="0"
                                       required 
                                       class="price-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available Quantity *</label>
                                <input type="number" 
                                       name="tickets[0][quantity]" 
                                       value="{{ old('tickets.0.quantity', '100') }}"
                                       min="1" 
                                       placeholder="100"
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            </div>

                            <!-- Sale Start -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sale Start Date *</label>
                                <input type="datetime-local" 
                                       name="tickets[0][sale_start]" 
                                       value="{{ old('tickets.0.sale_start', now()->format('Y-m-d\TH:i')) }}"
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            </div>

                            <!-- Sale End -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sale End Date *</label>
                                <input type="datetime-local" 
                                       name="tickets[0][sale_end]" 
                                       value="{{ old('tickets.0.sale_end', $event->start_date->format('Y-m-d\TH:i')) }}"
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2 lg:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="tickets[0][description]" 
                                          rows="3" 
                                          placeholder="Brief description of this ticket type"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">{{ old('tickets.0.description') }}</textarea>
                            </div>
                        </div>

                        <!-- Benefits Section -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Ticket Benefits (Optional)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 benefits-grid">
                                <label class="flex items-center">
                                    <input type="checkbox" name="tickets[0][benefits][]" value="Access to all sessions" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                    <span class="ml-2 text-sm text-gray-700">Access to all sessions</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tickets[0][benefits][]" value="Welcome kit" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                    <span class="ml-2 text-sm text-gray-700">Welcome kit</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tickets[0][benefits][]" value="Lunch included" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                    <span class="ml-2 text-sm text-gray-700">Lunch included</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tickets[0][benefits][]" value="Certificate" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                    <span class="ml-2 text-sm text-gray-700">Certificate</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tickets[0][benefits][]" value="Networking session" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                    <span class="ml-2 text-sm text-gray-700">Networking session</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tickets[0][benefits][]" value="Priority seating" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                                    <span class="ml-2 text-sm text-gray-700">Priority seating</span>
                                </label>
                                <div class="md:col-span-2 lg:col-span-3 flex flex-col sm:flex-row gap-2 items-stretch mt-2">
                                    <input type="text" class="custom-benefit-input w-full px-3 py-2 border border-dashed border-gray-300 rounded-lg text-sm" placeholder="Tambah benefit lain (mis. Snack, Meet & Greet)">
                                    <button type="button" class="add-custom-benefit px-4 py-2 border border-nexus text-nexus rounded-lg text-sm hover:bg-nexus hover:text-white transition-all">+ Tambah Benefit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Add Ticket Button -->
            <div class="mt-6 text-center">
                <button type="button" 
                        onclick="addTicket()" 
                        class="inline-flex items-center px-6 py-3 border border-nexus text-nexus rounded-lg hover:bg-nexus hover:text-white transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    Add Another Ticket Type
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-200 mt-8">
                <a href="{{ route('diantaranexus.events.create') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Basic Info
                </a>
                
                <button type="submit" 
                        class="nexus-gradient text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition-all transform hover:scale-105">
                    Continue to Media
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Pricing Tips -->
    <div class="mt-6 bg-blue-50 rounded-2xl p-6 border border-blue-200">
        <div class="flex items-start space-x-4">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-blue-900 mb-2">Ticket Pricing Strategy Tips</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• <strong>Early Bird:</strong> Offer 20-30% discount for early purchases</li>
                    <li>• <strong>Regular:</strong> Standard pricing for general admission</li>
                    <li>• <strong>VIP/Premium:</strong> 2-3x regular price with exclusive benefits</li>
                    <li>• <strong>Group Discounts:</strong> Consider bulk pricing for organizations</li>
                    <li>• <strong>Limited Quantities:</strong> Create urgency with limited availability</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let ticketIndex = {{ $tickets->count() > 0 ? $tickets->count() : 1 }};

function addTicket() {
    const container = document.getElementById('ticketsContainer');
    const ticketHtml = `
        <div class="ticket-item border border-gray-200 rounded-xl p-6 bg-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Ticket Type ${ticketIndex + 1}</h4>
                <button type="button" onclick="removeTicket(this)" class="text-red-600 hover:text-red-800 text-sm">
                    <i class="fas fa-trash mr-1"></i> Remove
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ticket Name *</label>
                    <input type="text" name="tickets[${ticketIndex}][name]" placeholder="e.g., VIP, Premium" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR) *</label>
                    <input type="text" name="tickets[${ticketIndex}][price]" placeholder="0" required 
                           class="price-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Quantity *</label>
                    <input type="number" name="tickets[${ticketIndex}][quantity]" min="1" placeholder="100" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Start Date *</label>
                    <input type="datetime-local" name="tickets[${ticketIndex}][sale_start]" value="${new Date().toISOString().slice(0, 16)}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale End Date *</label>
                    <input type="datetime-local" name="tickets[${ticketIndex}][sale_end]" value="{{ $event->start_date->format('Y-m-d\TH:i') }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                </div>
                <div class="md:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="tickets[${ticketIndex}][description]" rows="3" placeholder="Brief description"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all"></textarea>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Ticket Benefits (Optional)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="tickets[${ticketIndex}][benefits][]" value="Access to all sessions" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                        <span class="ml-2 text-sm text-gray-700">Access to all sessions</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="tickets[${ticketIndex}][benefits][]" value="Welcome kit" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                        <span class="ml-2 text-sm text-gray-700">Welcome kit</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="tickets[${ticketIndex}][benefits][]" value="Lunch included" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                        <span class="ml-2 text-sm text-gray-700">Lunch included</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="tickets[${ticketIndex}][benefits][]" value="Certificate" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                        <span class="ml-2 text-sm text-gray-700">Certificate</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="tickets[${ticketIndex}][benefits][]" value="Networking session" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                        <span class="ml-2 text-sm text-gray-700">Networking session</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="tickets[${ticketIndex}][benefits][]" value="Priority seating" class="rounded border-gray-300 text-nexus focus:ring-nexus">
                        <span class="ml-2 text-sm text-gray-700">Priority seating</span>
                    </label>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', ticketHtml);
    ticketIndex++;

    // apply formatting to new price inputs
    initPriceInputs();
}

function removeTicket(button) {
    button.closest('.ticket-item').remove();
}

function formatRupiah(value) {
    const numeric = value.replace(/[^0-9]/g, '');
    if (!numeric) return '';

    return numeric.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function initPriceInputs() {
    const priceInputs = document.querySelectorAll('.price-input');
    priceInputs.forEach((input) => {
        // initial format
        if (input.dataset.formatted !== '1') {
            if (input.value) {
                input.value = formatRupiah(input.value);
            }
            input.dataset.formatted = '1';
        }

        input.addEventListener('input', function () {
            this.value = formatRupiah(this.value);
        });
    });
}

// on submit, strip dots so backend receives plain numbers
document.getElementById('ticketForm').addEventListener('submit', function () {
    const priceInputs = this.querySelectorAll('.price-input');
    priceInputs.forEach((input) => {
        input.value = input.value.replace(/\./g, '');
    });
});

// init on load
document.addEventListener('DOMContentLoaded', initPriceInputs);
</script>
@endsection
