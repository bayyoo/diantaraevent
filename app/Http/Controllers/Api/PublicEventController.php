<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicEventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Event::with('creator')
            ->where('event_date', '>=', now()->toDateString());

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(12);

        return response()->json([
            'success' => true,
            'message' => 'Public events retrieved successfully',
            'data' => $events
        ]);
    }

    public function show($slug): JsonResponse
    {
        $event = Event::with('creator')
            ->where('slug', $slug)
            ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event retrieved successfully',
            'data' => $event
        ]);
    }
}
