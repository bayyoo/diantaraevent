@extends('partner.layout')

@section('title', 'Create Event - Step 1')
@section('page-title', 'Create New Event')
@section('page-subtitle', 'Step 1 of 3: Basic Information')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-8 h-8 nexus-gradient rounded-full flex items-center justify-center text-white font-semibold text-sm">1</div>
                <span class="ml-3 text-sm font-medium text-nexus">Basic Information</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">2</div>
                <span class="ml-3 text-sm text-gray-500">Tickets</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">3</div>
                <span class="ml-3 text-sm text-gray-500">Media & Finalize</span>
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="nexus-gradient h-2 rounded-full" style="width: 33.33%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Event Basic Information</h2>
            <p class="text-gray-600">Provide the essential details about your event. You can always edit these later.</p>
        </div>

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

        <form method="POST" action="{{ route('diantaranexus.events.create.step1.store') }}" class="space-y-6">
            @csrf

            <!-- Event Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       required 
                       placeholder="e.g., Tech Conference 2025"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('title') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Choose a clear, descriptive title for your event</p>
            </div>

            <!-- Event Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Event Category *</label>
                <select id="category" 
                        name="category" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('category') border-red-500 @enderror">
                    <option value="">Select a category</option>
                    <option value="Conference" {{ old('category') == 'Conference' ? 'selected' : '' }}>Conference</option>
                    <option value="Workshop" {{ old('category') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="Seminar" {{ old('category') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="Music & Concert" {{ old('category') == 'Music & Concert' ? 'selected' : '' }}>Music & Concert</option>
                    <option value="Exhibition" {{ old('category') == 'Exhibition' ? 'selected' : '' }}>Exhibition</option>
                    <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                    <option value="Food & Drink" {{ old('category') == 'Food & Drink' ? 'selected' : '' }}>Food & Drink</option>
                    <option value="Arts & Culture" {{ old('category') == 'Arts & Culture' ? 'selected' : '' }}>Arts & Culture</option>
                    <option value="Business" {{ old('category') == 'Business' ? 'selected' : '' }}>Business</option>
                    <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Event Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Event Description *</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          placeholder="Describe your event in detail. What will attendees experience? What makes it special?"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Provide a detailed description to attract attendees</p>
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time *</label>
                    <input type="datetime-local" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date') }}"
                           required 
                           min="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('start_date') border-red-500 @enderror">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date & Time *</label>
                    <input type="datetime-local" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date') }}"
                           required 
                           min="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('end_date') border-red-500 @enderror">
                </div>
            </div>

            <!-- Event Sessions -->
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-gray-900">Event Sessions (Optional, multi-hari)</h4>
                    <button type="button" id="add-session" class="text-sm text-nexus hover:text-nexus-dark">+ Tambah Sesi</button>
                </div>
                <p class="text-xs text-gray-500 mb-4">Tambahkan satu atau beberapa sesi. Sertifikat akan mensyaratkan hadir di semua sesi.</p>
                <div id="sessions-list" class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 session-row">
                        <div class="md:col-span-4">
                            <input type="text" name="sessions[0][name]" class="w-full px-3 py-2 border rounded-lg" placeholder="Nama sesi (Hari 1)" />
                        </div>
                        <div class="md:col-span-4">
                            <input type="datetime-local" name="sessions[0][start_at]" class="w-full px-3 py-2 border rounded-lg" />
                        </div>
                        <div class="md:col-span-4 flex gap-2">
                            <input type="datetime-local" name="sessions[0][end_at]" class="w-full px-3 py-2 border rounded-lg" />
                            <button type="button" class="px-3 py-2 border rounded remove-session hidden">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Venue Name *</label>
                <input type="text" 
                       id="location" 
                       name="location" 
                       value="{{ old('location') }}"
                       required 
                       placeholder="e.g., Jakarta Convention Center"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('location') border-red-500 @enderror">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Complete Address *</label>
                <textarea id="address" 
                          name="address" 
                          rows="3" 
                          required 
                          placeholder="Full address including street, city, postal code"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
            </div>

            <!-- Venue Info (Optional) -->
            <div>
                <label for="venue_info" class="block text-sm font-medium text-gray-700 mb-2">Additional Venue Information</label>
                <textarea id="venue_info" 
                          name="venue_info" 
                          rows="2" 
                          placeholder="Parking info, accessibility, nearby landmarks, etc. (optional)"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">{{ old('venue_info') }}</textarea>
            </div>

            <!-- Certificate Settings -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="font-semibold text-gray-900 mb-3">Certificate Settings</h4>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="has_certificate" value="1" class="h-4 w-4 text-nexus border-gray-300 focus:ring-nexus" {{ old('has_certificate') ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Aktifkan sertifikat untuk event ini</span>
                    </label>

                    <div class="pl-7 space-y-2">
                        <div class="text-sm text-gray-600">Pilih template sertifikat</div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="certificate_template" value="template_a" {{ old('certificate_template', 'template_a') === 'template_a' ? 'checked' : '' }}>
                                <span>Template A (Default)</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="certificate_template" value="template_b" {{ old('certificate_template') === 'template_b' ? 'checked' : '' }}>
                                <span>Template B</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="certificate_template" value="custom" {{ old('certificate_template') === 'custom' ? 'checked' : '' }}>
                                <span>Custom (upload di Step 3)</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Tanda tangan & cap dikelola dari halaman ORGANISASI.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('diantaranexus.events.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                
                <button type="submit" 
                        class="nexus-gradient text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition-all transform hover:scale-105">
                    Continue to Tickets
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Card -->
    <div class="mt-6 bg-blue-50 rounded-2xl p-6 border border-blue-200">
        <div class="flex items-start space-x-4">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-blue-900 mb-2">Tips for Creating Great Events</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Use clear, descriptive titles that explain what attendees will get</li>
                    <li>• Choose dates that don't conflict with major holidays or competing events</li>
                    <li>• Provide detailed venue information to help attendees plan their visit</li>
                    <li>• Write compelling descriptions that highlight the value and benefits</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-update end date when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDateInput = document.getElementById('end_date');
        
        if (startDate) {
            // Set minimum end date to start date
            endDateInput.min = this.value;
            
            // If end date is before start date, update it
            if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
                const suggestedEndDate = new Date(startDate.getTime() + 2 * 60 * 60 * 1000); // +2 hours
                endDateInput.value = suggestedEndDate.toISOString().slice(0, 16);
            }
        }
    });

    // Sessions editor
    (function(){
        const list = document.getElementById('sessions-list');
        const addBtn = document.getElementById('add-session');
        function updateRemoveVisibility(){
            const rows = list.querySelectorAll('.session-row');
            rows.forEach((row, idx)=>{
                const btn = row.querySelector('.remove-session');
                if (btn) btn.classList.toggle('hidden', rows.length <= 1);
            });
        }
        function addRow(){
            const idx = list.querySelectorAll('.session-row').length;
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-12 gap-3 session-row';
            div.innerHTML = `
                <div class="md:col-span-4">
                    <input type="text" name="sessions[${idx}][name]" class="w-full px-3 py-2 border rounded-lg" placeholder="Nama sesi (Hari ${idx+1})" />
                </div>
                <div class="md:col-span-4">
                    <input type="datetime-local" name="sessions[${idx}][start_at]" class="w-full px-3 py-2 border rounded-lg" />
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <input type="datetime-local" name="sessions[${idx}][end_at]" class="w-full px-3 py-2 border rounded-lg" />
                    <button type="button" class="px-3 py-2 border rounded remove-session">Hapus</button>
                </div>`;
            list.appendChild(div);
            updateRemoveVisibility();
        }
        addBtn.addEventListener('click', addRow);
        list.addEventListener('click', (e)=>{
            if (e.target.classList.contains('remove-session')){
                const row = e.target.closest('.session-row');
                row.remove();
                updateRemoveVisibility();
            }
        });
        updateRemoveVisibility();
    })();
</script>
@endsection
