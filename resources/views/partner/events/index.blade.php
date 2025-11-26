@extends('partner.layout-simple')

@section('title', 'Events')
@section('page-title', 'EVENT')
@section('page-subtitle', '')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Left Sidebar -->
    <div class="w-80 bg-gray-50 border-r border-gray-200 flex-shrink-0">
        <div class="p-6">
            

            <!-- Left Menu (Daftar/Analitik) -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <a href="#" class="block w-full px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold mb-2">
                    <i class="fas fa-list mr-2"></i>Daftar Event
                </a>
                <a href="#" class="block w-full px-4 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:border-blue-200 hover:text-blue-700">
                    <i class="fas fa-chart-line mr-2"></i>Analitik Event
                </a>
            </div>
            
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="flex-1 overflow-hidden">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <i class="fas fa-calendar"></i>
                    <span>Event</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">EVENT</h1>
            </div>
            
            
            
            <!-- Top Controls in bordered container -->
            <div class="mb-6 border border-gray-200 rounded-md p-3">
                <div class="flex items-center justify-between">
                    <!-- Tabs Left -->
                    @php
                        $status = $statusFilter ?? request('status', 'semua');
                    @endphp
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('diantaranexus.events.index', ['status' => 'semua']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium border {{ $status === 'semua' ? 'border-blue-200 bg-white text-blue-700' : 'border-transparent text-gray-600 hover:text-blue-700 hover:border-blue-200' }}">
                            SEMUA EVENT
                        </a>
                        <a href="{{ route('diantaranexus.events.index', ['status' => 'draft']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium border {{ $status === 'draft' ? 'border-blue-200 bg-white text-blue-700' : 'border-transparent text-gray-600 hover:text-blue-700 hover:border-blue-200' }}">
                            DRAF
                        </a>
                        <a href="{{ route('diantaranexus.events.index', ['status' => 'tayang']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium border {{ $status === 'tayang' ? 'border-blue-200 bg-white text-blue-700' : 'border-transparent text-gray-600 hover:text-blue-700 hover:border-blue-200' }}">
                            TAYANG
                        </a>
                        <a href="{{ route('diantaranexus.events.index', ['status' => 'berakhir']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium border {{ $status === 'berakhir' ? 'border-blue-200 bg-white text-blue-700' : 'border-transparent text-gray-600 hover:text-blue-700 hover:border-blue-200' }}">
                            BERAKHIR
                        </a>
                    </div>
                    <!-- Actions Right -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('diantaranexus.events.create') }}" 
                           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> BUAT EVENT
                        </a>
                        <div class="relative">
                            <input type="text" placeholder="Cari event" class="pl-9 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent w-56">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($events->count() > 0)
                    @foreach($events as $event)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Event Image/Poster -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 relative">
                            @php
                                // Normalisasi poster & banners ke array path yang bisa dipakai
                                $primaryImage = null;

                                if (!empty($event->poster)) {
                                    $primaryImage = $event->poster;
                                } elseif (!empty($event->banners)) {
                                    $bannersArray = is_array($event->banners) ? $event->banners : json_decode($event->banners, true);
                                    if (is_array($bannersArray) && count($bannersArray) > 0) {
                                        $primaryImage = $bannersArray[0];
                                    }
                                }
                            @endphp

                            @if($primaryImage)
                                <img src="{{ asset($primaryImage) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-red-500 to-orange-500 flex items-center justify-center text-white">
                                    <i class="fas fa-calendar text-4xl opacity-70"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if($event->status === 'draft')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        DRAFT
                                    </span>
                                @elseif($event->status === 'published')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        TAYANG
                                    </span>
                                @elseif($event->status === 'pending_review')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        REVIEW
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $event->title }}</h4>
                            
                            <div class="space-y-1 text-sm text-gray-600 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-4 mr-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-ticket-alt w-4 mr-2"></i>
                                    <span>{{ $event->tickets->count() }} Tiket Terjual dan Dipesan</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-eye w-4 mr-2"></i>
                                    <span>{{ $event->views ?? 0 }} Kali Dilihat</span>
                                </div>
                            </div>
                            
                            <!-- Action Icons -->
                            <div class="flex items-center space-x-2 text-sm">
                                @if(in_array($event->status, ['published', 'approved']))
                                <a href="{{ route('public.events.show', $event->slug) }}" target="_blank"
                                   class="inline-flex items-center px-2.5 py-1 rounded-md border border-gray-200 text-gray-600 hover:text-blue-700 hover:border-blue-200">
                                    <i class="fas fa-external-link-alt mr-1"></i> Preview
                                </a>
                                @endif
                                <a href="{{ route('diantaranexus.events.attendance', $event->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1 rounded-md border border-emerald-200 text-emerald-700 hover:text-emerald-800 hover:border-emerald-300">
                                    <i class="fas fa-user-check mr-1"></i> Absensi
                                </a>
                                <a href="{{ route('diantaranexus.events.show', $event->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1 rounded-md border border-gray-200 text-gray-600 hover:text-blue-700 hover:border-blue-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if($event->status === 'draft')
                                <form action="{{ route('diantaranexus.events.submit-review', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-md border border-yellow-200 text-yellow-700 hover:text-yellow-800 hover:border-yellow-300">
                                        <i class="fas fa-paper-plane mr-1"></i> Ajukan Review
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('diantaranexus.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-md border border-red-200 text-red-600 hover:text-red-700 hover:border-red-300">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="col-span-full bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-plus text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Event</h4>
                        <p class="text-gray-600 mb-6">Mulai buat event pertama Anda untuk menjangkau lebih banyak peserta</p>
                        <a href="{{ route('diantaranexus.events.create') }}" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Event Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
    }
    .aspect-w-16 > * {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
@endpush
@endsection
