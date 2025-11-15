@extends('admin.layout')

@section('title', 'Verifikasi Kehadiran - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Verifikasi Kehadiran</h1>
                <p class="text-gray-600">{{ $event->title }}</p>
                <p class="text-sm text-gray-500">{{ $event->event_date->format('d F Y') }} - {{ $event->location }}</p>
            </div>
            <div class="text-right">
                <div class="bg-blue-100 px-4 py-2 rounded-lg">
                    <p class="text-sm text-blue-600">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $totalRegistrations }}</p>
                </div>
                <div class="bg-green-100 px-4 py-2 rounded-lg mt-2">
                    <p class="text-sm text-green-600">Sudah Hadir</p>
                    <p class="text-2xl font-bold text-green-800">{{ $totalAttended }}</p>
                </div>
            </div>
        </div>

        <!-- Token Verification Form -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Verifikasi Token Kehadiran</h2>
            
            <form id="tokenVerificationForm" class="space-y-4">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                
                <div>
                    <label for="attendance_token" class="block text-sm font-medium text-gray-700 mb-2">
                        Token Kehadiran (10 digit)
                    </label>
                    <input 
                        type="text" 
                        id="attendance_token" 
                        name="attendance_token" 
                        maxlength="10"
                        pattern="[0-9]{10}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-2xl font-mono"
                        placeholder="0123456789"
                        required
                        autocomplete="off"
                    >
                </div>
                
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 font-semibold"
                >
                    Verifikasi Kehadiran
                </button>
            </form>

            <!-- Result Display -->
            <div id="verificationResult" class="mt-4 hidden">
                <!-- Success/Error messages will be displayed here -->
            </div>
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white rounded-lg border">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Kehadiran Terbaru</h3>
            </div>
            <div id="recentAttendances" class="divide-y">
                <!-- Recent attendances will be loaded here -->
            </div>
        </div>

        <!-- Generate Certificates Button -->
        @if($event->has_certificate)
        <div class="mt-6 text-center">
            <button 
                id="generateCertificatesBtn"
                class="bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 font-semibold"
            >
                Generate Sertifikat untuk Semua Peserta
            </button>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tokenVerificationForm');
    const resultDiv = document.getElementById('verificationResult');
    const tokenInput = document.getElementById('attendance_token');
    
    // Auto-focus on token input
    tokenInput.focus();
    
    // Only allow numbers
    tokenInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Auto-submit when 10 digits entered
    tokenInput.addEventListener('input', function(e) {
        if (this.value.length === 10) {
            form.dispatchEvent(new Event('submit'));
        }
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const token = formData.get('attendance_token');
        
        if (token.length !== 10) {
            showResult('error', 'Token harus 10 digit angka');
            return;
        }
        
        // Show loading
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Memverifikasi...';
        submitBtn.disabled = true;
        
        fetch('{{ route("attendance.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                attendance_token: token,
                event_id: {{ $event->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showResult('success', data.message, data.user);
                form.reset();
                tokenInput.focus();
                loadRecentAttendances();
                updateStats();
            } else {
                showResult('error', data.message, data.user);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showResult('error', 'Terjadi kesalahan saat verifikasi');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    function showResult(type, message, user = null) {
        const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        const icon = type === 'success' ? '✓' : '✗';
        
        let userInfo = '';
        if (user) {
            userInfo = `
                <div class="mt-2 text-sm">
                    <strong>Nama:</strong> ${user.name}<br>
                    ${user.email ? `<strong>Email:</strong> ${user.email}<br>` : ''}
                    ${user.attended_at ? `<strong>Waktu Hadir:</strong> ${new Date(user.attended_at).toLocaleString('id-ID')}` : ''}
                </div>
            `;
        }
        
        resultDiv.innerHTML = `
            <div class="border-l-4 p-4 ${bgColor}">
                <div class="flex items-center">
                    <span class="text-xl mr-2">${icon}</span>
                    <span class="font-medium">${message}</span>
                </div>
                ${userInfo}
            </div>
        `;
        resultDiv.classList.remove('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            resultDiv.classList.add('hidden');
        }, 5000);
    }
    
    function loadRecentAttendances() {
        // Load recent attendances via AJAX
        // Implementation can be added later
    }
    
    function updateStats() {
        // Update attendance statistics
        // Implementation can be added later
    }
    
    // Generate certificates button
    @if($event->has_certificate)
    document.getElementById('generateCertificatesBtn').addEventListener('click', function() {
        if (confirm('Generate sertifikat untuk semua peserta yang sudah hadir?')) {
            const btn = this;
            const originalText = btn.textContent;
            btn.textContent = 'Generating...';
            btn.disabled = true;
            
            fetch(`/admin/events/{{ $event->id }}/generate-certificates`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Berhasil generate ${data.count} sertifikat`);
                } else {
                    alert('Gagal generate sertifikat: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat generate sertifikat');
            })
            .finally(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            });
        }
    });
    @endif
    
    // Load initial data
    loadRecentAttendances();
});
</script>
@endpush
@endsection
