@extends('partner.layout')

@section('title', 'Certificate Preview')
@section('page-title', 'Certificate Preview')
@section('page-subtitle', $event->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Preview Sertifikat</h2>
            <p class="text-sm text-gray-600">Tampilan sertifikat untuk event ini. Nama peserta hanya contoh.</p>
        </div>
        <a href="{{ route('diantaranexus.events.show', $event->id) }}" class="text-sm text-nexus hover:text-nexus-dark">Kembali ke detail event</a>
    </div>

    <div class="bg-gray-100 border border-gray-200 rounded-2xl p-4 md:p-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @php
                $metadata = $event->metadata['certificate'] ?? [];
                $template = $metadata['certificate_template'] ?? 'template_a';
                $certLogoPath = $metadata['logo_path'] ?? null;
                $defaultCertLogo = asset('images/diantara-nexus-logo.png');
                $certLogoUrl = $certLogoPath ? asset('storage/'.$certLogoPath) : $defaultCertLogo;
                $customPath = $event->custom_certificate_path ?? null;
            @endphp
            @if ($template === 'custom' && $customPath)
                <div class="p-4 md:p-6">
                    <div class="mb-4 text-sm text-gray-600">Preview menggunakan template custom yang kamu upload.</div>
                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                        <img src="{{ asset('storage/'.$customPath) }}" alt="Custom certificate" class="w-full max-h-[480px] object-contain bg-white">
                    </div>
                    <div class="mt-4 text-xs text-gray-500">Nama peserta dan tanda tangan dari ORGANISASI akan ditempel otomatis di atas template ini saat sertifikat di-generate.</div>
                </div>
            @elseif ($template === 'template_b')
                {{-- Template B preview: blok warna di sudut, judul besar, nama oranye --}}
                <div class="relative">
                    <div class="absolute inset-x-0 top-0 flex justify-between p-4">
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-orange-500"></div>
                            <div class="w-3 h-3 bg-blue-500"></div>
                            <div class="w-3 h-3 bg-indigo-500"></div>
                        </div>
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-indigo-500"></div>
                            <div class="w-3 h-3 bg-blue-500"></div>
                            <div class="w-3 h-3 bg-orange-500"></div>
                        </div>
                    </div>
                    <div class="p-8 md:p-12">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full border border-gray-400 flex items-center justify-center overflow-hidden bg-white">
                                    <img src="{{ $certLogoUrl }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                </div>
                                <div class="text-[10px] md:text-xs text-gray-500 uppercase tracking-wide">Company Name</div>
                            </div>
                        </div>
                        <div class="text-center mb-6">
                            <div class="text-lg md:text-2xl font-extrabold tracking-[0.25em] text-blue-900 uppercase">Certificate</div>
                            <div class="text-[11px] md:text-xs text-gray-500 tracking-[0.35em] uppercase">of Achievement</div>
                        </div>
                        <div class="text-center mb-6">
                            <div class="text-[11px] md:text-xs text-blue-700 font-semibold tracking-wide mb-1">THIS CERTIFICATE IS PRESENTED TO</div>
                            <div class="text-xl md:text-3xl font-semibold text-orange-500">{{ $participantName }}</div>
                        </div>
                        <div class="text-[11px] md:text-sm text-gray-600 max-w-2xl mx-auto mb-10">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </div>
                        <div class="mt-8 flex justify-between items-end text-[10px] md:text-xs text-gray-500">
                            <div>
                                <div class="mb-1">January 2nd 2025</div>
                                <div class="h-8 border-b border-gray-300 mb-1 w-40"></div>
                                <div>Date</div>
                            </div>
                            <div class="text-right">
                                <div class="h-8 border-b border-gray-300 mb-1 w-40 ml-auto"></div>
                                <div>Signature</div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 flex justify-between p-4">
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-indigo-500"></div>
                            <div class="w-3 h-3 bg-blue-500"></div>
                            <div class="w-3 h-3 bg-orange-500"></div>
                        </div>
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-orange-500"></div>
                            <div class="w-3 h-3 bg-blue-500"></div>
                            <div class="w-3 h-3 bg-indigo-500"></div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Template A preview (gelombang biru seperti di Step 3) --}}
                <div class="h-8 bg-gradient-to-r from-blue-500 via-sky-400 to-blue-600"></div>
                <div class="p-6 md:p-10">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="h-10 w-10 rounded-full border border-blue-400 flex items-center justify-center overflow-hidden bg-white">
                                <img src="{{ $certLogoUrl }}" alt="Logo" class="max-w-full max-h-full object-contain">
                            </div>
                            <div class="text-xs text-gray-500">Your Organization</div>
                        </div>
                    </div>
                    <div class="text-center mb-2">
                        <div class="text-base md:text-xl tracking-[0.2em] text-gray-700 uppercase">Certificate</div>
                        <div class="text-[10px] md:text-xs text-gray-500 tracking-[0.3em] uppercase">of Achievement</div>
                    </div>
                    <div class="mt-6 text-center">
                        <div class="text-[10px] md:text-xs text-gray-500 mb-1">Proudly Presented To</div>
                        <div class="text-lg md:text-2xl font-semibold text-sky-600 mb-3">{{ $participantName }}</div>
                        <div class="text-[11px] md:text-sm text-gray-600 max-w-xl mx-auto">
                            Atas pencapaian dan partisipasi dalam acara <span class="font-medium">{{ $event->title }}</span> yang diselenggarakan oleh organisasi kamu.
                        </div>
                    </div>
                    <div class="mt-10 flex justify-between items-end text-[10px] md:text-xs text-gray-500">
                        <div class="text-center">
                            <div class="h-8 border-b border-gray-300 mb-1 w-32 mx-auto"></div>
                            <div>Date</div>
                        </div>
                        <div class="text-center">
                            <div class="h-8 border-b border-gray-300 mb-1 w-32 mx-auto"></div>
                            <div>Signature</div>
                        </div>
                    </div>
                </div>
                <div class="h-8 bg-gradient-to-r from-blue-600 via-sky-400 to-blue-500"></div>
            @endif
        </div>
    </div>
</div>
@endsection
