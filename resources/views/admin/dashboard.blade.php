@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
    <p class="text-gray-600 mt-1">Event Management System Overview</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Events -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-alt text-lg text-white"></i>
            </div>
            @if($eventsGrowth != 0)
                <span class="px-2 py-1 {{ $eventsGrowth > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} text-xs font-medium rounded">
                    {{ $eventsGrowth > 0 ? '+' : '' }}{{ $eventsGrowth }}%
                </span>
            @endif
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Total Events</p>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalEvents) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $eventsThisMonth }} this month
            </p>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-lg text-white"></i>
            </div>
            @if($ongoingEvents > 0)
                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded">
                    Live
                </span>
            @endif
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Upcoming Events</p>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($upcomingEvents) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $pastEvents }} completed
            </p>
        </div>
    </div>

    <!-- Total Participants -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-lg text-white"></i>
            </div>
            @if($participantsGrowth != 0)
                <span class="px-2 py-1 {{ $participantsGrowth > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} text-xs font-medium rounded">
                    {{ $participantsGrowth > 0 ? '+' : '' }}{{ $participantsGrowth }}%
                </span>
            @endif
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Total Participants</p>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalParticipants) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $participantsThisMonth }} this month
            </p>
        </div>
    </div>

    <!-- Attendance Rate -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-lg text-white"></i>
            </div>
            <span class="px-2 py-1 bg-purple-50 text-purple-600 text-xs font-medium rounded">
                {{ $attendanceRate }}%
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Attendance Rate</p>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalAttendees) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                of {{ number_format($totalParticipants) }} participants
            </p>
        </div>
    </div>
</div>

<!-- Export Buttons -->
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('admin.dashboard.export', ['type' => 'events']) }}" 
       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
        <i class="fas fa-file-excel text-sm"></i>
        <span>Export Events</span>
    </a>
    <a href="{{ route('admin.dashboard.export', ['type' => 'participants']) }}" 
       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
        <i class="fas fa-file-csv text-sm"></i>
        <span>Export Participants</span>
    </a>
    <a href="{{ route('admin.dashboard.export', ['type' => 'attendances']) }}" 
       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
        <i class="fas fa-download text-sm"></i>
        <span>Export Attendances</span>
    </a>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Chart 1: Events Per Month -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-base font-semibold text-gray-800 mb-4">
            Events Per Month ({{ date('Y') }})
        </h3>
        <div style="height: 280px;">
            <canvas id="eventsPerMonthChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Participants Per Month -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-base font-semibold text-gray-800 mb-4">
            Participants Per Month ({{ date('Y') }})
        </h3>
        <div style="height: 280px;">
            <canvas id="participantsPerMonthChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart 3: Top 10 Events -->
<div class="bg-white rounded-lg p-5 border border-gray-200 mb-6">
    <h3 class="text-base font-semibold text-gray-800 mb-4">
        Top 10 Events by Participants
    </h3>
    <div style="height: 350px;">
        <canvas id="topEventsChart"></canvas>
    </div>
</div>

<script>
// Chart 1: Events Per Month
const eventsCtx = document.getElementById('eventsPerMonthChart').getContext('2d');
new Chart(eventsCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Events',
            data: [
                {{ $monthlyEvents[1] }}, {{ $monthlyEvents[2] }}, {{ $monthlyEvents[3] }}, 
                {{ $monthlyEvents[4] }}, {{ $monthlyEvents[5] }}, {{ $monthlyEvents[6] }}, 
                {{ $monthlyEvents[7] }}, {{ $monthlyEvents[8] }}, {{ $monthlyEvents[9] }}, 
                {{ $monthlyEvents[10] }}, {{ $monthlyEvents[11] }}, {{ $monthlyEvents[12] }}
            ],
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    precision: 0
                }
            }
        }
    }
});

// Chart 2: Participants Per Month
const participantsCtx = document.getElementById('participantsPerMonthChart').getContext('2d');
new Chart(participantsCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Participants',
            data: [
                {{ $monthlyParticipants[1] }}, {{ $monthlyParticipants[2] }}, {{ $monthlyParticipants[3] }}, 
                {{ $monthlyParticipants[4] }}, {{ $monthlyParticipants[5] }}, {{ $monthlyParticipants[6] }}, 
                {{ $monthlyParticipants[7] }}, {{ $monthlyParticipants[8] }}, {{ $monthlyParticipants[9] }}, 
                {{ $monthlyParticipants[10] }}, {{ $monthlyParticipants[11] }}, {{ $monthlyParticipants[12] }}
            ],
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgba(34, 197, 94, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    precision: 0
                }
            }
        }
    }
});

// Chart 3: Top 10 Events
const topEventsCtx = document.getElementById('topEventsChart').getContext('2d');
new Chart(topEventsCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($topEvents as $event)
                '{{ Str::limit($event->title, 30) }}',
            @endforeach
        ],
        datasets: [{
            label: 'Participants',
            data: [
                @foreach($topEvents as $event)
                    {{ $event->attended_count }},
                @endforeach
            ],
            backgroundColor: 'rgba(147, 51, 234, 0.8)',
            borderColor: 'rgba(147, 51, 234, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2.5,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    precision: 0
                }
            }
        }
    }
});
</script>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-base font-semibold text-gray-800 mb-4">
            Recent Users
        </h3>
        <div class="space-y-3">
            @forelse($recentUsers as $user)
                <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 last:border-0">
                    <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center text-white text-sm font-medium flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    @if($user->email_verified_at)
                        <span class="px-2 py-1 text-xs bg-green-50 text-green-600 rounded">✓</span>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500 py-4 text-sm">No users yet</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Events -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-base font-semibold text-gray-800 mb-4">
            Recent Events
        </h3>
        <div class="space-y-3">
            @forelse($recentEvents as $event)
                <div class="pb-3 border-b border-gray-100 last:border-0">
                    <p class="font-medium text-gray-800 text-sm truncate">{{ $event->title }}</p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $event->event_date->format('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-user mr-1"></i>
                            {{ $event->creator->name }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4 text-sm">No events yet</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-base font-semibold text-gray-800 mb-4">
            Recent Registrations
        </h3>
        <div class="space-y-3">
            @forelse($recentParticipants as $participant)
                <div class="pb-3 border-b border-gray-100 last:border-0">
                    <p class="font-medium text-gray-800 text-sm truncate">{{ $participant->name }}</p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-500 truncate flex-1 mr-2">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ Str::limit($participant->event->title, 20) }}
                        </p>
                        <span class="px-2 py-1 text-xs {{ $participant->status == 'attended' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }} rounded flex-shrink-0">
                            {{ ucfirst($participant->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4 text-sm">No registrations yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
