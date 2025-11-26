<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Event::with('creator');

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

        return response()->json([
            'success' => true,
            'message' => 'Events retrieved successfully',
            'data' => $events
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Handle file upload (store under public/images/flyers)
        if ($request->hasFile('flyer')) {
            $flyerFile = $request->file('flyer');
            $flyerName = 'flyer_'.time().'_'.Str::random(8).'.'.$flyerFile->getClientOriginalExtension();
            $flyerFile->move(public_path('images/flyers'), $flyerName);
            $validated['flyer_path'] = 'images/flyers/'.$flyerName;
        }

        // Add created_by
        $validated['created_by'] = auth()->id();

        $event = Event::create($validated);
        $event->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    public function show(Event $event): JsonResponse
    {
        $event->load('creator');
        
        return response()->json([
            'success' => true,
            'message' => 'Event retrieved successfully',
            'data' => $event
        ]);
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                $oldPath = public_path($event->flyer_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $flyerFile = $request->file('flyer');
            $flyerName = 'flyer_'.time().'_'.Str::random(8).'.'.$flyerFile->getClientOriginalExtension();
            $flyerFile->move(public_path('images/flyers'), $flyerName);
            $validated['flyer_path'] = 'images/flyers/'.$flyerName;
        }

        $event->update($validated);
        $event->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    public function destroy(Event $event): JsonResponse
    {
        // Delete flyer file if exists
        if ($event->flyer_path) {
            $oldPath = public_path($event->flyer_path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}
