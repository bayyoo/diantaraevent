<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\PartnerEvent;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Check existing review (polymorphic)
        $existingReview = Review::where('user_id', auth()->id())
            ->where('reviewable_id', $event->id)
            ->where('reviewable_type', Event::class)
            ->first();

        if ($existingReview) {
            return redirect()
                ->route('events.show', $event)
                ->with('error', 'You have already reviewed this event.');
        }

        // Create review (polymorphic)
        Review::create([
            'user_id' => auth()->id(),
            'reviewable_id' => $event->id,
            'reviewable_type' => Event::class,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        
        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Thank you for your review! Your feedback has been submitted.');
    }
    
    /**
     * Update existing review
     */
    public function update(Request $request, Event $event, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        
        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        
        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Review updated successfully!');
    }
    
    /**
     * Delete review
     */
    public function destroy(Event $event, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $review->delete();
        
        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * PartnerEvent review endpoints
     */
    public function storePartner(Request $request, PartnerEvent $event)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $existingReview = Review::where('user_id', auth()->id())
            ->where('reviewable_id', $event->id)
            ->where('reviewable_type', PartnerEvent::class)
            ->first();

        if ($existingReview) {
            return redirect()->route('public.partner-events.show', $event)
                ->with('error', 'You have already reviewed this event.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'reviewable_id' => $event->id,
            'reviewable_type' => PartnerEvent::class,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('public.partner-events.show', $event)
            ->with('success', 'Thank you for your review! Your feedback has been submitted.');
    }

    public function updatePartner(Request $request, PartnerEvent $event, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('public.partner-events.show', $event)
            ->with('success', 'Review updated successfully!');
    }

    public function destroyPartner(PartnerEvent $event, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $review->delete();
        return redirect()->route('public.partner-events.show', $event)
            ->with('success', 'Review deleted successfully.');
    }
}
