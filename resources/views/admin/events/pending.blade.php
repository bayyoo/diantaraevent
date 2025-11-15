@extends('admin.layout')

@section('title', 'Pending Events')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pending Events</h1>
            <p class="text-gray-600 mt-1">Review dan approve event yang dibuat oleh user</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($pendingEvents->count() > 0)
            <div class="space-y-6">
                @foreach($pendingEvents as $event)
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                        <div class="md:flex">
                            <!-- Event Image -->
                            <div class="md:w-80 h-64 md:h-auto">
                                @if($event->flyer_path)
                                    <img src="{{ asset('storage/' . $event->flyer_path) }}" 
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#7681FF] to-[#5A67D8] flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-6xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Event Details -->
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                                        <div class="flex items-center text-sm text-gray-600 mb-3">
                                            <i class="fas fa-user mr-2 text-[#7681FF]"></i>
                                            <span>Dibuat oleh: <strong>{{ $event->creator->name }}</strong> ({{ $event->creator->email }})</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-calendar w-5 text-[#7681FF]"></i>
                                            <span class="ml-2">{{ $event->event_date->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-clock w-5 text-[#7681FF]"></i>
                                            <span class="ml-2">{{ $event->event_time ? $event->event_time->format('H:i') : '-' }} WIB</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-map-marker-alt w-5 text-[#7681FF]"></i>
                                            <span class="ml-2">{{ $event->location }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-users w-5 text-[#7681FF]"></i>
                                            <span class="ml-2">Kapasitas: {{ $event->capacity ?? 'Unlimited' }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-tag w-5 text-[#7681FF]"></i>
                                            <span class="ml-2">
                                                @if($event->price > 0)
                                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                                @else
                                                    Gratis
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-calendar-plus w-5 text-[#7681FF]"></i>
                                            <span class="ml-2">Dibuat: {{ $event->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Deskripsi:</h4>
                                    <p class="text-sm text-gray-700 line-clamp-3">{{ $event->description }}</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menyetujui event ini?')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors flex items-center space-x-2">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Approve</span>
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <button onclick="openRejectModal({{ $event->id }}, '{{ $event->title }}')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors flex items-center space-x-2">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Reject</span>
                                    </button>

                                    <!-- View Details -->
                                    <a href="{{ route('events.show', $event) }}" 
                                       target="_blank"
                                       class="text-[#7681FF] hover:text-[#5A67D8] font-semibold flex items-center space-x-2">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span>Preview</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $pendingEvents->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Event Pending</h3>
                <p class="text-gray-600">Semua event sudah direview</p>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Event</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejection_reason" 
                              name="rejection_reason" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Jelaskan alasan penolakan event ini..."
                              required></textarea>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                        Tolak Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(eventId, eventTitle) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = `/admin/events/${eventId}/reject`;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            document.getElementById('rejection_reason').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
