<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7f4ca5',
                        'primary-dark': '#6b3d8a',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detail Event</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.events.edit', $event) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                        Edit
                    </a>
                    <a href="{{ route('admin.events.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Event Details -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $event->title }}</h2>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-600 mb-1"><strong>Slug:</strong></p>
                            <p class="text-gray-800">{{ $event->slug }}</p>
                        </div>
                    </div>

                    @if($event->description)
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Deskripsi</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Tanggal & Waktu</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">
                                    <strong>Tanggal:</strong> {{ $event->event_date->format('d F Y') }}
                                </p>
                                @if($event->event_time)
                                    <p class="text-gray-700 mt-1">
                                        <strong>Waktu:</strong> {{ $event->event_time->format('H:i') }} WIB
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Lokasi</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Kapasitas</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">
                                    {{ $event->capacity ? number_format($event->capacity) . ' orang' : 'Tidak terbatas' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Dibuat Oleh</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">{{ $event->creator->name }}</p>
                                <p class="text-sm text-gray-500">{{ $event->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($event->updated_at != $event->created_at)
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Terakhir Diperbarui</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">{{ $event->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Event Flyer -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Flyer Event</h3>
                    @if($event->flyer_path)
                        <div class="bg-gray-50 p-4 rounded-md">
                            <img src="{{ asset('storage/' . $event->flyer_path) }}" 
                                 alt="Flyer {{ $event->title }}" 
                                 class="w-full h-auto rounded-md shadow-md">
                            <div class="mt-4">
                                <a href="{{ asset('storage/' . $event->flyer_path) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Lihat Ukuran Penuh
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 p-8 rounded-md text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">Tidak ada flyer yang diupload</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" 
                      onsubmit="return confirm('Yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md">
                        Hapus Event
                    </button>
                </form>
                <a href="{{ route('admin.events.edit', $event) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md">
                    Edit Event
                </a>
            </div>
        </div>
    </div>
</body>
</html>
