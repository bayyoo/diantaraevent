<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Participant::with('event', 'user');

        // Filter by event_id
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(10);
        $events = Event::orderBy('title')->get();

        return view('admin.participants.index', compact('participants', 'events'));
    }

    public function create()
    {
        $events = Event::orderBy('title')->get();
        return view('admin.participants.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'event_id' => 'required|exists:events,id',
            'phone' => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Generate unique token
        do {
            $token = Str::upper(Str::random(8));
        } while (Participant::where('token', $token)->exists());

        $validated['token'] = $token;

        Participant::create($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function show(Participant $participant)
    {
        $participant->load('event', 'user');
        return view('admin.participants.show', compact('participant'));
    }

    public function edit(Participant $participant)
    {
        $events = Event::orderBy('title')->get();
        return view('admin.participants.edit', compact('participant', 'events'));
    }

    public function update(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'event_id' => 'required|exists:events,id',
            'phone' => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:registered,cancelled,attended',
            'certificate_path' => 'nullable|string|max:255',
        ]);

        $participant->update($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Data peserta berhasil diperbarui!');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('admin.participants.index')
            ->with('success', 'Peserta berhasil dihapus!');
    }
}
