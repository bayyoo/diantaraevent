<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationToken;
use App\Models\Event;
use App\Models\Participant;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventRegistrationController extends Controller
{
    public function tickets(Event $event)
    {
        $user = auth()->user();

        // Check if registration is still open (before event starts)
        $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00'));
        $now = \Carbon\Carbon::now();

        if ($now->gte($eventDateTime)) {
            return redirect()->back()
                ->with('error', 'Pendaftaran sudah ditutup. Event telah dimulai.');
        }

        // Check if user already registered for this event
        $existingParticipant = \App\Models\Participant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingParticipant) {
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar di event ini.');
        }

        return view('events.tickets', compact('event', 'user'));
    }

    public function payment(Event $event)
    {
        $user = auth()->user();

        // Check if registration is still open (before event starts)
        $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00'));
        $now = \Carbon\Carbon::now();

        if ($now->gte($eventDateTime)) {
            return redirect()->back()
                ->with('error', 'Pendaftaran sudah ditutup. Event telah dimulai.');
        }

        // Check if user already registered for this event
        $existingParticipant = \App\Models\Participant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingParticipant) {
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar di event ini.');
        }

        return view('events.payment', compact('event', 'user'));
    }

    public function store(Event $event)
    {
        $user = auth()->user();

        // Check if registration is still open (before event starts)
        $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . ($event->event_time ? $event->event_time->format('H:i:s') : '00:00:00'));
        $now = \Carbon\Carbon::now();

        if ($now->gte($eventDateTime)) {
            return redirect()->back()
                ->with('error', 'Pendaftaran sudah ditutup. Event telah dimulai.');
        }

        // Check if user already registered for this event
        $existingParticipants = Participant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->get();

        // If event doesn't allow multiple registration and user already registered
        if (!$event->allow_multiple_registration && $existingParticipants->count() > 0) {
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar di event ini.');
        }
        
        // If event allows multiple registration but has limit
        if ($event->allow_multiple_registration && $event->max_registrations_per_user) {
            if ($existingParticipants->count() >= $event->max_registrations_per_user) {
                return redirect()->back()
                    ->with('error', 'Anda sudah mencapai batas maksimal pendaftaran untuk event ini (' . $event->max_registrations_per_user . ' kali).');
            }
        }

        // Generate unique 10-digit attendance token (numbers only)
        $attendanceToken = EventAttendance::generateToken();

        // Create participant record (legacy system)
        $participant = Participant::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $attendanceToken, // Use same token for backward compatibility
            'attendance_token' => $attendanceToken, // keep DB happy if column is NOT NULL
            'status' => 'registered'
        ]);

        // Create new attendance record for certificate system
        EventAttendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'attendance_token' => $attendanceToken,
            'is_attended' => false
        ]);

        // Generate E-Ticket PDF (temporarily disabled until packages installed)
        // Will be enabled after composer install completes
        try {
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $ticketController = new \App\Http\Controllers\TicketController();
                $ticketPath = $ticketController->generateTicketPath($participant);
                if ($ticketPath) {
                    $participant->update(['ticket_path' => $ticketPath]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('E-Ticket generation failed: ' . $e->getMessage());
        }

        // Send token via email
        try {
            Mail::to($user->email)->send(new EventRegistrationToken($participant, $event));
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }

        // Redirect back to event detail with success message
        return redirect()->route('events.show', $event->id)
            ->with('success', 'Pendaftaran berhasil! Token absensi telah dikirim ke email Anda.')
            ->with('token', $attendanceToken)
            ->with('participant_id', $participant->id);
    }
    
    /**
     * Show registration success page
     */
    public function success($eventId, $participantId)
    {
        $event = Event::findOrFail($eventId);
        $participant = Participant::findOrFail($participantId);
        
        // Verify participant belongs to current user
        if (auth()->check() && $participant->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('events.registration-success', compact('event', 'participant'));
    }
}
