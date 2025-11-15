<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $user = Auth::user();
        
        $wishlists = Wishlist::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        
        return view('wishlist.index', [
            'user' => $user,
            'wishlists' => $wishlists,
        ]);
    }

    /**
     * Add an event to wishlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $user = Auth::user();
        
        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('event_id', $request->event_id)
            ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Event sudah ada di wishlist',
            ], 400);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'event_id' => $request->event_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil ditambahkan ke wishlist',
        ]);
    }

    /**
     * Remove an event from wishlist.
     */
    public function destroy($eventId)
    {
        $user = Auth::user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();
        
        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan di wishlist',
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dihapus dari wishlist',
        ]);
    }

    /**
     * Check if event is in user's wishlist.
     */
    public function check($eventId)
    {
        $user = Auth::user();
        
        $inWishlist = Wishlist::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->exists();
        
        return response()->json([
            'in_wishlist' => $inWishlist,
        ]);
    }
}
