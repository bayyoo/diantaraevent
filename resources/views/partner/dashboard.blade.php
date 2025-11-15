@extends('partner.layout-simple')

@section('title', 'Dashboard')
@section('page-title', 'HOME')
@section('page-subtitle', '')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Left Sidebar -->
    <div class="w-80 bg-gray-50 border-r border-gray-200 flex-shrink-0">
        <div class="p-6">
            <!-- Organization Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <!-- Organization Header -->
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center relative">
                        <img src="{{ asset('images/diantara-nexus-logo.png') }}" 
                             alt="Organization" 
                             class="w-8 h-8 object-contain">
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 text-sm">
                            @if(isset($organization))
                                {{ Str::limit($organization->name, 20) }}
                            @else
                                Orang Orangan Poern...
                            @endif
                        </h3>
                        <div class="flex items-center space-x-3 text-xs text-gray-500">
                            <span>2 follower</span>
                            <span class="flex items-center">
                                <i class="fas fa-heart text-red-400 mr-1"></i>
                                0
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2 mb-4">
                    <button class="flex-1 bg-white border-2 border-blue-400 text-blue-600 py-2 rounded-full text-sm font-medium hover:bg-blue-50 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        ATUR
                    </button>
                    <button class="flex-1 bg-blue-600 text-white py-2 rounded-full text-sm font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        LIHAT
                    </button>
                </div>
                
                <!-- Create Activity Button -->
                <button class="w-full bg-blue-600 text-white py-3 rounded-full font-semibold hover:bg-blue-700 transition-colors">
                    BUAT AKTIVITAS
                </button>
            </div>
            
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="flex-1 overflow-hidden">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">HOME</h1>
            </div>
            
            <!-- Banner Image with Border -->
            <div class="mb-8">
                <div class="border-4 border-gray-300 rounded-2xl overflow-hidden">
                    <img src="https://picsum.photos/1200/200?random=1" 
                         alt="Banner" 
                         class="w-full h-32 object-cover">
                </div>
            </div>
            
            <!-- Event Summary Stats -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-star text-yellow-500"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Event</h3>
                </div>
                <p class="text-gray-600 text-sm mb-4">Ringkasan semua event yang Anda sponsoring yuk!</p>
                
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-bullhorn text-red-600"></i>
                        </div>
                        <div class="text-sm text-gray-600">Event Dijalankan</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</div>
                        <div class="text-xs text-gray-500">event</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-ticket-alt text-blue-600"></i>
                        </div>
                        <div class="text-sm text-gray-600">Tiket Terjual & Dipesan</div>
                        <div class="text-2xl font-bold text-gray-900">+3</div>
                        <div class="text-xs text-gray-500">tiket</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                        </div>
                        <div class="text-sm text-gray-600">Hasil Transaksi Total</div>
                        <div class="text-2xl font-bold text-gray-900">Rp 0</div>
                        <div class="text-xs text-gray-500">-</div>
                    </div>
                </div>
                
                <div class="text-center mt-6">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700">
                        Analitik Event →
                    </button>
                </div>
            </div>
            
            <!-- Latest Events -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-calendar text-gray-600"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Event Terupdate</h3>
                </div>
                
                @if($recentEvents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recentEvents as $event)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="aspect-w-16 aspect-h-9 bg-gradient-to-r from-red-500 to-orange-500">
                                <div class="flex items-center justify-center text-white">
                                    <i class="fas fa-calendar text-4xl opacity-50"></i>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $event->title }}</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar w-4 mr-2"></i>
                                        <span>
                                            @if(!empty($event->start_date))
                                                {{ optional($event->start_date)->format('l, d F Y') }}
                                            @elseif(!empty($event->end_date))
                                                {{ optional($event->end_date)->format('l, d F Y') }}
                                            @else
                                                Jadwal belum ditentukan
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-ticket-alt w-4 mr-2"></i>
                                        <span>Tiket Tersisa dan Dipesan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-eye w-4 mr-2"></i>
                                        <span>Lihat Detail</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 mt-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
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
@endsection

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
