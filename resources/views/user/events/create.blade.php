@extends('layouts.app')

@section('title', 'Buat Event Baru')

@section('content')
<div class="min-h-screen bg-gray-50 py-4">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('user.events.index') }}" class="text-[#7681FF] hover:text-[#5A67D8] font-semibold text-sm mb-3 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <h1 class="text-lg font-bold text-gray-900 mt-3">Buat Event Baru</h1>
            <p class="text-gray-600 text-xs mt-1">Isi form di bawah untuk membuat event. Event akan direview oleh admin sebelum dipublikasikan.</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('user.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Contoh: Workshop Web Development"
                           required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Deskripsi Event <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Jelaskan detail event kamu..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="event_date" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Event <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="event_date" 
                               name="event_date" 
                               value="{{ old('event_date') }}"
                               min="{{ now()->addDays(3)->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('event_date') border-red-500 @enderror"
                               required>
                        @error('event_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[10px] text-gray-500 mt-1">
                            <i class="fas fa-info-circle"></i> Event harus dibuat minimal H-3
                        </p>
                    </div>

                    <div>
                        <label for="event_time" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Waktu Event <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="event_time" 
                               name="event_time" 
                               value="{{ old('event_time') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('event_time') border-red-500 @enderror"
                               required>
                        @error('event_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-4">
                    <label for="location" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Lokasi Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('location') border-red-500 @enderror"
                           placeholder="Contoh: Gedung A Lantai 3, Universitas XYZ"
                           required>
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity & Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="capacity" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kapasitas Peserta
                        </label>
                        <input type="number" 
                               id="capacity" 
                               name="capacity" 
                               value="{{ old('capacity') }}"
                               min="1"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('capacity') border-red-500 @enderror"
                               placeholder="Kosongkan jika unlimited">
                        @error('capacity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Harga Tiket (Rp)
                        </label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="{{ old('price', 0) }}"
                               min="0"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7681FF] focus:border-transparent @error('price') border-red-500 @enderror"
                               placeholder="0 untuk gratis">
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Flyer Upload -->
                <div class="mb-4">
                    <label for="flyer" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Flyer Event
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="flyer" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-2"></i>
                                <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-[10px] text-gray-500">PNG, JPG (MAX. 2MB)</p>
                            </div>
                            <input id="flyer" name="flyer" type="file" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" src="" alt="Preview" class="max-h-48 rounded-lg mx-auto">
                    </div>
                    @error('flyer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2 text-sm"></i>
                        <div class="text-xs text-blue-700">
                            <p class="font-semibold mb-1">Catatan Penting:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                <li>Event akan direview oleh admin sebelum dipublikasikan</li>
                                <li>Proses review memakan waktu 1-3 hari kerja</li>
                                <li>Pastikan semua informasi yang diisi sudah benar</li>
                                <li>Event harus dibuat minimal H-3 sebelum tanggal pelaksanaan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('user.events.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit" 
                            class="gradient-bg text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-200 flex items-center space-x-2 text-sm">
                        <i class="fas fa-paper-plane"></i>
                        <span>Submit Event</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
