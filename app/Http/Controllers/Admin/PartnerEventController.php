<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerEvent;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerEventController extends Controller
{
    /**
     * Display a listing of partner events.
     */
    public function index()
    {
        $events = PartnerEvent::with(['partner', 'organization'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.partner-events.index', compact('events'));
    }

    /**
     * Display the specified partner event.
     */
    public function show(PartnerEvent $event)
    {
        $event->load(['partner', 'organization', 'tickets']);
        
        return view('admin.partner-events.show', compact('event'));
    }

    /**
     * Approve a partner event.
     */
    public function approve(PartnerEvent $event)
    {
        // Update status di tabel partner_events
        $event->update([
            'status' => 'published',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Sinkronkan ke tabel events (mirror publik)
        try {
            Event::updateOrCreate(
                ['slug' => $event->slug],
                [
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->start_date,
                    'event_time' => null,
                    'location' => $event->location,
                    'flyer_path' => $event->poster,
                    'status' => 'approved', // masuk scope published()
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]
            );
        } catch (\Throwable $e) {
            \Log::warning('Sync approved PartnerEvent to events table failed: '.$e->getMessage(), [
                'partner_event_id' => $event->id,
                'slug' => $event->slug,
            ]);
        }

        return back()->with('success', 'Partner event has been approved and published to public catalog.');
    }

    /**
     * Reject a partner event.
     */
    public function reject(PartnerEvent $event)
    {
        $event->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        return back()->with('success', 'Partner event has been rejected.');
    }

    /**
     * Withdraw a published partner event from public catalog.
     */
    public function withdraw(PartnerEvent $event)
    {
        // Tandai event partner sebagai cancelled
        $event->update([
            'status' => 'cancelled',
        ]);

        // Tarik kembali dari tabel events (supaya tidak muncul di publik)
        try {
            Event::where('slug', $event->slug)->update([
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Withdraw PartnerEvent from events table failed: '.$e->getMessage(), [
                'partner_event_id' => $event->id,
                'slug' => $event->slug,
            ]);
        }

        return back()->with('success', 'Partner event has been withdrawn from public catalog.');
    }
}
