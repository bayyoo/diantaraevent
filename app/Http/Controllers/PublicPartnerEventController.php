<?php

namespace App\Http\Controllers;

use App\Models\PartnerEvent;
use Illuminate\Http\Request;

class PublicPartnerEventController extends Controller
{
    public function show(PartnerEvent $event)
    {
        // Only allow published events to be viewed publicly
        if ($event->status !== 'published') {
            return redirect()->route('catalog.index')->with('error', 'Event tidak tersedia.');
        }

        $event->load(['partner', 'organization', 'tickets', 'reviews.user']);

        // Compute min price
        $minPrice = (int) ($event->tickets->min('price') ?? 0);

        // Reviews data
        $reviews = $event->reviews()->with('user')->latest()->get();
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return view('public.partner-event', [
            'event' => $event,
            'minPrice' => $minPrice,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}
