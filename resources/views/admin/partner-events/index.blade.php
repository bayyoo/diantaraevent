@extends('admin.layout')

@section('title', 'Partner Events Management')
@section('page-title', 'Partner Events Management')
@section('page-subtitle', 'Manage Events from Diantara Nexus Partners')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Partner Events</h3>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">Total: {{ $events->total() }} events</span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                            <div class="text-sm text-gray-500">{{ $event->location }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm text-gray-900">{{ $event->partner->name }}</div>
                            <div class="text-sm text-gray-500">{{ $event->organization->name ?? 'No organization' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                        <br>
                        {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($event->status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        @elseif($event->status === 'pending_review')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending Review
                            </span>
                        @elseif($event->status === 'draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.partner-events.show', $event) }}" class="text-blue-600 hover:text-blue-900">
                                View
                            </a>
                            @if($event->status === 'pending_review')
                                <form method="POST" action="{{ route('admin.partner-events.approve', $event) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.partner-events.reject', $event) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Reject
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                            <p>No partner events found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($events->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection
