<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventApproved;
use App\Mail\EventRejected;

class EventApprovalController extends Controller
{
    /**
     * Display pending events for approval
     */
    public function index()
    {
        $pendingEvents = Event::with(['creator'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.events.pending', compact('pendingEvents'));
    }

    /**
     * Approve an event
     */
    public function approve(Event $event)
    {
        // Check if event is pending
        if (!$event->isPending()) {
            return back()->with('error', 'Event ini tidak dalam status pending.');
        }

        // Update event status
        $event->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        // Send email notification to event creator
        try {
            $html = view('emails.event-approved', [
                'event' => $event,
            ])->render();

            app(\App\Services\BrevoEmailService::class)->sendEmail(
                $event->creator->email,
                $event->creator->name ?? $event->creator->email,
                'Event Anda Telah Disetujui - ' . $event->title,
                $html
            );
        } catch (\Exception $e) {
            // Log error but don't fail the approval
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return back()->with('success', "Event '{$event->title}' berhasil disetujui dan dipublikasikan.");
    }

    /**
     * Reject an event
     */
    public function reject(Request $request, Event $event)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Check if event is pending
        if (!$event->isPending()) {
            return back()->with('error', 'Event ini tidak dalam status pending.');
        }

        // Update event status
        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        // Send email notification to event creator
        try {
            $html = view('emails.event-rejected', [
                'event' => $event,
            ])->render();

            app(\App\Services\BrevoEmailService::class)->sendEmail(
                $event->creator->email,
                $event->creator->name ?? $event->creator->email,
                'Event Anda Ditolak - ' . $event->title,
                $html
            );
        } catch (\Exception $e) {
            // Log error but don't fail the rejection
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return back()->with('success', "Event '{$event->title}' telah ditolak.");
    }
}
