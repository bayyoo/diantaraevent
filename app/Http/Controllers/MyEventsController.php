<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;

class MyEventsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all events the user has registered for
        $participants = Participant::with('event')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('my-events.index', [
            'participants' => $participants,
            'user' => $user
        ]);
    }
}
