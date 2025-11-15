@extends('partner.layout')

@section('title', 'Create Event - Step 3')
@section('page-title', 'Create New Event')
@section('page-subtitle', 'Step 3 of 3: Media & Finalization')

@section('content')
<div class="max-w-4xl mx-auto">
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
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <span class="ml-3 text-sm text-green-600">Tickets</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 nexus-gradient rounded-full flex items-center justify-center text-white font-semibold text-sm">3</div>
                <span class="ml-3 text-sm font-medium text-nexus">Media & Finalize</span>
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="nexus-gradient h-2 rounded-full" style="width: 100%"></div>
        </div>
    </div>

    <!-- Event Summary -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                <p class="text-sm text-gray-600">{{ $event->category }}</p>
                <p class="text-sm text-gray-600">{{ $event->start_date->format('M d, Y \a\t H:i') }} - {{ $event->end_date->format('M d, Y \a\t H:i') }}</p>
                <p class="text-sm text-gray-600">{{ $event->location }}</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Tickets ({{ $event->tickets->count() }} types)</h4>
                @foreach($event->tickets as $ticket)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $ticket->name }}</span>
                        <span class="font-medium">{{ $ticket->formatted_price }} ({{ $ticket->quantity }} available)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Media Upload & Finalization -->
    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Add Media & Finalize</h2>
            <p class="text-gray-600">Upload your event poster and banners, add terms & conditions, then submit for review.</p>
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

        <form method="POST" action="{{ route('diantaranexus.events.create.step3.store', $event->id) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Event Poster -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">Event Poster</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-nexus transition-colors">
                    <div id="posterPreview" class="mb-4">
                        @if($event->poster)
                            <img src="{{ Storage::url($event->poster) }}" alt="Current poster" class="mx-auto max-h-48 rounded-lg">
                            <p class="text-sm text-gray-600 mt-2">Current poster</p>
                        @else
                            <i class="fas fa-image text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 mb-2">Upload your event poster</p>
                            <p class="text-sm text-gray-500">PNG, JPG up to 2MB. Recommended: 1080x1080px</p>
                        @endif
                    </div>
                    <input type="file" 
                           id="poster" 
                           name="poster" 
                           accept="image/*" 
                           class="hidden"
                           onchange="previewImage(this, 'posterPreview')">
                    <button type="button" 
                            onclick="document.getElementById('poster').click()" 
                            class="nexus-gradient text-white px-6 py-2 rounded-lg hover:opacity-90 transition-all">
                        <i class="fas fa-upload mr-2"></i>
                        {{ $event->poster ? 'Change Poster' : 'Upload Poster' }}
                    </button>
                </div>
            </div>

            <!-- Event Banners -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">Event Banners (Optional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-nexus transition-colors">
                    <div id="bannersPreview" class="mb-4">
                        @if($event->banners && count($event->banners) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($event->banners as $banner)
                                    <img src="{{ Storage::url($banner) }}" alt="Banner" class="rounded-lg max-h-32 object-cover">
                                @endforeach
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Current banners</p>
                        @else
                            <i class="fas fa-images text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 mb-2">Upload additional banners</p>
                            <p class="text-sm text-gray-500">PNG, JPG up to 2MB each. Multiple files allowed</p>
                        @endif
                    </div>
                    <input type="file" 
                           id="banners" 
                           name="banners[]" 
                           accept="image/*" 
                           multiple 
                           class="hidden"
                           onchange="previewMultipleImages(this, 'bannersPreview')">
                    <button type="button" 
                            onclick="document.getElementById('banners').click()" 
                            class="border border-nexus text-nexus px-6 py-2 rounded-lg hover:bg-nexus hover:text-white transition-all">
                        <i class="fas fa-upload mr-2"></i>
                        {{ $event->banners && count($event->banners) > 0 ? 'Change Banners' : 'Upload Banners' }}
                    </button>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div>
                <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions *</label>
                <textarea id="terms_conditions" 
                          name="terms_conditions" 
                          rows="8" 
                          required 
                          placeholder="Enter the terms and conditions for your event..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('terms_conditions') border-red-500 @enderror">{{ old('terms_conditions', $event->terms_conditions) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Include registration policies, cancellation terms, refund policies, etc.</p>
            </div>

            <!-- Terms Template -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="font-medium text-gray-900 mb-3">Terms & Conditions Template</h4>
                <div class="text-sm text-gray-700 space-y-2">
                    <p><strong>Registration:</strong> All registrations are final upon payment confirmation.</p>
                    <p><strong>Cancellation:</strong> Event may be cancelled due to unforeseen circumstances with full refund.</p>
                    <p><strong>Refund Policy:</strong> Refunds are available up to 7 days before the event date.</p>
                    <p><strong>Age Restriction:</strong> Participants must be 18+ unless otherwise specified.</p>
                    <p><strong>Photography:</strong> Event may be photographed/recorded for promotional purposes.</p>
                    <p><strong>Liability:</strong> Organizers are not responsible for personal belongings or injuries.</p>
                </div>
                <button type="button" 
                        onclick="useTemplate()" 
                        class="mt-3 text-nexus hover:text-nexus-dark text-sm font-medium">
                    Use This Template
                </button>
            </div>

            <!-- Submission Options -->
            <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                <h4 class="font-medium text-blue-900 mb-4">Ready to Submit?</h4>
                <div class="space-y-3">
                    <label class="flex items-start space-x-3">
                        <input type="radio" 
                               name="submit_action" 
                               value="save_draft" 
                               checked 
                               class="mt-1 text-nexus border-gray-300 focus:ring-nexus">
                        <div>
                            <span class="font-medium text-blue-900">Save as Draft</span>
                            <p class="text-sm text-blue-700">Keep working on your event. You can submit for review later.</p>
                        </div>
                    </label>
                    <label class="flex items-start space-x-3">
                        <input type="radio" 
                               name="submit_action" 
                               value="submit_review" 
                               class="mt-1 text-nexus border-gray-300 focus:ring-nexus">
                        <div>
                            <span class="font-medium text-blue-900">Submit for Review</span>
                            <p class="text-sm text-blue-700">Send your event to admin for approval. Once approved, it will be published and tickets can be sold.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('diantaranexus.events.create.step2', $event->id) }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Tickets
                </a>
                
                <div class="space-x-4">
                    <button type="submit" 
                            name="submit_for_review" 
                            value="0"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                        Save as Draft
                    </button>
                    <button type="submit" 
                            name="submit_for_review" 
                            value="1"
                            class="nexus-gradient text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition-all transform hover:scale-105">
                        Submit for Review
                        <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Final Tips -->
    <div class="mt-6 bg-green-50 rounded-2xl p-6 border border-green-200">
        <div class="flex items-start space-x-4">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-green-900 mb-2">You're Almost Done!</h3>
                <ul class="text-sm text-green-800 space-y-1">
                    <li>• <strong>Review Process:</strong> Admin will review your event within 24-48 hours</li>
                    <li>• <strong>Approval:</strong> Once approved, your event will be live and tickets can be sold</li>
                    <li>• <strong>Notifications:</strong> You'll receive email updates about your event status</li>
                    <li>• <strong>Analytics:</strong> Track sales and attendee data in your dashboard</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="mx-auto max-h-48 rounded-lg mb-2">
                <p class="text-sm text-gray-600">New image selected</p>
            `;
        };
        reader.readAsDataURL(file);
    }
}

function previewMultipleImages(input, previewId) {
    const preview = document.getElementById(previewId);
    const files = input.files;
    
    if (files.length > 0) {
        let html = '<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-2">';
        
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                html += `<img src="${e.target.result}" alt="Preview" class="rounded-lg max-h-32 object-cover">`;
                
                if (html.split('<img').length - 1 === files.length) {
                    html += '</div><p class="text-sm text-gray-600">New images selected</p>';
                    preview.innerHTML = html;
                }
            };
            reader.readAsDataURL(file);
        });
    }
}

function useTemplate() {
    const template = `TERMS AND CONDITIONS

1. REGISTRATION
- All registrations are final upon payment confirmation
- Participants must provide accurate information during registration
- Registration confirmation will be sent via email

2. CANCELLATION & REFUNDS
- Event may be cancelled due to unforeseen circumstances with full refund
- Participant cancellations: Refunds available up to 7 days before event date
- No refunds for no-shows or late cancellations

3. EVENT POLICIES
- Age restriction: Participants must be 18+ unless otherwise specified
- Dress code: Business casual or as specified in event details
- Punctuality: Please arrive 15 minutes before start time

4. PHOTOGRAPHY & RECORDING
- Event may be photographed/recorded for promotional purposes
- By attending, you consent to use of your image in marketing materials

5. LIABILITY
- Organizers are not responsible for personal belongings or injuries
- Participants attend at their own risk
- Event schedule and speakers subject to change

6. CODE OF CONDUCT
- Respectful behavior expected from all participants
- Harassment or disruptive behavior will result in removal
- No outside food or beverages unless specified

By registering for this event, you agree to these terms and conditions.`;

    document.getElementById('terms_conditions').value = template;
}

// Handle radio button changes
document.querySelectorAll('input[name="submit_action"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const buttons = document.querySelectorAll('button[name="submit_for_review"]');
        if (this.value === 'submit_review') {
            buttons[0].style.display = 'none';
            buttons[1].textContent = 'Submit for Review';
        } else {
            buttons[0].style.display = 'inline-block';
            buttons[1].textContent = 'Save as Draft';
        }
    });
});
</script>
@endsection
