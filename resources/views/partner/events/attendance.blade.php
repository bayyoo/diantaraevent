@extends('partner.layout-simple')

@section('title', 'Absensi Event')
@section('page-title', 'ABSENSI EVENT')
@section('page-subtitle', '')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Absensi Event</h1>
            <p class="text-sm text-gray-600">Input token absensi peserta pada event <span class="font-semibold">{{ $event->title }}</span>.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Total Pendaftar</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalRegistrations }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Sudah Hadir</p>
                <p class="text-2xl font-semibold text-emerald-600">{{ $totalAttended }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Belum Hadir</p>
                <p class="text-2xl font-semibold text-amber-600">{{ max($totalRegistrations - $totalAttended, 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Form Input Token</h2>
            <p class="text-sm text-gray-600 mb-4">Minta peserta menunjukkan token dari email / e-ticket, lalu masukkan 10 digit token tersebut di bawah ini.</p>

            <form action="{{ route('diantaranexus.events.attendance.store', $event->id) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                @csrf
                <input type="text" name="attendance_token" maxlength="10" minlength="10" required
                       class="w-full sm:w-64 px-3 py-2 rounded-md border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent tracking-[0.3em] uppercase"
                       placeholder="XXXXXXXXXX">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                    <i class="fas fa-check mr-2"></i> Tandai Hadir
                </button>
            </form>

            @error('attendance_token')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
@endsection
