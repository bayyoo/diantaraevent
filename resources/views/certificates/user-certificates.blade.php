@extends('layouts.app')

@section('title', 'Sertifikat Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Sertifikat Saya</h1>
            <p class="text-gray-600">Koleksi sertifikat dari event yang telah Anda ikuti</p>
        </div>

        @if($certificates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($certificates as $certificate)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Certificate Preview -->
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-6 text-white">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Certificate of Participation</h3>
                            <p class="text-sm opacity-90">{{ $certificate->certificate_number }}</p>
                        </div>
                    </div>

                    <!-- Certificate Details -->
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $certificate->event->title }}</h4>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($certificate->event->event_date)->format('d F Y') }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $certificate->event->location }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Generated: {{ $certificate->generated_at->format('d M Y') }}
                            </div>
                        </div>

                        <!-- Download Stats -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>Downloaded {{ $certificate->download_count }} times</span>
                            @if($certificate->last_downloaded_at)
                                <span>Last: {{ $certificate->last_downloaded_at->diffForHumans() }}</span>
                            @endif
                        </div>

                        <!-- Download Button -->
                        <a 
                            href="{{ route('certificates.download', $certificate->id) }}" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center font-medium"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination if needed -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">Total {{ $certificates->count() }} sertifikat</p>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Sertifikat</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki sertifikat. Ikuti event dan hadiri untuk mendapatkan sertifikat.</p>
                <a 
                    href="{{ route('events.index') }}" 
                    class="inline-flex items-center bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari Event
                </a>
            </div>
        @endif

        <!-- Certificate Verification -->
        <div class="bg-gray-50 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Verifikasi Sertifikat</h3>
            <p class="text-gray-600 mb-4">Verifikasi keaslian sertifikat dengan memasukkan nomor sertifikat</p>
            
            <form action="{{ route('certificates.verify') }}" method="GET" class="flex gap-4">
                <input 
                    type="text" 
                    name="certificate_number" 
                    placeholder="Masukkan nomor sertifikat (contoh: CERT2025123456)" 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-gray-600 text-white py-2 px-6 rounded-lg hover:bg-gray-700 transition-colors"
                >
                    Verifikasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
