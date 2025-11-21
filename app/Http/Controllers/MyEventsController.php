<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\EventAttendanceSession;
use App\Models\EventCertificate;
use App\Services\CertificateService;

class MyEventsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all events the user has registered for
        $participants = Participant::with('event.sessions')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $eventIds = $participants->pluck('event_id')->unique()->values();
        
        $certificateService = new CertificateService();

        foreach ($participants as $participant) {
            if ($participant->event) {
                $certificateService->generateCertificate($participant->event, $user);
            }
        }
        
        // Count attended sessions per event for this user
        $attendanceCounts = EventAttendanceSession::whereIn('event_id', $eventIds)
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('event_id')
            ->map->count();
        
        // Fetch existing certificates for this user keyed by event_id
        $certificatesByEvent = EventCertificate::where('user_id', $user->id)
            ->whereIn('event_id', $eventIds)
            ->get()
            ->keyBy('event_id');
        
        return view('my-events.index', [
            'participants' => $participants,
            'user' => $user,
            'attendanceCounts' => $attendanceCounts,
            'certificatesByEvent' => $certificatesByEvent,
        ]);
    }
}
