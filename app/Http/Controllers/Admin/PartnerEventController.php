<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerEvent;
use Illuminate\Http\Request;

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
        $event->update([
            'status' => 'published',
            'approved_at' => now()
        ]);

        return back()->with('success', 'Partner event has been approved and published.');
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
}
