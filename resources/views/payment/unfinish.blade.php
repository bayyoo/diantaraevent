@extends('layouts.app')

@section('title', 'Pembayaran Belum Selesai')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            <!-- Warning Icon -->
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>

            <!-- Warning Message -->
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Pembayaran Belum Selesai</h1>
            <p class="text-gray-600 mb-6">Pembayaran Anda belum diselesaikan. Jangan khawatir, Anda masih bisa melanjutkan pembayaran.</p>

            <!-- Information -->
            <div class="bg-yellow-50 rounded-xl p-6 mb-6 text-left">
                <h3 class="font-semibold text-yellow-900 mb-3">Informasi Penting</h3>
                <ul class="space-y-2 text-yellow-800">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Pesanan Anda masih tersimpan dan dapat dilanjutkan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Silakan hubungi customer service jika mengalami kendala</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        <span>Anda dapat mencoba mendaftar ulang untuk event yang sama</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('catalog.index') }}" 
                   class="bg-[#7681FF] hover:bg-[#5A67D8] text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Coba Daftar Lagi
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
