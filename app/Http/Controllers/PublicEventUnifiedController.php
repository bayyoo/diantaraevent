<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PartnerEvent;
use Illuminate\Http\Request;

class PublicEventUnifiedController extends Controller
{
    public function show(string $slug)
    {
        // Try PartnerEvent first (published only)
        $partner = PartnerEvent::where('slug', $slug)->first();
        if ($partner) {
            if ($partner->status !== 'published') {
                return redirect()->route('catalog.index')->with('error', 'Event tidak tersedia.');
            }
            // Load only safe relations (avoid reviews until schema is unified)
            $partner->load(['partner', 'organization', 'tickets']);

            // Compute min price
            $minPrice = (int) ($partner->tickets->min('price') ?? 0);

            // Temporary: empty reviews until migration completes
            $reviews = collect();
            $averageRating = 0;
            $totalReviews = 0;
            $ratingDistribution = [
                5 => ['count' => 0, 'percentage' => 0],
                4 => ['count' => 0, 'percentage' => 0],
                3 => ['count' => 0, 'percentage' => 0],
                2 => ['count' => 0, 'percentage' => 0],
                1 => ['count' => 0, 'percentage' => 0],
            ];

            return view('public.partner-event', [
                'event' => $partner,
                'minPrice' => $minPrice,
                'reviews' => $reviews,
                'averageRating' => $averageRating,
                'totalReviews' => $totalReviews,
                'ratingDistribution' => $ratingDistribution,
            ]);
        }

        // Fallback to admin Event by slug
        $event = Event::where('slug', $slug)->first();
        if ($event) {
            // Assume published/approved check inside existing EventController@show logic; basic guard here
            if (!in_array($event->status, ['approved', 'published'])) {
                return redirect()->route('catalog.index')->with('error', 'Event tidak tersedia.');
            }
            // Reuse existing show view by redirecting to id route to avoid duplicating logic
            return redirect()->route('events.show', $event);
        }

        return redirect()->route('catalog.index')->with('error', 'Event tidak ditemukan.');
    }
}
