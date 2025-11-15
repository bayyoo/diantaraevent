<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - {{ config('app.name', 'Diantara') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7681FF',
                        'primary-dark': '#5A67D8',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', system-ui, sans-serif;
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(118, 129, 255, 0.15);
        }
        .otp-input {
            width: 3rem;
            height: 3rem;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            border: 0;
            border-bottom: 2px solid #e5e7eb;
            background: transparent;
            transition: all 0.3s ease;
        }
        .otp-input:focus {
            outline: none;
            border-bottom-color: #7681FF;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(118, 129, 255, 0.15);
        }
        .otp-input:not(:placeholder-shown) {
            border-bottom-color: #7681FF;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Main Auth Card -->
    <div class="pt-6 pb-8 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                
                <!-- Header -->
                <div class="text-center mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1.5">Verifikasi Email Anda</h1>
                    <p class="text-gray-600 text-xs">Kami telah mengirimkan kode verifikasi ke email Anda</p>
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

            <!-- OTP Form -->
            <form method="POST" action="{{ route('verify.otp.store') }}" class="space-y-6">
                @csrf
                
                <!-- Email Display -->
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-600">
                        Kode dikirim ke: <strong class="text-gray-900">{{ session('verification_email') ?: session('email') ?: old('email') ?: 'EMAIL_TIDAK_DITEMUKAN' }}</strong>
                    </p>
                    <input type="hidden" name="email" value="{{ session('verification_email') ?: session('email') ?: old('email') ?: '' }}">
                    
                    @if(!session('verification_email') && !session('email') && !old('email'))
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl mt-4 text-left">
                            <div class="flex items-start">
                                <svg class="flex-shrink-0 w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium">Email tidak ditemukan</p>
                                    <p class="text-xs mt-1">Silakan masukkan email Anda secara manual:</p>
                                    <input type="email" name="manual_email" placeholder="contoh@email.com" 
                                           class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- OTP Input -->
                <div class="space-y-4">
                    <label for="otp_code" class="block text-sm font-medium text-gray-700 text-center">Masukkan 6 digit kode verifikasi</label>
                    
                    <div class="flex justify-center space-x-3">
                        <input type="text" maxlength="1" class="otp-input input-focus" pattern="\d*" inputmode="numeric" autocomplete="one-time-code" />
                        <input type="text" maxlength="1" class="otp-input input-focus" pattern="\d*" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-input input-focus" pattern="\d*" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-input input-focus" pattern="\d*" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-input input-focus" pattern="\d*" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-input input-focus" pattern="\d*" inputmode="numeric" />
                    </div>
                    
                    <!-- Hidden input that will contain the actual OTP value -->
                    <input type="hidden" id="otp_code" name="otp_code" required>
                    
                    @error('otp_code')
                        <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                    @enderror
                    
                    <p class="text-xs text-gray-500 text-center mt-2">
                        Kode akan kadaluarsa dalam <span id="countdown" class="font-semibold text-primary">05:00</span>
                    </p>
                </div>

                <!-- Verify Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary text-white py-3 px-4 rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 font-medium"
                >
                    Verifikasi Kode
                </button>
            </form>
            
                <!-- Resend OTP -->
                <div class="text-center mt-6">
                    <form method="POST" action="{{ route('otp.resend') }}" class="inline-block">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') ?: old('email') }}">
                        <button type="submit" class="text-primary hover:text-primary-dark text-sm font-medium transition-colors" id="resendBtn">
                            <span id="resendText">Kirim ulang kode</span>
                        </button>
                    </form>
                </div>
                
                <!-- Back to Login -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-primary hover:text-primary-dark font-medium transition-colors text-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp_code');
            
            // Auto-focus first input
            if (otpInputs.length > 0) {
                otpInputs[0].focus();
            }
            
            // Handle OTP input
            otpInputs.forEach((input, index) => {
                // Handle paste event
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = e.clipboardData.getData('text');
                    const digits = paste.replace(/\D/g, '').split('');
                    
                    digits.forEach((digit, i) => {
                        if (index + i < otpInputs.length) {
                            otpInputs[index + i].value = digit;
                        }
                    });
                    
                    // Focus on the last filled input
                    const lastFilledIndex = Math.min(index + digits.length - 1, otpInputs.length - 1);
                    if (lastFilledIndex < otpInputs.length - 1) {
                        otpInputs[lastFilledIndex + 1].focus();
                    } else {
                        otpInputs[lastFilledIndex].blur();
                    }
                    
                    updateHiddenInput();
                });
                
                // Handle input
                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    
                    // Only allow numbers
                    if (value && !/^\d*$/.test(value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    // Move to next input on number input
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    // If backspace pressed and input is empty, move to previous input
                    if (e.inputType === 'deleteContentBackward' && !value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    
                    updateHiddenInput();
                });
                
                // Handle keydown for backspace and navigation
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    } else if (e.key === 'ArrowLeft' && index > 0) {
                        e.preventDefault();
                        otpInputs[index - 1].focus();
                    } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                        e.preventDefault();
                        otpInputs[index + 1].focus();
                    }
                });
            });
            
            // Update hidden input with OTP value
            function updateHiddenInput() {
                const otp = Array.from(otpInputs).map(input => input.value).join('');
                hiddenInput.value = otp;
            }
            
            // Countdown timer
            function startCountdown() {
                let timeLeft = 300; // 5 minutes in seconds
                const countdownElement = document.getElementById('countdown');
                const resendBtn = document.getElementById('resendBtn');
                const resendText = document.getElementById('resendText');
                
                const timer = setInterval(() => {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    
                    countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        countdownElement.textContent = '00:00';
                        resendBtn.disabled = false;
                        resendText.textContent = 'Kirim ulang kode';
                    } else {
                        timeLeft--;
                    }
                }, 1000);
            }
            
            // Start countdown on page load
            startCountdown();
            
            // Handle resend button click
            const resendForm = document.querySelector('form[action$="otp/resend"]');
            if (resendForm) {
                resendForm.addEventListener('submit', function(e) {
                    const btn = this.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.querySelector('#resendText').textContent = 'Mengirim ulang...';
                    
                    // In a real app, you would make an AJAX request here
                    // and handle the response before resetting the countdown
                    
                    // For demo purposes, just reset the countdown after a short delay
                    setTimeout(() => {
                        startCountdown();
                        btn.querySelector('#resendText').textContent = 'Kode terkirim ulang';
                    }, 1500);
                });
            }
        });
    </script>

    @include('components.footer')
</body>
</html>
