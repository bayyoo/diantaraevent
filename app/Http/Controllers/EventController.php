<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PartnerEvent;
use App\Models\PartnerTicket;
use App\Models\EventAttendance;
use App\Models\EventCertificate;
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

        // Hanya tampilkan ke publik jika event sudah disetujui/dipublikasikan
        if (!in_array($event->status, ['approved', 'published'])) {
            $user = auth()->user();
            $isAdmin = $user && property_exists($user, 'is_admin') && $user->is_admin;
            $isCreator = $user && $event->created_by === $user->id;

            if (!$isAdmin && !$isCreator) {
                return redirect()->route('catalog.index')->with('error', 'Event tidak tersedia.');
            }
        }
        
        // Check if current user is already registered
        $isRegistered = false;
        $hasAttended = false;
        $userCertificate = null;
        if (auth()->check()) {
            $userId = auth()->id();

            $isRegistered = $event->participants()
                ->where('user_id', $userId)
                ->exists();

            // Cek apakah user sudah tercatat hadir di sistem absensi baru
            $attendance = EventAttendance::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->where('is_attended', true)
                ->first();
            $hasAttended = (bool) $attendance;

            // Ambil sertifikat jika sudah pernah dibuat
            $userCertificate = EventCertificate::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->first();
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
        
        return view('events.show', compact('event', 'isRegistered', 'reviews', 'averageRating', 'totalReviews', 'ratingDistribution', 'hasAttended', 'userCertificate'));
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

        // Guard akses tiket untuk event yang belum disetujui
        if (!in_array($event->status, ['approved', 'published'])) {
            $user = auth()->user();
            $isAdmin = $user && property_exists($user, 'is_admin') && $user->is_admin;
            $isCreator = $user && $event->created_by === $user->id;

            if (!$isAdmin && !$isCreator) {
                return redirect()->route('catalog.index')->with('error', 'Event tidak tersedia.');
            }
        }
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

        // Guard akses halaman pembayaran
        if (!in_array($event->status, ['approved', 'published'])) {
            $user = auth()->user();
            $isAdmin = $user && property_exists($user, 'is_admin') && $user->is_admin;
            $isCreator = $user && $event->created_by === $user->id;

            if (!$isAdmin && !$isCreator) {
                return redirect()->route('catalog.index')->with('error', 'Event tidak tersedia.');
            }
        }

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
