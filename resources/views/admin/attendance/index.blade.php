@extends('admin.layout')

@section('title', 'Absensi Peserta')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Absensi Peserta</h1>
            <p class="text-gray-600 mt-1">Kelola absensi peserta event</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <!-- Token Input Form -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-qrcode mr-2 text-[#7681FF]"></i>
                    Scan Token Absensi
                </h2>
                <form method="POST" action="{{ route('admin.attendance.checkin') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="token" class="block text-sm font-semibold text-gray-700 mb-2">Token Peserta</label>
                        <input 
                            type="text" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent text-center text-2xl font-mono tracking-widest" 
                            id="token" 
                            name="token" 
                            placeholder="0000000000"
                            required
                            autofocus
                            maxlength="10"
                        >
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Token terdiri dari 10 digit angka yang diterima peserta via email
                        </p>
                    </div>
                    
                    <button type="submit" class="w-full gradient-bg text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Konfirmasi Absensi
                    </button>
                </form>
            </div>

            <!-- Instructions -->
            <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200 mt-6">
                <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Petunjuk Penggunaan
                </h3>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span>Minta peserta untuk memberikan token 10 digit yang diterima via email</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span>Masukkan token pada form di atas dan klik "Konfirmasi Absensi"</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span>Sistem akan otomatis memvalidasi dan mencatat kehadiran peserta</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span>Peserta yang sudah absen tidak dapat melakukan absensi ulang</span>
                    </li>
                </ul>
            </div>
        </div>

        <div>
            <!-- Recent Attendances -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-history mr-2 text-[#7681FF]"></i>
                    Absensi Terbaru
                </h2>
                @if($recentAttendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Peserta</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Event</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Waktu</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Token</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentAttendances->take(10) as $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-900">{{ $attendance->participant->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->participant->user->email }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                {{ Str::limit($attendance->event->title, 20) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-gray-700">{{ $attendance->attended_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->attended_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <code class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $attendance->token_used }}</code>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-inbox text-5xl mb-3"></i>
                        <p class="text-gray-600">Belum ada data absensi</p>
                    </div>
                @endif
            </div>

            <!-- Statistics Card -->
            <div class="bg-gradient-to-r from-[#7681FF] to-[#5A67D8] rounded-xl p-6 mt-6 text-white">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-3xl font-bold mb-1">{{ $recentAttendances->count() }}</div>
                        <div class="text-sm opacity-90">Absensi Hari Ini</div>
                    </div>
                    <div class="border-l border-r border-white/30">
                        <div class="text-3xl font-bold mb-1">
                            {{ $recentAttendances->where('attended_at', '>=', now()->startOfWeek())->count() }}
                        </div>
                        <div class="text-sm opacity-90">Minggu Ini</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-1">
                            {{ $recentAttendances->where('attended_at', '>=', now()->startOfMonth())->count() }}
                        </div>
                        <div class="text-sm opacity-90">Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tokenInput = document.getElementById('token');
    
    // Auto-focus and format token input
    tokenInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/\D/g, '');
        
        // Limit to 10 digits
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        e.target.value = value;
    });
    
    // Auto-submit when 10 digits entered
    tokenInput.addEventListener('input', function(e) {
        if (e.target.value.length === 10) {
            // Optional: Auto-submit after short delay
            setTimeout(() => {
                if (confirm('Token lengkap terdeteksi. Konfirmasi absensi sekarang?')) {
                    e.target.closest('form').submit();
                }
            }, 500);
        }
    });
});
</script>
@endsection
