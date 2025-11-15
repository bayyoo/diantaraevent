<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PartnerEvent;
use App\Models\PartnerTicket;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('creator');
        
        // Check if current user is already registered
        $isRegistered = false;
        if (auth()->check()) {
            $isRegistered = $event->participants()
                ->where('user_id', auth()->id())
                ->exists();
        }
        
        // Load reviews with user relationship
        $reviews = $event->reviews()->with('user')->latest()->get();
        
        // Calculate average rating
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();
        
        // Rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }
        
        return view('events.show', compact('event', 'isRegistered', 'reviews', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Show the ticket selection page.
     */
    public function tickets(Event $event)
    {
        $event->load('creator');
        $tickets = collect();

        // If this unified event originated from a partner event, map its tickets
        $partnerEvent = PartnerEvent::where('slug', $event->slug)->first();
        if ($partnerEvent) {
            $tickets = PartnerTicket::where('event_id', $partnerEvent->id)
                ->where(function ($q) {
                    // if column is_active exists, use it; otherwise ignore
                    try { $q->where('is_active', true); } catch (\Throwable $e) {}
                })
                ->orderBy('price')
                ->get();
        }

        return view('events.tickets', compact('event', 'tickets'));
    }

    /**
     * Show the payment page.
     */
    public function payment(Event $event)
    {
        $event->load('creator');
        return view('events.payment', compact('event'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
