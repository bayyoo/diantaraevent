@extends('admin.layout')

@section('title', 'Data Peserta')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Peserta</h1>
            <p class="text-gray-600 mt-1">Manage all event participants</p>
        </div>
        <a href="{{ route('admin.dashboard.export', ['type' => 'participants']) }}" 
           class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2">
            <i class="fas fa-file-excel"></i>
            <span>Export CSV</span>
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 flex gap-4">
        <form action="{{ route('admin.participants.index') }}" method="GET" class="flex-1 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama atau email..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nexus focus:border-transparent">
            <select name="event_id" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nexus">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->title }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nexus">
                <option value="">Semua Status</option>
                <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Registered</option>
                <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Attended</option>
            </select>
            <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all">
                <i class="fas fa-search"></i> Filter
            </button>
            @if(request('search') || request('event_id') || request('status'))
                <a href="{{ route('admin.participants.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-xl font-semibold transition-all">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Participants Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Nama</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Email</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Event</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Token</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Status</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Tanggal Daftar</th>
                    <th class="text-center py-4 px-4 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $participant)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($participant->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $participant->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-gray-600">{{ $participant->email }}</td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-900 font-medium">{{ Str::limit($participant->event->title, 30) }}</span>
                            <br>
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $participant->event->event_date->format('d M Y') }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <code class="bg-gray-100 px-3 py-1 rounded text-sm font-mono">{{ $participant->token }}</code>
                        </td>
                        <td class="py-4 px-4">
                            @if($participant->status == 'attended')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Attended
                                </span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-clock mr-1"></i>Registered
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600">
                            {{ $participant->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center space-x-2">
                                @if($participant->status == 'attended')
                                    <a href="{{ route('certificate.generate', $participant) }}" 
                                       class="text-purple-600 hover:text-purple-800 transition-colors" title="Download Certificate" target="_blank">
                                        <i class="fas fa-certificate"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.participants.destroy', $participant->id) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus peserta ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada peserta yang terdaftar</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $participants->links() }}
    </div>
    
    <!-- Info -->
    <div class="mt-4 text-center text-sm text-gray-600">
        Showing {{ $participants->firstItem() ?? 0 }} to {{ $participants->lastItem() ?? 0 }} of {{ $participants->total() }} participants
    </div>
</div>
@endsection
