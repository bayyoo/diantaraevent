<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
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
        $pastEvents = Event::where('event_date', '<', Carbon::now())->count();
        $ongoingEvents = Event::whereDate('event_date', Carbon::today())->count();
        $totalUsers = User::count();
        
        // This month stats
        $eventsThisMonth = Event::whereYear('event_date', Carbon::now()->year)
            ->whereMonth('event_date', Carbon::now()->month)
            ->count();
        
        $participantsThisMonth = Participant::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Last month stats for growth calculation
        $eventsLastMonth = Event::whereYear('event_date', Carbon::now()->subMonth()->year)
            ->whereMonth('event_date', Carbon::now()->subMonth()->month)
            ->count();
        
        $participantsLastMonth = Participant::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        
        // Calculate growth percentage
        $eventsGrowth = $eventsLastMonth > 0 
            ? round((($eventsThisMonth - $eventsLastMonth) / $eventsLastMonth) * 100) 
            : 0;
        
        $participantsGrowth = $participantsLastMonth > 0 
            ? round((($participantsThisMonth - $participantsLastMonth) / $participantsLastMonth) * 100) 
            : 0;
        
        // Attendance rate
        $attendanceRate = $totalParticipants > 0 
            ? round(($totalAttendees / $totalParticipants) * 100) 
            : 0;
        
        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentEvents = Event::with('creator')->latest()->take(5)->get();
        $recentParticipants = Participant::with('event')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'monthlyEvents',
            'monthlyParticipants', 
            'topEvents',
            'totalEvents',
            'totalParticipants',
            'totalAttendees',
            'upcomingEvents',
            'pastEvents',
            'ongoingEvents',
            'totalUsers',
            'eventsThisMonth',
            'participantsThisMonth',
            'eventsGrowth',
            'participantsGrowth',
            'attendanceRate',
            'recentUsers',
            'recentEvents',
            'recentParticipants'
        ));
    }

    public function exportData(Request $request)
    {
        $type = $request->get('type', 'events');
        
        if ($type === 'events') {
            return $this->exportEvents();
        } elseif ($type === 'participants') {
            return $this->exportParticipants();
        } elseif ($type === 'attendances') {
            return $this->exportAttendances();
        }
        
        return redirect()->back()->with('error', 'Invalid export type');
    }

    private function exportEvents()
    {
        $events = Event::with('creator')->get();
        
        $csvData = "ID,Title,Date,Time,Location,Capacity,Created By,Participants Count\n";
        
        foreach ($events as $event) {
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",%d,\"%s\",%d\n",
                $event->id,
                str_replace('"', '""', $event->title),
                $event->event_date->format('Y-m-d'),
                $event->event_time ? $event->event_time->format('H:i') : '',
                str_replace('"', '""', $event->location),
                $event->capacity ?? 0,
                str_replace('"', '""', $event->creator->name ?? ''),
                $event->participants()->count()
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="events_' . date('Y-m-d') . '.csv"');
    }

    private function exportParticipants()
    {
        $participants = Participant::with(['event', 'user'])->get();
        
        $csvData = "ID,Name,Email,Phone,Event,Status,Token,Registration Date\n";
        
        foreach ($participants as $participant) {
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $participant->id,
                str_replace('"', '""', $participant->name),
                $participant->email,
                $participant->phone ?? '',
                str_replace('"', '""', $participant->event->title),
                $participant->status,
                $participant->token,
                $participant->created_at->format('Y-m-d H:i:s')
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="participants_' . date('Y-m-d') . '.csv"');
    }

    private function exportAttendances()
    {
        $attendances = Attendance::with(['participant', 'event'])->get();
        
        $csvData = "ID,Participant Name,Event,Token Used,Attended At\n";
        
        foreach ($attendances as $attendance) {
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $attendance->id,
                str_replace('"', '""', $attendance->participant->name),
                str_replace('"', '""', $attendance->event->title),
                $attendance->token_used,
                $attendance->attended_at->format('Y-m-d H:i:s')
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="attendances_' . date('Y-m-d') . '.csv"');
    }
}
