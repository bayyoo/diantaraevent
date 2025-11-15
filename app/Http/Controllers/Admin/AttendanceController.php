<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display the admin attendance page with last 50 attendances
     */
    public function index()
    {
        $attendances = Attendance::with(['participant.user', 'event'])
            ->orderBy('attended_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.attendance.index', compact('attendances'));
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
                return back()->with('error', 'Token tidak valid.');
            }

            // Check if participant already attended
            if ($participant->status === 'attended') {
                return back()->with('error', 'Peserta sudah melakukan absensi.');
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

            return back()->with('success', "Absensi berhasil: {$participant->user->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat melakukan absensi: ' . $e->getMessage());
        }
    }
}
