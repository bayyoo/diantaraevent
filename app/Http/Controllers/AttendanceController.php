<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Participant;
use App\Models\EventAttendance;
use App\Models\EventSession;
use App\Models\EventAttendanceSession;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display the admin attendance page
     */
    public function index()
    {
        // Get 50 recent attendances with participant and event info
        $recentAttendances = Attendance::with(['participant.user', 'event'])
            ->orderBy('attended_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.attendance.index', compact('recentAttendances'));
    }

    /**
     * Handle token-based check-in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        try {
            // Find participant by token
            $participant = Participant::with(['user', 'event'])
                ->where('token', $request->token)
                ->first();

            if (!$participant) {
                return back()->with('error', 'Token tidak valid');
            }

            // Check if participant already attended
            if ($participant->status === 'attended') {
                return back()->with('error', 'Peserta sudah melakukan absensi');
            }

            DB::beginTransaction();

            // Create attendance record
            Attendance::create([
                'participant_id' => $participant->id,
                'event_id' => $participant->event_id,
                'token_used' => $request->token,
                'attended_at' => now()
            ]);

            // Update participant status
            $participant->update(['status' => 'attended']);

            DB::commit();

            return back()->with('success', 
                "Absensi berhasil! Peserta: {$participant->user->name} - Event: {$participant->event->title}"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat melakukan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Show attendance form for participants (public)
     */
    public function show(Event $event)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if user is registered for this event
        $participant = Participant::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if (!$participant) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Anda belum terdaftar untuk event ini.');
        }

        // Check if user already attended
        if ($participant->status === 'attended') {
            return redirect()->route('events.show', $event)
                ->with('info', 'Anda sudah melakukan absensi untuk event ini pada ' . 
                    \Carbon\Carbon::parse($participant->updated_at)->format('d M Y H:i'));
        }

        // UKK REQUIREMENT: Attendance only available on event day after event time
        $eventDateTime = \Carbon\Carbon::parse(
            $event->event_date->format('Y-m-d') . ' ' . 
            ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00')
        );
        $now = \Carbon\Carbon::now();

        if ($now->lt($eventDateTime)) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Absensi belum dibuka. Event dimulai pada: ' . 
                    $eventDateTime->format('d M Y, H:i') . ' WIB');
        }

        return view('attendance.form', compact('event', 'participant'));
    }

    /**
     * Store attendance for participants (public)
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'token' => 'required|string|size:10'
        ]);

        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // UKK REQUIREMENT: Validate event time
        $eventDateTime = \Carbon\Carbon::parse(
            $event->event_date->format('Y-m-d') . ' ' . 
            ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00')
        );
        $now = \Carbon\Carbon::now();

        if ($now->lt($eventDateTime)) {
            return back()->with('error', 'Absensi belum dibuka. Event dimulai pada: ' . 
                $eventDateTime->format('d M Y, H:i') . ' WIB');
        }

        // Find participant by token and user
        $participant = Participant::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->where('token', $request->token)
            ->first();

        if (!$participant) {
            return back()->with('error', 'Token tidak valid atau Anda tidak terdaftar untuk event ini.');
        }

        // Check if already attended
        if ($participant->status === 'attended') {
            return back()->with('error', 'Anda sudah melakukan absensi untuk event ini.');
        }

        try {
            DB::beginTransaction();

            // Create attendance record
            Attendance::create([
                'participant_id' => $participant->id,
                'event_id' => $event->id,
                'token_used' => $request->token,
                'attended_at' => now()
            ]);

            // Update participant status
            $participant->update(['status' => 'attended']);

            // UKK REQUIREMENT: Generate certificate after attendance
            $certificateController = new \App\Http\Controllers\CertificateController();
            $certificatePath = $certificateController->generateCertificatePath($participant);
            $participant->update(['certificate_path' => $certificatePath]);

            DB::commit();

            return redirect()->route('certificate.generate', $participant)
                ->with('success', 'Absensi berhasil! Sertifikat Anda sudah tersedia.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat melakukan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Verify attendance using new token system
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'attendance_token' => 'required|string|size:10',
            'event_id' => 'required|exists:events,id'
        ]);

        try {
            // Find attendance record by token
            $attendance = EventAttendance::where('attendance_token', $request->attendance_token)
                ->where('event_id', $request->event_id)
                ->with(['user', 'event'])
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid attendance token or event'
                ], 404);
            }

            // Check if already attended
            if ($attendance->is_attended) {
                return response()->json([
                    'success' => false,
                    'message' => 'User has already been marked as attended',
                    'user' => [
                        'name' => $attendance->user->full_name,
                        'attended_at' => $attendance->attended_at
                    ]
                ]);
            }

            // Check if event is currently running
            $event = $attendance->event;
            $eventStart = \Carbon\Carbon::parse($event->event_date . ' ' . $event->event_time);
            $now = now();

            if ($now->lt($eventStart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event has not started yet. Attendance will be available on event day.'
                ], 400);
            }

            // Mark as attended
            $attendance->markAttended(auth()->user()->name ?? 'System');

            return response()->json([
                'success' => true,
                'message' => 'Attendance verified successfully',
                'user' => [
                    'name' => $attendance->user->full_name,
                    'email' => $attendance->user->email,
                    'attended_at' => $attendance->attended_at
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Attendance verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify attendance'
            ], 500);
        }
    }

    /**
     * Show attendance verification page for organizers
     */
    public function verificationPage(Event $event)
    {
        // Check if user can verify attendance for this event
        if (!auth()->user()->is_admin && $event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized to verify attendance for this event');
        }

        $totalRegistrations = EventAttendance::where('event_id', $event->id)->count();
        $totalAttended = EventAttendance::where('event_id', $event->id)
            ->where('is_attended', true)
            ->count();

        return view('admin.attendance.verification', compact('event', 'totalRegistrations', 'totalAttended'));
    }

    /**
     * Simple session check-in endpoint (auth user)
     * Params: event_id, session_id
     */
    public function sessionCheckIn(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'session_id' => 'required|exists:event_sessions,id',
        ]);

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($request->event_id);
        $session = EventSession::where('id', $request->session_id)
            ->where('event_id', $event->id)
            ->firstOrFail();

        // Ensure user registered as participant
        $participant = Participant::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();
        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar pada event ini'], 403);
        }

        // Validate time within session window
        $now = now();
        if ($session->start_at && $now->lt($session->start_at)) {
            return response()->json(['success' => false, 'message' => 'Sesi belum dimulai'], 400);
        }
        if ($session->end_at && $now->gt($session->end_at)) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir'], 400);
        }

        $attendance = EventAttendanceSession::firstOrCreate(
            [
                'event_id' => $event->id,
                'session_id' => $session->id,
                'user_id' => auth()->id(),
            ],
            [
                'checked_in_at' => now(),
            ]
        );

        if (!$attendance->wasRecentlyCreated && !$attendance->checked_in_at) {
            $attendance->update(['checked_in_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in sesi berhasil',
            'data' => [
                'session' => $session->name,
                'checked_in_at' => $attendance->checked_in_at,
            ]
        ]);
    }
}
