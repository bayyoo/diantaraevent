<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerEvent;
use App\Models\PartnerTicket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PartnerEventController extends Controller
{
    public function __construct()
    {
        // Middleware handled in routes
    }

    /**
     * Display a listing of partner events.
     */
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        
        $events = $partner->events()
            ->with(['tickets'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('partner.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event - Step 1: Basic Info
     */
    public function create()
    {
        return view('partner.events.create.step1');
    }

    /**
     * Store basic event information - Step 1
     */
    public function storeStep1(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'address' => 'required|string',
            'has_certificate' => 'nullable|boolean',
            'certificate_template' => 'nullable|in:template_a,template_b,custom',
        ]);

        $partner = Auth::guard('partner')->user();
        $organization = $partner->organizations()->first();

        if (!$organization) {
            return back()->withErrors(['organization' => 'You need to have an organization to create events.']);
        }

        $event = PartnerEvent::create([
            'partner_id' => $partner->id,
            'organization_id' => $organization->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'location_details' => json_encode([
                'address' => $request->address,
                'coordinates' => null,
                'venue_info' => $request->venue_info ?? null,
            ]),
            'status' => 'draft',
            'metadata' => json_encode([
                'certificate' => [
                    'has_certificate' => (bool)$request->boolean('has_certificate'),
                    'certificate_template' => $request->certificate_template ?? 'template_a',
                ],
            ]),
        ]);

        // Mirror to main events table (unified public event)
        try {
            $adminId = DB::table('users')->where('is_admin', 1)->value('id') ?? DB::table('users')->min('id');
            if ($adminId) {
                Event::updateOrCreate(
                    ['slug' => $event->slug],
                    [
                        'title' => $event->title,
                        'description' => $event->description,
                        'event_date' => $event->start_date,
                        'event_time' => null,
                        'location' => $event->location,
                        'capacity' => null,
                        'flyer_path' => $event->poster,
                        'created_by' => $adminId,
                        'partner_id' => $partner->id,
                        'organization_id' => $organization->id,
                        'source' => 'partner',
                        'poster' => $event->poster,
                        'banners' => $event->banners,
                        'price' => 0,
                        'has_certificate' => (bool)$request->boolean('has_certificate'),
                        'certificate_template' => $request->certificate_template ?? 'template_a',
                    ]
                );
            }
        } catch (\Throwable $e) {
            \Log::warning('Mirror partner event step1 failed: '.$e->getMessage());
        }

        return redirect()->route('diantaranexus.events.create.step2', $event->id)
            ->with('success', 'Event basic information saved! Now let\'s set up your tickets.');
    }

    /**
     * Show ticket creation form - Step 2
     */
    public function createStep2($eventId)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($eventId);
        
        if ($event->status !== 'draft') {
            return redirect()->route('diantaranexus.events.index')
                ->with('error', 'This event cannot be edited.');
        }

        $tickets = $event->tickets;

        return view('partner.events.create.step2', compact('event', 'tickets'));
    }

    /**
     * Store ticket information - Step 2
     */
    public function storeStep2(Request $request, $eventId)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($eventId);

        $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.quantity' => 'required|integer|min:1',
            'tickets.*.sale_start' => 'required|date',
            'tickets.*.sale_end' => 'required|date|after:tickets.*.sale_start',
            'tickets.*.description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $event) {
            // Delete existing tickets
            $event->tickets()->delete();

            // Create new tickets
            foreach ($request->tickets as $ticketData) {
                PartnerTicket::create([
                    'event_id' => $event->id,
                    'name' => $ticketData['name'],
                    'description' => $ticketData['description'] ?? null,
                    'price' => $ticketData['price'],
                    'quantity' => $ticketData['quantity'],
                    'sale_start' => $ticketData['sale_start'],
                    'sale_end' => $ticketData['sale_end'],
                    'benefits' => json_encode($ticketData['benefits'] ?? []),
                    'is_active' => true,
                ]);
            }
        });

        // Update mirrored Event price (min ticket price)
        try {
            $minPrice = PartnerTicket::where('event_id', $event->id)->min('price');
            if (!is_null($minPrice)) {
                Event::where('slug', $event->slug)->update(['price' => $minPrice]);
            }
        } catch (\Throwable $e) {
            \Log::warning('Mirror partner tickets to price failed: '.$e->getMessage());
        }

        return redirect()->route('diantaranexus.events.create.step3', $event->id)
            ->with('success', 'Tickets configured successfully! Now let\'s add media and finalize.');
    }

    /**
     * Show media upload and finalization form - Step 3
     */
    public function createStep3($eventId)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->with('tickets')->findOrFail($eventId);
        
        if ($event->status !== 'draft') {
            return redirect()->route('diantaranexus.events.index')
                ->with('error', 'This event cannot be edited.');
        }

        return view('partner.events.create.step3', compact('event'));
    }

    /**
     * Finalize event creation - Step 3
     */
    public function storeStep3(Request $request, $eventId)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($eventId);

        $request->validate([
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'banners.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'terms_conditions' => 'required|string',
            'submit_for_review' => 'nullable|boolean',
            'custom_certificate' => 'nullable|file|mimes:pdf,png,jpg,jpeg',
        ]);

        $updateData = [
            'terms_conditions' => $request->terms_conditions,
        ];

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('events/posters', 'public');
            $updateData['poster'] = $posterPath;
        }

        // Handle banner uploads
        if ($request->hasFile('banners')) {
            $bannerPaths = [];
            foreach ($request->file('banners') as $banner) {
                $bannerPaths[] = $banner->store('events/banners', 'public');
            }
            $updateData['banners'] = json_encode($bannerPaths);
        }

        // Handle custom certificate upload (for custom template)
        if ($request->hasFile('custom_certificate')) {
            $certPath = $request->file('custom_certificate')->store('certificates/templates', 'public');
            try {
                Event::where('slug', $event->slug)->update(['custom_certificate_path' => $certPath]);
            } catch (\Throwable $e) {
                \Log::warning('Mirror custom certificate failed: '.$e->getMessage());
            }
        }

        // Update status if submitting for review
        if ($request->submit_for_review) {
            $updateData['status'] = 'pending_review';
        }

        $event->update($updateData);

        // Mirror media updates to main events table
        try {
            $mirror = [
                'poster' => $event->poster,
            ];
            if (!empty($updateData['poster'])) {
                $mirror['flyer_path'] = $updateData['poster'];
                $mirror['poster'] = $updateData['poster'];
            }
            if (!empty($updateData['banners'])) {
                $mirror['banners'] = $updateData['banners'];
            }
            if (!empty($mirror)) {
                Event::where('slug', $event->slug)->update($mirror);
            }
        } catch (\Throwable $e) {
            \Log::warning('Mirror partner media failed: '.$e->getMessage());
        }

        $message = $request->submit_for_review 
            ? 'Event submitted for review! You will be notified once it\'s approved.'
            : 'Event saved as draft. You can submit it for review anytime.';

        return redirect()->route('diantaranexus.events.index')->with('success', $message);
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->with(['tickets', 'organization'])->findOrFail($id);

        return view('partner.events.show', compact('event'));
    }

    /**
     * Submit event for review
     */
    public function submitForReview($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($id);

        if ($event->status !== 'draft') {
            return back()->with('error', 'Only draft events can be submitted for review.');
        }

        $event->update(['status' => 'pending_review']);

        return back()->with('success', 'Event submitted for review successfully!');
    }
}
