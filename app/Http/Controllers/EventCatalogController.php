<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventCatalogController extends Controller
{
    public function index(Request $request)
    {
        // Only show published/approved events
        $query = Event::with('creator')->published();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->has('city') && $request->city) {
            $query->where('location', 'like', "%{$request->city}%");
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('title', 'like', "%{$request->category}%");
        }

        // Filter by date
        if ($request->has('date_filter') && $request->date_filter) {
            $dateFilter = $request->date_filter;
            
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('event_date', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('event_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('event_date', Carbon::now()->month)
                          ->whereYear('event_date', Carbon::now()->year);
                    break;
                case 'upcoming':
                    $query->where('event_date', '>', Carbon::now());
                    break;
                case 'past':
                    $query->where('event_date', '<', Carbon::now());
                    break;
            }
        } else {
            // Default: show upcoming events first
            $query->where('event_date', '>=', Carbon::today());
        }

        // Sort by date (closest first by default)
        $sortBy = $request->get('sort', 'date_asc');
        
        switch ($sortBy) {
            case 'date_desc':
                $query->orderBy('event_date', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default: // date_asc
                $query->orderBy('event_date', 'asc');
                break;
        }

        $events = $query->paginate(12)->withQueryString();

        // Check if user is authenticated
        $isAuthenticated = auth()->check();
        $user = auth()->user();

        // Get user's wishlist event IDs if authenticated
        $wishlistEventIds = [];
        if ($isAuthenticated) {
            $wishlistEventIds = Wishlist::where('user_id', $user->id)
                ->pluck('event_id')
                ->toArray();
        }

        return view('catalog.index', compact('events', 'isAuthenticated', 'user', 'wishlistEventIds'));
    }
}
