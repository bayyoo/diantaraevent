@extends('layouts.app')

@section('title', 'Daftar Event - ' . $event->title)

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('events.show', $event) }}" class="text-[#7681FF] hover:text-[#5A67D8] font-semibold text-sm inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Event
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Event Info -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Event</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $event->title }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($event->description, 150) }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Tanggal:</span>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Waktu:</span>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Lokasi:</span>
                            <p class="font-medium">{{ $event->location }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Harga Tiket:</span>
                            <p class="font-bold text-[#7681FF]">
                                @if($event->price > 0)
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                @else
                                    GRATIS
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Formulir Pendaftaran</h2>
                
                <form id="registrationForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="amount" value="{{ $event->price }}">
                    
                    <div>
                        <label for="participant_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap *
                        </label>
                        <input type="text" 
                               id="participant_name" 
                               name="participant_name" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent"
                               placeholder="Masukkan nama lengkap Anda">
                    </div>
                    
                    <div>
                        <label for="participant_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email" 
                               id="participant_email" 
                               name="participant_email" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent"
                               placeholder="nama@email.com">
                    </div>
                    
                    <div>
                        <label for="participant_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp *
                        </label>
                        <input type="tel" 
                               id="participant_phone" 
                               name="participant_phone" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent"
                               placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-gray-50 rounded-xl p-4 mt-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Ringkasan Pembayaran</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Harga Tiket</span>
                                <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Biaya Admin</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between font-bold">
                                <span>Total</span>
                                <span class="text-[#7681FF]">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="payButton"
                            class="w-full bg-[#7681FF] hover:bg-[#5A67D8] text-white font-semibold py-3 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        @if($event->price > 0)
                            Bayar Sekarang
                        @else
                            Daftar Gratis
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const payButton = document.getElementById('payButton');
    const originalText = payButton.textContent;
    
    // Disable button and show loading
    payButton.disabled = true;
    payButton.textContent = 'Memproses...';
    
    // Get form data
    const formData = new FormData(this);
    
    // If event is free, handle differently
    @if($event->price == 0)
        // For free events, directly register without payment
        fetch('{{ route("events.register") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("events.registration-success") }}';
            } else {
                alert('Gagal mendaftar: ' + data.message);
                payButton.disabled = false;
                payButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            payButton.disabled = false;
            payButton.textContent = originalText;
        });
    @else
        // For paid events, create payment (Xendit)
        fetch('{{ route("payment.create") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.payment_url) {
                window.location.href = data.payment_url;
            } else {
                alert('Gagal membuat pembayaran: ' + (data.message || 'Payment URL tidak tersedia.'));
                payButton.disabled = false;
                payButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            payButton.disabled = false;
            payButton.textContent = originalText;
        });
    @endif
});
</script>
@endsection
