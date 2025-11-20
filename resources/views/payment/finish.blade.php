@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mb-6">Terima kasih! Pembayaran Anda telah berhasil diproses.</p>

            @if($participant)
            <!-- Order Details -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
                <h3 class="font-semibold text-gray-900 mb-4">Detail Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-mono text-sm">{{ $participant->order_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event:</span>
                        <span class="font-medium">{{ $participant->event->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Peserta:</span>
                        <span class="font-medium">{{ $participant->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $participant->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Bayar:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($participant->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-xl p-6 mb-6 text-left">
                <h3 class="font-semibold text-blue-900 mb-3">Langkah Selanjutnya</h3>
                <ul class="space-y-2 text-blue-800">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Token kehadiran dan QR code tiket akan dikirim ke email Anda dalam 5-10 menit</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Gunakan token / QR code untuk melakukan check-in pada hari event</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Sertifikat akan tersedia setelah Anda hadir di event sesuai ketentuan penyelenggara</span>
                    </li>
                </ul>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if($participant)
                    <a href="{{ route('ticket.view', $participant) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Lihat E-Ticket
                    </a>
                    <a href="{{ route('ticket.download', $participant) }}" 
                       class="bg-green-100 hover:bg-green-200 text-green-800 font-semibold py-3 px-6 rounded-lg transition-colors">
                        Download E-Ticket (PDF)
                    </a>
                @endif

                <a href="{{ route('home') }}" 
                   class="bg-[#7681FF] hover:bg-[#5A67D8] text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('catalog.index') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                    Lihat Event Lainnya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
