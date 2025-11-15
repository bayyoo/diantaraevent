@extends('admin.layout')

@section('title', 'Kelola Event')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Event</h1>
                <p class="text-gray-600 mt-1">Manage all your events</p>
            </div>
            <a href="{{ route('admin.events.create') }}" 
               class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Event</span>
            </a>
        </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Form -->
            <form method="GET" class="mb-6">
                <div class="flex gap-4">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari event berdasarkan judul, tanggal, atau lokasi..."
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="submit" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.events.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Events Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($event->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $event->event_date->format('d/m/Y') }}
                                    @if($event->event_time)
                                        <br><span class="text-gray-500">{{ $event->event_time->format('H:i') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $event->capacity ?? 'Tidak terbatas' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->creator->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.events.show', $event) }}" 
                                           class="text-primary hover:text-primary-dark">Lihat</a>
                                        <a href="{{ route('admin.events.edit', $event) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" 
                                              class="inline" onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    @if(request('search'))
                                        Tidak ada event yang ditemukan untuk pencarian "{{ request('search') }}"
                                    @else
                                        Belum ada event yang dibuat
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $events->links() }}
        </div>
        
        <!-- Info -->
        <div class="mt-4 text-center text-sm text-gray-600">
            Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events
        </div>
    </div>
@endsection
