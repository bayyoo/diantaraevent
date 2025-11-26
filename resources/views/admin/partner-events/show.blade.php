@extends('admin.layout')

@section('title', 'Partner Event Detail')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-4">
        <a href="{{ route('admin.partner-events.index') }}" class="px-3 py-2 border rounded text-sm">← Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded border">
                <div class="flex items-start justify-between gap-6">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold">{{ $event->title }}</h1>
                        <div class="text-sm text-gray-600 mt-1">{{ $event->organization->name ?? '-' }} • {{ $event->partner->name ?? '-' }}</div>
                        <div class="mt-2 text-xs text-gray-500 space-x-2">
                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y H:i') }}</span>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        @php $status = strtoupper($event->status); @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $event->status === 'pending_review' ? 'bg-yellow-100 text-yellow-800' : ($event->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">{{ $status }}</span>

                        @php
                            $primaryMedia = null;
                            if ($event->poster) {
                                $primaryMedia = $event->poster;
                            } elseif (is_array($event->banners) && count($event->banners)) {
                                $primaryMedia = $event->banners[0];
                            }
                        @endphp

                        @if($primaryMedia)
                            <div class="mt-1 w-40 h-24 border rounded overflow-hidden bg-gray-50">
                                <img src="{{ Storage::url($primaryMedia) }}" alt="Poster" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-4 text-gray-700 whitespace-pre-line">{{ $event->description }}</div>
            </div>

            <div class="bg-white p-6 rounded border">
                <h3 class="font-semibold mb-3">Detail</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li><strong>Kategori:</strong> {{ $event->category }}</li>
                    <li><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') }}</li>
                    <li><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y H:i') }}</li>
                    <li><strong>Lokasi:</strong> {{ $event->location }}</li>
                    @if(!empty($event->location_details['address']))
                        <li><strong>Alamat Lengkap:</strong> {{ $event->location_details['address'] }}</li>
                    @endif
                    @if(!empty($event->location_details['city']) || !empty($event->location_details['province']))
                        <li>
                            <strong>Kota / Provinsi:</strong>
                            {{ $event->location_details['city'] ?? '-' }}
                            @if(!empty($event->location_details['province']))
                                , {{ $event->location_details['province'] }}
                            @endif
                        </li>
                    @endif
                    @if(!empty($event->location_details['maps_url']))
                        <li>
                            <strong>Maps:</strong>
                            <a href="{{ $event->location_details['maps_url'] }}" target="_blank" class="text-blue-600 hover:underline">Lihat di Google Maps</a>
                        </li>
                    @endif
                </ul>

                @if($event->terms_conditions)
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold mb-1">Syarat & Ketentuan</h4>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $event->terms_conditions }}</div>
                    </div>
                @endif
            </div>

            @if($event->poster || $event->banners)
            <div class="bg-white p-6 rounded border">
                <h3 class="font-semibold mb-3">Media</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($event->poster)
                        <img src="{{ asset($event->poster) }}" class="rounded border" alt="Poster">
                    @endif
                    @if($event->banners)
                        @foreach(json_decode($event->banners, true) as $bn)
                            <img src="{{ asset($bn) }}" class="rounded border" alt="Banner">
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded border">
                <h3 class="font-semibold mb-3">Tiket</h3>
                @if($event->tickets->count())
                    <div class="space-y-3">
                        @foreach($event->tickets as $t)
                            <div class="border rounded p-3">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="font-medium">{{ $t->name }}</div>
                                        <div class="text-xs text-gray-600 mt-0.5">
                                            Harga: Rp {{ number_format($t->price,0,',','.') }}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-0.5">
                                            Kuota: {{ $t->quantity }} tiket 
                                            @if(method_exists($t, 'getRemainingQuantityAttribute'))
                                                (Sisa {{ $t->remaining_quantity }})
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600 mt-0.5">
                                            Min/Maks Pembelian per Transaksi: {{ $t->min_purchase }} - {{ $t->max_purchase }} tiket
                                        </div>
                                        @if($t->description)
                                            <div class="text-xs text-gray-600 mt-1 whitespace-pre-line">{{ $t->description }}</div>
                                        @endif
                                        @if(is_array($t->benefits) && count($t->benefits))
                                            <div class="text-xs text-gray-700 mt-1">
                                                <span class="font-semibold">Benefit:</span>
                                                <ul class="list-disc list-inside mt-0.5">
                                                    @foreach($t->benefits as $benefit)
                                                        <li>{{ $benefit }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right text-xs">
                                        <div class="text-gray-500">
                                            {{ \Carbon\Carbon::parse($t->sale_start)->format('d M Y H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($t->sale_end)->format('d M Y H:i') }}
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $t->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $t->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-600">Belum ada tiket.</div>
                @endif
            </div>

            <div class="bg-white p-6 rounded border">
                <h3 class="font-semibold mb-3">Moderasi</h3>
                @if($event->status === 'pending_review')
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('admin.partner-events.approve', $event) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.partner-events.reject', $event) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">Reject</button>
                        </form>
                    </div>
                @elseif($event->status === 'published')
                    <form method="POST" action="{{ route('admin.partner-events.withdraw', $event) }}" onsubmit="return confirm('Tarik event ini dari publik? Peserta tidak akan bisa mendaftar lagi.');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 rounded bg-yellow-600 text-white w-full">Tarik Event Kembali</button>
                    </form>
                @else
                    <p class="text-sm text-gray-600">Tidak ada aksi moderasi yang tersedia untuk status ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
