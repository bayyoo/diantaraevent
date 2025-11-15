@extends('layouts.app')

@section('title', 'My Created Events')

@section('content')
<div class="min-h-screen bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Created Events</h1>
                    <p class="text-gray-600 mt-1">Kelola event yang kamu buat</p>
                </div>
                <a href="{{ route('user.events.create') }}" 
                   class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Buat Event Baru</span>
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Events List -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="md:flex">
                            <!-- Event Image -->
                            <div class="md:w-64 h-48 md:h-auto">
                                @if($event->flyer_path)
                                    <img src="{{ asset('storage/' . $event->flyer_path) }}" 
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#7681FF] to-[#5A67D8] flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-5xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Event Details -->
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                                        <div class="space-y-2 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-2 text-[#7681FF]"></i>
                                                {{ $event->event_date->format('d M Y') }} • {{ $event->event_time ? $event->event_time->format('H:i') : '-' }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt mr-2 text-[#7681FF]"></i>
                                                {{ $event->location }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-users mr-2 text-[#7681FF]"></i>
                                                {{ $event->participants->count() }} / {{ $event->capacity ?? '∞' }} Peserta
                                            </div>
                                            @if($event->price > 0)
                                                <div class="flex items-center">
                                                    <i class="fas fa-tag mr-2 text-[#7681FF]"></i>
                                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <i class="fas fa-tag mr-2 text-[#7681FF]"></i>
                                                    Gratis
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="ml-4">
                                        @if($event->status === 'pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @elseif($event->status === 'approved' || $event->status === 'published')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i> Approved
                                            </span>
                                        @elseif($event->status === 'rejected')
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-times-circle mr-1"></i> Rejected
                                            </span>
                                        @elseif($event->status === 'draft')
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-file mr-1"></i> Draft
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Rejection Reason -->
                                @if($event->status === 'rejected' && $event->rejection_reason)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                        <p class="text-sm text-red-700">
                                            <strong>Alasan Penolakan:</strong> {{ $event->rejection_reason }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-3 mt-4">
                                    <a href="{{ route('user.events.show', $event) }}" 
                                       class="text-[#7681FF] hover:text-[#5A67D8] font-semibold text-sm flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                                    </a>

                                    @if(in_array($event->status, ['pending', 'rejected', 'draft']))
                                        <a href="{{ route('user.events.edit', $event) }}" 
                                           class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                    @endif

                                    @if(!$event->isApproved())
                                        <form action="{{ route('user.events.destroy', $event) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus event ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-700 font-semibold text-sm flex items-center">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-calendar-plus text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Event</h3>
                    <p class="text-gray-600 mb-6">Kamu belum membuat event apapun. Mulai buat event pertamamu sekarang!</p>
                    <a href="{{ route('user.events.create') }}" 
                       class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-200 inline-flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Buat Event Pertama</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
