<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PartnerEvent;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is authenticated
        $isAuthenticated = auth()->check();
        $user = auth()->user();

        // Get admin events (published/approved)
        $adminEventsQuery = Event::with('creator')
            ->published();
        
        // Get partner events (published)
        $partnerEventsQuery = PartnerEvent::with(['partner', 'organization', 'tickets'])
            ->where('status', 'published');
            
        // Time filter - default show all, but can filter by upcoming/past
        $timeFilter = $request->get('time', 'all'); // all, upcoming, past
        if ($timeFilter === 'upcoming') {
            $adminEventsQuery->where('event_date', '>=', Carbon::today());
            $partnerEventsQuery->where('start_date', '>=', Carbon::today());
        } elseif ($timeFilter === 'past') {
            $adminEventsQuery->where('event_date', '<', Carbon::today());
            $partnerEventsQuery->where('start_date', '<', Carbon::today());
        }

        // Search functionality for admin events
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $adminEventsQuery->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
            
            // Search functionality for partner events
            $partnerEventsQuery->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        // Date range filter for admin events
        if ($request->filled('date_from')) {
            $adminEventsQuery->where('event_date', '>=', Carbon::parse($request->date_from));
            $partnerEventsQuery->where('start_date', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->filled('date_to')) {
            $adminEventsQuery->where('event_date', '<=', Carbon::parse($request->date_to));
            $partnerEventsQuery->where('start_date', '<=', Carbon::parse($request->date_to));
        }

        // Get admin events
        $adminEvents = $adminEventsQuery->get()->map(function($event) {
            return (object) [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'location' => $event->location,
                'capacity' => $event->capacity,
                'price' => $event->price ?? 0,
                'flyer_path' => $event->flyer_path,
                'creator_name' => $event->creator->name ?? 'Admin',
                'creator_type' => 'admin',
                'slug' => $event->slug,
                'created_at' => $event->created_at,
                'link_url' => route('public.events.show', $event->slug),
            ];
        });
        
        // Get partner events
        $partnerEvents = $partnerEventsQuery->get()->map(function($event) {
            // Determine minimum ticket price (0 if no tickets)
            $minPrice = 0;
            if ($event->relationLoaded('tickets')) {
                $min = $event->tickets->min('price');
                $minPrice = $min !== null ? (int) $min : 0;
            } else {
                $minPrice = (int) ($event->tickets()->min('price') ?? 0);
            }
            return (object) [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->start_date->format('Y-m-d'),
                'event_time' => $event->start_date->format('H:i:s'),
                'location' => $event->location,
                'capacity' => 1000, // Default capacity for partner events
                'price' => $minPrice,
                'flyer_path' => $event->poster,
                'creator_name' => $event->organization->name ?? $event->partner->name ?? 'Partner',
                'creator_type' => 'partner',
                'slug' => $event->slug,
                'created_at' => $event->created_at,
                // Unified public detail page using slug for both admin & partner events
                'link_url' => route('public.events.show', $event->slug),
            ];
        });
        
        // Combine and sort all events
        $allEvents = $adminEvents->concat($partnerEvents);
        
        // Sorting - default to show upcoming events first, then past events
        $sort = $request->get('sort', 'date_mixed');
        switch ($sort) {
            case 'name_asc':
                $allEvents = $allEvents->sortBy('title');
                break;
            case 'name_desc':
                $allEvents = $allEvents->sortByDesc('title');
                break;
            case 'date_desc':
                $allEvents = $allEvents->sortByDesc('event_date');
                break;
            case 'date_asc':
                $allEvents = $allEvents->sortBy('event_date');
                break;
            default: // date_mixed - upcoming first, then past
                $today = Carbon::today()->format('Y-m-d');
                $upcomingEvents = $allEvents->filter(function($event) use ($today) {
                    return $event->event_date >= $today;
                })->sortBy('event_date');
                $pastEvents = $allEvents->filter(function($event) use ($today) {
                    return $event->event_date < $today;
                })->sortByDesc('event_date');
                $allEvents = $upcomingEvents->concat($pastEvents);
                break;
        }
        
        // Paginate manually - show more events on homepage
        $perPage = 50; // Increased to show more events
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $events = $allEvents->slice($offset, $perPage)->values();
        
        // Create pagination object
        $total = $allEvents->count();
        $events = new \Illuminate\Pagination\LengthAwarePaginator(
            $events,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get featured events for carousel (latest 3 events from both admin and partner)
        $featuredAdminEvents = Event::with('creator')
            ->published()
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
            
        $featuredPartnerEvents = PartnerEvent::with(['partner', 'organization'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get();
            
        $featuredEvents = $featuredAdminEvents->concat($featuredPartnerEvents)->take(3);

        // Get event categories/types for category section (from both admin and partner events)
        $adminWorkshopCount = Event::published()->where('title', 'like', '%workshop%')->count();
        $partnerWorkshopCount = PartnerEvent::where('status', 'published')->where('title', 'like', '%workshop%')->count();
        
        $adminSeminarCount = Event::published()->where('title', 'like', '%seminar%')->count();
        $partnerSeminarCount = PartnerEvent::where('status', 'published')->where('title', 'like', '%seminar%')->count();
        
        $adminCompetitionCount = Event::published()->where(function($q) {
            $q->where('title', 'like', '%competition%')->orWhere('title', 'like', '%championship%');
        })->count();
        $partnerCompetitionCount = PartnerEvent::where('status', 'published')->where(function($q) {
            $q->where('title', 'like', '%competition%')->orWhere('title', 'like', '%championship%');
        })->count();
        
        $adminConcertCount = Event::published()->where(function($q) {
            $q->where('title', 'like', '%concert%')->orWhere('title', 'like', '%konser%')
              ->orWhere('title', 'like', '%music%')->orWhere('title', 'like', '%festival%')
              ->orWhere('title', 'like', '%fest%');
        })->count();
        $partnerConcertCount = PartnerEvent::where('status', 'published')->where(function($q) {
            $q->where('title', 'like', '%concert%')->orWhere('title', 'like', '%konser%')
              ->orWhere('title', 'like', '%music%')->orWhere('title', 'like', '%festival%')
              ->orWhere('title', 'like', '%fest%');
        })->count();
        
        $eventCategories = [
            [
                'name' => 'Workshop',
                'count' => $adminWorkshopCount + $partnerWorkshopCount,
                'image' => 'https://images.unsplash.com/photo-1517180102446-f3ece451e9d8?w=400&h=300&fit=crop',
                'description' => 'Hands-on learning experiences'
            ],
            [
                'name' => 'Seminar',
                'count' => $adminSeminarCount + $partnerSeminarCount,
                'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&h=300&fit=crop',
                'description' => 'Knowledge sharing sessions'
            ],
            [
                'name' => 'Competition',
                'count' => $adminCompetitionCount + $partnerCompetitionCount,
                'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=400&h=300&fit=crop',
                'description' => 'Competitive events and contests'
            ],
            [
                'name' => 'Concert',
                'count' => $adminConcertCount + $partnerConcertCount,
                'image' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=400&h=300&fit=crop',
                'description' => 'Live music and festival events'
            ]
        ];

        // Get user's wishlist event IDs if authenticated
        $wishlistEventIds = [];
        if ($isAuthenticated) {
            $wishlistEventIds = Wishlist::where('user_id', $user->id)
                ->pluck('event_id')
                ->toArray();
        }

        return view('welcome', compact('events', 'featuredEvents', 'eventCategories', 'isAuthenticated', 'user', 'wishlistEventIds'));
    }
}
