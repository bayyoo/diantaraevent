@extends('partner.layout-simple')

@section('title', 'Absensi Event')
@section('page-title', 'ABSENSI EVENT')
@section('page-subtitle', 'Input token peserta untuk menandai kehadiran')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Absensi Event</h1>
        <p class="text-gray-600 text-sm">Pilih event yang ingin kamu lakukan absensi lalu buka halaman absensi untuk input token peserta.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($events->count() === 0)
        <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center text-gray-600">
            Belum ada event yang bisa diabsen. Pastikan kamu sudah punya event yang tayang / berjalan.
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-2xl p-4">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-100 text-gray-500 text-xs uppercase">
                        <th class="py-3 px-3">Event</th>
                        <th class="py-3 px-3">Tanggal</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-3 px-3">
                                <div class="font-semibold text-gray-900">{{ $event->title }}</div>
                                <div class="text-xs text-gray-500">{{ $event->location }}</div>
                            </td>
                            <td class="py-3 px-3 text-gray-700 text-xs">
                                {{ optional($event->start_date)->format('d M Y') }}
                                @if($event->end_date && $event->end_date->ne($event->start_date))
                                    - {{ optional($event->end_date)->format('d M Y') }}
                                @endif
                            </td>
                            <td class="py-3 px-3 text-xs">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $event->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('diantaranexus.events.attendance', $event->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-nexus text-white text-xs font-medium hover:bg-blue-700">
                                    <i class="fas fa-qrcode mr-2"></i>
                                    Buka Halaman Absensi
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $events->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
