<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - Partner Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#2563eb',
                        'primary-dark': '#1d4ed8',
                        'secondary': '#374151',
                    }
                }
            }
        }
    </script>
    <style>
        .btn-primary {
            background: #2563eb;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .otp-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        .otp-input.filled {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Logo at top center -->
    <div class="text-center py-8 bg-white">
        <img src="{{ asset('images/diantara-nexus-logo.png') }}" alt="Diantara Nexus" class="h-16 mx-auto">
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Akun Anda</h2>
                <p class="text-gray-600 mb-2">Silahkan masukkan kode verifikasi akun yang dikirim ke email</p>
                <p class="text-primary font-semibold text-sm">{{ $email }}</p>
                <button type="button" onclick="changeEmail()" class="text-sm text-primary hover:text-primary-dark mt-1 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Ganti email
                </button>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Masukkan Kode Verifikasi</h3>
                </div>

                <form method="POST" action="{{ route('diantaranexus.verify-otp.submit') }}" id="otpForm">
                    @csrf

                    <!-- OTP Input Fields -->
                    <div class="flex justify-center space-x-3 mb-6">
                        <input type="text" 
                               class="otp-input" 
                               maxlength="1" 
                               data-index="0"
                               autocomplete="off">
                        <input type="text" 
                               class="otp-input" 
                               maxlength="1" 
                               data-index="1"
                               autocomplete="off">
                        <input type="text" 
                               class="otp-input" 
                               maxlength="1" 
                               data-index="2"
                               autocomplete="off">
                        <input type="text" 
                               class="otp-input" 
                               maxlength="1" 
                               data-index="3"
                               autocomplete="off">
                        <input type="text" 
                               class="otp-input" 
                               maxlength="1" 
                               data-index="4"
                               autocomplete="off">
                        <input type="text" 
                               class="otp-input" 
                               maxlength="1" 
                               data-index="5"
                               autocomplete="off">
                    </div>

                    <!-- Hidden input for actual OTP -->
                    <input type="hidden" name="otp" id="otpValue">

                    <!-- Resend OTP -->
                    <div class="text-center mb-6">
                        <button type="button" 
                                id="resendBtn"
                                onclick="resendOtp()"
                                class="text-primary hover:text-primary-dark font-medium text-sm">
                            Kirim Ulang Kode Verifikasi
                        </button>
                        <p id="countdown" class="text-xs text-gray-500 mt-1" style="display: none;"></p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitBtn"
                            disabled
                            class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-full font-medium transition-all cursor-not-allowed">
                        SUBMIT KODE
                    </button>
                </form>
            </div>

            <!-- Contact Link -->
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    Punya Pertanyaan? 
                    <a href="#" class="text-primary hover:text-primary-dark font-medium">
                        Hubungi CS Nexus
                    </a>
                </p>
            </div>
    <script>
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');
        const submitBtn = document.getElementById('submitBtn');
        let resendTimeout;

        // OTP Input Handler
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const value = e.target.value;
                
                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    e.target.value = '';
                    return;
                }

                if (value) {
                    e.target.classList.add('filled');
                    // Move to next input
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                } else {
                    e.target.classList.remove('filled');
                }

                updateOtpValue();
            });

            input.addEventListener('keydown', function(e) {
                // Handle backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                    updateOtpValue();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                const digits = pastedData.replace(/\D/g, '').slice(0, 6);
                
                digits.split('').forEach((digit, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = digit;
                        otpInputs[i].classList.add('filled');
                    }
                });
                
                updateOtpValue();
            });
        });

        function updateOtpValue() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpValue.value = otp;
            
            if (otp.length === 6) {
                submitBtn.disabled = false;
                submitBtn.className = 'w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-full font-medium transition-all cursor-pointer';
                submitBtn.textContent = 'SUBMIT KODE';
            } else {
                submitBtn.disabled = true;
                submitBtn.className = 'w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-full font-medium transition-all cursor-not-allowed';
                submitBtn.textContent = 'SUBMIT KODE';
            }
        }

        function resendOtp() {
            const resendBtn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            
            // Disable resend button
            resendBtn.disabled = true;
            resendBtn.textContent = 'Mengirim...';
            
            // Send resend request
            fetch('{{ route("diantaranexus.resend-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showMessage('Kode OTP baru telah dikirim!', 'success');
                    
                    // Start countdown
                    startCountdown();
                } else {
                    showMessage('Gagal mengirim kode. Silakan coba lagi.', 'error');
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Kirim Ulang Kode Verifikasi';
                }
            })
            .catch(error => {
                showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
                resendBtn.disabled = false;
                resendBtn.textContent = 'Kirim Ulang Kode Verifikasi';
            });
        }

        function startCountdown() {
            const resendBtn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            let timeLeft = 60;
            
            countdown.style.display = 'block';
            
            const timer = setInterval(() => {
                countdown.textContent = `Kirim ulang dalam ${timeLeft} detik`;
                timeLeft--;
                
                if (timeLeft < 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Kirim Ulang Kode Verifikasi';
                    countdown.style.display = 'none';
                }
            }, 1000);
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `p-4 rounded-lg text-center mb-4 ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'}`;
            messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>${message}`;
            
            const form = document.querySelector('form');
            form.parentNode.insertBefore(messageDiv, form);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }

        function changeEmail() {
            if (confirm('Apakah Anda yakin ingin mengganti email? Anda akan diarahkan kembali ke halaman pendaftaran.')) {
                window.location.href = '{{ route("diantaranexus.register") }}';
            }
        }

        // Auto focus first input
        otpInputs[0].focus();
    </script>
</body>
</html>
