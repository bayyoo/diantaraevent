<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserEventController extends Controller
{
    /**
     * Display user's events dashboard
     */
    public function index()
    {
        $events = Event::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        return view('user.events.create');
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:' . now()->addDays(3)->format('Y-m-d'),
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'event_date.after_or_equal' => 'Event harus dibuat minimal H-3 sebelum tanggal pelaksanaan.',
        ]);

        // Handle flyer upload
        $flyerPath = null;
        if ($request->hasFile('flyer')) {
            $flyerPath = $request->file('flyer')->store('flyers', 'public');
        }

        // Create event with pending status
        $event = Event::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'] ?? null,
            'price' => $validated['price'] ?? 0,
            'flyer_path' => $flyerPath,
            'created_by' => Auth::id(),
            'status' => 'pending', // Pending approval
        ]);

        return redirect()->route('user.events.index')
            ->with('success', 'Event berhasil dibuat dan menunggu persetujuan admin.');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        // Check if user owns this event
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        // Check if user owns this event
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only edit if pending or rejected
        if (!in_array($event->status, ['pending', 'rejected', 'draft'])) {
            return redirect()->route('user.events.index')
                ->with('error', 'Event yang sudah disetujui tidak dapat diedit.');
        }

        return view('user.events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        // Check if user owns this event
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only edit if pending or rejected
        if (!in_array($event->status, ['pending', 'rejected', 'draft'])) {
            return redirect()->route('user.events.index')
                ->with('error', 'Event yang sudah disetujui tidak dapat diedit.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:' . now()->addDays(3)->format('Y-m-d'),
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'event_date.after_or_equal' => 'Event harus dibuat minimal H-3 sebelum tanggal pelaksanaan.',
        ]);

        // Handle flyer upload
        if ($request->hasFile('flyer')) {
            // Delete old flyer
            if ($event->flyer_path) {
                Storage::disk('public')->delete($event->flyer_path);
            }
            $validated['flyer_path'] = $request->file('flyer')->store('flyers', 'public');
        }

        // Update event and reset to pending if it was rejected
        $event->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'] ?? null,
            'price' => $validated['price'] ?? 0,
            'flyer_path' => $validated['flyer_path'] ?? $event->flyer_path,
            'status' => 'pending', // Reset to pending for re-approval
            'rejection_reason' => null, // Clear rejection reason
        ]);

        return redirect()->route('user.events.index')
            ->with('success', 'Event berhasil diperbarui dan menunggu persetujuan admin.');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        // Check if user owns this event
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only delete if not approved
        if ($event->isApproved()) {
            return redirect()->route('user.events.index')
                ->with('error', 'Event yang sudah disetujui tidak dapat dihapus.');
        }

        // Delete flyer
        if ($event->flyer_path) {
            Storage::disk('public')->delete($event->flyer_path);
        }

        $event->delete();

        return redirect()->route('user.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
