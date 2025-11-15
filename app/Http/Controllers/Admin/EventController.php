<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Show only published events (pending events are in separate page)
        $query = Event::with('creator')->published();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('event_date', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after_or_equal:' . now()->addDays(3)->format('Y-m-d'),
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'event_date.after_or_equal' => 'Event hanya dapat dibuat minimal H-3 (3 hari) sebelum tanggal pelaksanaan.'
        ]);

        // Generate slug from title
        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Event::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle file upload
        if ($request->hasFile('flyer')) {
            $flyerPath = $request->file('flyer')->store('flyers', 'public');
            $validated['flyer_path'] = $flyerPath;
        }

        // Add created_by and auto-publish (admin events are trusted)
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'published';
        $validated['approved_by'] = auth()->id();
        $validated['approved_at'] = now();

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    public function show(Event $event)
    {
        $event->load('creator');
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after_or_equal:' . now()->addDays(3)->format('Y-m-d'),
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'event_date.after_or_equal' => 'Event hanya dapat diubah minimal H-3 (3 hari) sebelum tanggal pelaksanaan.'
        ]);

        // Generate slug from title if title changed
        if ($validated['title'] !== $event->title) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure slug is unique (excluding current event)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Event::where('slug', $validated['slug'])->where('id', '!=', $event->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle file upload
        if ($request->hasFile('flyer')) {
            // Delete old flyer if exists
            if ($event->flyer_path) {
                Storage::disk('public')->delete($event->flyer_path);
            }
            
            $flyerPath = $request->file('flyer')->store('flyers', 'public');
            $validated['flyer_path'] = $flyerPath;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        // Delete flyer file if exists
        if ($event->flyer_path) {
            Storage::disk('public')->delete($event->flyer_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}
