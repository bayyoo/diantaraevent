<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NexusController extends Controller
{
    public function dashboard()
    {
        // 1. Jumlah kegiatan yang terlaksana setiap bulan (Januari - Desember)
        $eventsPerMonth = Event::selectRaw('MONTH(event_date) as month, COUNT(*) as count')
            ->whereYear('event_date', Carbon::now()->year)
            ->where('event_date', '<=', Carbon::now())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyEvents = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyEvents[$i] = $eventsPerMonth[$i] ?? 0;
        }

        // 2. Jumlah peserta yang mengikuti kegiatan setiap bulan (yang sudah absensi)
        $participantsPerMonth = Attendance::selectRaw('MONTH(events.event_date) as month, COUNT(*) as count')
            ->join('events', 'attendances.event_id', '=', 'events.id')
            ->whereYear('events.event_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyParticipants = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyParticipants[$i] = $participantsPerMonth[$i] ?? 0;
        }

        // 3. Sepuluh kegiatan dengan jumlah peserta terbanyak
        $topEvents = Event::withCount(['participants as attended_count' => function ($query) {
                $query->where('status', 'attended');
            }])
            ->orderBy('attended_count', 'desc')
            ->limit(10)
            ->get();

        // Statistics summary
        $totalEvents = Event::count();
        $totalParticipants = Participant::count();
        $totalAttendees = Attendance::count();
        $upcomingEvents = Event::where('event_date', '>', Carbon::now())->count();
        $totalUsers = User::count();
        
        // Past events (completed)
        $pastEvents = Event::where('event_date', '<', Carbon::now())->count();
        
        // Ongoing events (today)
        $ongoingEvents = Event::whereDate('event_date', Carbon::today())->count();
        
        // This month statistics
        $eventsThisMonth = Event::whereMonth('event_date', Carbon::now()->month)
            ->whereYear('event_date', Carbon::now()->year)
            ->count();
        $participantsThisMonth = Participant::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // Last month statistics for comparison
        $eventsLastMonth = Event::whereMonth('event_date', Carbon::now()->subMonth()->month)
            ->whereYear('event_date', Carbon::now()->subMonth()->year)
            ->count();
        $participantsLastMonth = Participant::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        
        // Calculate growth percentage
        $eventsGrowth = $eventsLastMonth > 0 
            ? round((($eventsThisMonth - $eventsLastMonth) / $eventsLastMonth) * 100, 1)
            : 0;
        $participantsGrowth = $participantsLastMonth > 0 
            ? round((($participantsThisMonth - $participantsLastMonth) / $participantsLastMonth) * 100, 1)
            : 0;
        
        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentEvents = Event::with('creator')->latest()->take(5)->get();
        $recentParticipants = Participant::with(['event', 'user'])->latest()->take(5)->get();
        
        // Attendance rate
        $attendanceRate = $totalParticipants > 0 
            ? round(($totalAttendees / $totalParticipants) * 100, 1)
            : 0;

        return view('admin.dashboard', compact(
            'monthlyEvents',
            'monthlyParticipants', 
            'topEvents',
            'totalEvents',
            'totalParticipants',
            'totalAttendees',
            'upcomingEvents',
            'totalUsers',
            'pastEvents',
            'ongoingEvents',
            'eventsThisMonth',
            'participantsThisMonth',
            'eventsGrowth',
            'participantsGrowth',
            'recentUsers',
            'recentEvents',
            'recentParticipants',
            'attendanceRate'
        ));
    }
}
