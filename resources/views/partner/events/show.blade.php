@extends('partner.layout-simple')

@section('title', 'Event Detail')
@section('page-title', 'EVENT DETAIL')
@section('page-subtitle', '')

@section('content')
<div class="py-8 px-4 md:px-8">
    <div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <i class="fas fa-calendar"></i>
            <a href="{{ route('diantaranexus.events.index') }}" class="text-blue-600 hover:underline">Event</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Detail</span>
        </div>
        <a href="{{ route('diantaranexus.events.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Event Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $event->title }}</h1>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex items-center"><i class="fas fa-calendar w-4 mr-2"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('l, d F Y H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('l, d F Y H:i') }}</div>
                            <div class="flex items-center"><i class="fas fa-map-marker-alt w-4 mr-2"></i> {{ $event->location }}</div>
                            <div class="flex items-center"><i class="fas fa-tag w-4 mr-2"></i> {{ $event->category }}</div>
                        </div>
                    </div>
                    <div>
                        @php $status = strtoupper($event->status); @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $event->status === 'pending_review' ? 'bg-yellow-100 text-yellow-800' : ($event->status === 'published' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">{{ $status }}</span>
                    </div>
                </div>
                <div class="mt-4 prose max-w-none">
                    <p class="text-gray-700">{!! nl2br(e($event->description)) !!}</p>
                </div>
            </div>

            @if($event->poster || $event->banners)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Media</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($event->poster)
                        <div>
                            <div class="text-sm text-gray-600 mb-2">Poster</div>
                            <div class="w-full max-h-80 bg-gray-50 rounded-lg border flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('images/'.$event->poster) }}" alt="Poster" class="w-full h-full object-contain">
                            </div>
                        </div>
                    @endif
                    @if($event->banners)
                        @foreach(json_decode($event->banners, true) as $bn)
                            <div class="w-full max-h-80 bg-gray-50 rounded-lg border flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('images/'.$bn) }}" alt="Banner" class="w-full h-full object-contain">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right: Tickets -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tiket</h3>
                    <a href="{{ route('diantaranexus.events.create.step2', $event->id) }}" class="text-sm text-blue-600 hover:text-blue-800">Kelola Tiket</a>
                </div>
                @if($event->tickets->count())
                    <div class="space-y-3">
                        @foreach($event->tickets as $t)
                        <div class="border rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900">{{ $t->name }}</div>
                                <div class="text-xs text-gray-600">Qty: {{ $t->quantity }} • Rp {{ number_format($t->price,0,',','.') }}</div>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($t->sale_start)->format('d M Y H:i') }} - {{ \Carbon\Carbon::parse($t->sale_end)->format('d M Y H:i') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-600">Belum ada tiket.</div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aksi</h3>
                <div class="space-y-2">
                    <a href="{{ route('diantaranexus.events.index') }}" class="block text-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Kembali ke Daftar</a>

                    <a href="{{ route('diantaranexus.events.create.step3', $event->id) }}"
                       class="block text-center px-4 py-2 border border-blue-300 text-blue-700 rounded-md hover:bg-blue-50 text-sm">
                        Edit Poster & Banner
                    </a>

                    <form method="POST" action="{{ route('diantaranexus.events.submit-review', $event->id) }}">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Ajukan Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
