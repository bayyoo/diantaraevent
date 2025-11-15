<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buat Organisasi - Nexus Experience Manager</title>
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
                    }
                }
            }
        }
    </script>
    <style>
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Logo at top center -->
    <div class="text-center py-6 bg-white">
        <img src="{{ asset('images/diantara-nexus-logo.png') }}" alt="Nexus" class="h-12 mx-auto">
    </div>

    <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-lg w-full space-y-6">
            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Buat Organisasi</h2>
                <p class="text-gray-600 text-sm">Silahkan isi keterangan organisasi Anda</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-center">
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

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form method="POST" action="{{ route('diantaranexus.setup-organization.submit') }}">
                    @csrf
                    <input type="hidden" name="organization_type" value="business">
                    
                    <!-- Organization Name -->
                    <div class="mb-4">
                        <input type="text" 
                               id="organization_name" 
                               name="organization_name" 
                               value="{{ old('organization_name', $partner['organization_name'] ?? '') }}"
                               placeholder="Nama organisasi / komunitas / kelompok / personal"
                               required 
                               class="form-input w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm @error('organization_name') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Data ini akan ditampilkan sebagai nama Creator di halaman GOERS</p>
                    </div>

                    <!-- Position -->
                    <div class="mb-4">
                        <input type="text" 
                               id="position" 
                               name="position" 
                               value="{{ old('position') }}"
                               placeholder="Posisi Anda dalam organisasi"
                               required 
                               class="form-input w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm @error('position') border-red-500 @enderror">
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <select id="category" 
                                name="category" 
                                required 
                                class="form-input w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm @error('category') border-red-500 @enderror">
                            <option value="">Kategori Organisasi</option>
                            <option value="Teknologi" {{ old('category') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                            <option value="Pendidikan" {{ old('category') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Bisnis" {{ old('category') == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                            <option value="Seni & Budaya" {{ old('category') == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                            <option value="Olahraga" {{ old('category') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                            <option value="Kesehatan" {{ old('category') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="Lingkungan" {{ old('category') == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                            <option value="Sosial" {{ old('category') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mb-5">
                        <p class="text-sm text-gray-700 mb-2">
                            Apakah Anda bersedia kami kontak sewaktu-waktu untuk menceritakan pengalaman Anda menggunakan GEM?
                        </p>
                        <div class="flex items-start space-x-2">
                            <input type="checkbox" 
                                   id="agree_terms" 
                                   name="agree_terms" 
                                   value="1"
                                   required
                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-0.5">
                            <label for="agree_terms" class="text-sm text-gray-700">
                                Ya, saya bersedia
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-primary-dark text-white py-2.5 px-4 rounded-full font-medium text-sm transition-all">
                        SUBMIT DATA & LANJUTKAN
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
        </div>
    </div>

    <script>
        // Auto-focus first input
        document.addEventListener('DOMContentLoaded', function() {
            const orgNameInput = document.getElementById('organization_name');
            if (!orgNameInput.value.trim()) {
                orgNameInput.focus();
            }
        });

        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['organization_name', 'position', 'category'];
            let isValid = true;

            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            const agreeTerms = document.getElementById('agree_terms');
            if (!agreeTerms.checked) {
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang diperlukan.');
            }
        });
    </script>
</body>
</html>
