@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            <!-- Error Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <!-- Error Message -->
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Pembayaran Gagal</h1>
            <p class="text-gray-600 mb-6">Maaf, terjadi kesalahan saat memproses pembayaran Anda. Silakan coba lagi.</p>

            <!-- Error Information -->
            <div class="bg-red-50 rounded-xl p-6 mb-6 text-left">
                <h3 class="font-semibold text-red-900 mb-3">Kemungkinan Penyebab</h3>
                <ul class="space-y-2 text-red-800">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Saldo kartu/e-wallet tidak mencukupi</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Koneksi internet terputus saat proses pembayaran</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Kartu/akun pembayaran bermasalah</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Gangguan sistem pembayaran sementara</span>
                    </li>
                </ul>
            </div>

            <!-- Help Information -->
            <div class="bg-blue-50 rounded-xl p-6 mb-6 text-left">
                <h3 class="font-semibold text-blue-900 mb-3">Butuh Bantuan?</h3>
                <p class="text-blue-800 mb-3">Jika masalah terus berlanjut, silakan hubungi customer service kami:</p>
                <div class="space-y-2 text-blue-800">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Email: diantaraevent@gmail.com</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>WhatsApp: +62 xxx-xxxx-xxxx</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('catalog.index') }}" 
                   class="bg-[#7681FF] hover:bg-[#5A67D8] text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Coba Lagi
                </a>
                <a href="{{ route('home') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
