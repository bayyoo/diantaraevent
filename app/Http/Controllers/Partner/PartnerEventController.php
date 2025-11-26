<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerEvent;
use App\Models\PartnerTicket;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventAttendance;
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
    public function index(Request $request)
    {
        $partner = Auth::guard('partner')->user();

        $statusFilter = $request->query('status', 'semua');

        $query = $partner->events()
            ->with(['tickets'])
            ->orderBy('created_at', 'desc');

        if ($statusFilter === 'draft') {
            $query->where('status', 'draft');
        } elseif ($statusFilter === 'tayang') {
            $query->where('status', 'published');
        } elseif ($statusFilter === 'berakhir') {
            $query->where('end_date', '<', now());
        }

        $events = $query->paginate(10);

        return view('partner.events.index', [
            'events' => $events,
            'statusFilter' => $statusFilter,
        ]);
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
            'sessions' => 'nullable|array',
            'sessions.*.name' => 'nullable|string|max:100',
            'sessions.*.start_at' => 'nullable|date',
            'sessions.*.end_at' => 'nullable|date|after_or_equal:sessions.*.start_at',
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
                    'has_certificate' => false,
                    'certificate_template' => 'template_a',
                ],
            ]),
        ]);

        // Mirror to main events table (unified public event)
        try {
            $adminId = DB::table('users')->where('is_admin', 1)->value('id') ?? DB::table('users')->min('id');
            if ($adminId) {
                $mirrored = Event::updateOrCreate(
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
                        'has_certificate' => false,
                        'certificate_template' => 'template_a',
                        // Semua event dari partner masuk antrian approval dulu
                        'status' => 'pending',
                    ]
                );

                // Create sessions if provided
                if ($mirrored && is_array($request->sessions)) {
                    foreach ($request->sessions as $idx => $s) {
                        if (!empty($s['name']) && !empty($s['start_at'])) {
                            EventSession::create([
                                'event_id' => $mirrored->id,
                                'name' => $s['name'],
                                'start_at' => $s['start_at'],
                                'end_at' => $s['end_at'] ?? null,
                                'order_index' => $idx + 1,
                            ]);
                        }
                    }
                }
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
        $event = $partner->events()->with(['tickets', 'organization'])->findOrFail($eventId);
        
        // Hanya event dengan status draft atau pending_review yang bisa diedit media-nya langsung
        if (!in_array($event->status, ['draft', 'pending_review'])) {
            return redirect()->route('diantaranexus.events.index')
                ->with('error', 'Hanya event dengan status draft atau dalam review yang dapat diedit.');
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

        // Simpan status lama untuk logika approval setelah update
        $previousStatus = $event->status;

        $request->validate([
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'banners.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'terms_conditions' => 'required|string',
            'submit_for_review' => 'nullable|boolean',
            'custom_certificate' => 'nullable|file|mimes:pdf,png,jpg,jpeg',
            'has_certificate' => 'nullable|boolean',
            'certificate_template' => 'nullable|in:template_a,template_b,custom',
            'cert_org_name' => 'nullable|string|max:255',
            'cert_presented_text' => 'nullable|string|max:255',
            'cert_body' => 'nullable|string',
            'cert_date_label' => 'nullable|string|max:50',
            'cert_signature_label' => 'nullable|string|max:50',
            'certificate_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $updateData = [
            'terms_conditions' => $request->terms_conditions,
        ];

        // Update certificate metadata on PartnerEvent (normalize to array)
        $rawMetadata = $event->metadata ?? [];
        if (is_string($rawMetadata)) {
            $decoded = json_decode($rawMetadata, true);
            $metadata = is_array($decoded) ? $decoded : [];
        } else {
            $metadata = is_array($rawMetadata) ? $rawMetadata : [];
        }

        $certificateMeta = $metadata['certificate'] ?? [];
        $certificateMeta['has_certificate'] = (bool)$request->boolean('has_certificate');
        $certificateMeta['certificate_template'] = $request->certificate_template ?? 'template_a';
        $certificateMeta['text'] = [
            'org_name' => $request->input('cert_org_name'),
            'presented_text' => $request->input('cert_presented_text'),
            'body' => $request->input('cert_body'),
            'date_label' => $request->input('cert_date_label'),
            'signature_label' => $request->input('cert_signature_label'),
        ];

        // Handle custom certificate logo upload (stored in public storage)
        if ($request->hasFile('certificate_logo')) {
            $logoPath = $request->file('certificate_logo')->store('events/certificates/logos', 'public');
            $certificateMeta['logo_path'] = $logoPath;
        }

        $metadata['certificate'] = $certificateMeta;
        $updateData['metadata'] = $metadata;

        // Handle poster upload (simpan langsung ke public/images/posters)
        if ($request->hasFile('poster')) {
            $posterFile = $request->file('poster');
            $posterName = 'poster_'.time().'_'.Str::random(8).'.'.$posterFile->getClientOriginalExtension();
            $posterFile->move(public_path('images/posters'), $posterName);
            // Simpan path relatif dari public untuk digunakan dengan asset()
            $updateData['poster'] = 'images/posters/'.$posterName;
        }

        // Handle banner uploads (simpan langsung ke public/images/banners)
        if ($request->hasFile('banners')) {
            $bannerPaths = [];
            foreach ($request->file('banners') as $banner) {
                $bannerName = 'banner_'.time().'_'.Str::random(8).'.'.$banner->getClientOriginalExtension();
                $banner->move(public_path('images/banners'), $bannerName);
                $bannerPaths[] = 'images/banners/'.$bannerName;
            }
            $updateData['banners'] = json_encode($bannerPaths);

            // Jika belum ada poster (di event lama maupun input baru),
            // gunakan banner pertama sebagai poster utama supaya selalu ada gambar di kartu event
            if ((empty($event->poster) && empty($updateData['poster'] ?? null)) && count($bannerPaths) > 0) {
                $updateData['poster'] = $bannerPaths[0];
            }
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

        // Jika event sebelumnya sudah published dan terjadi perubahan melalui step3
        // tetapi organizer tidak mencentang submit_for_review secara eksplisit,
        // tetap paksa status menjadi pending_review agar perubahan menunggu approve admin.
        if ($previousStatus === 'published' && empty($updateData['status'])) {
            $updateData['status'] = 'pending_review';
        }

        $event->update($updateData);

        // Mirror media updates to main events table
        try {
            $mirror = [
                'poster' => $event->poster,
                'has_certificate' => (bool)$request->boolean('has_certificate'),
                'certificate_template' => $request->certificate_template ?? 'template_a',
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

        // Cari event publik yang di-mirror dari partner event ini (berdasarkan slug)
        $publicEvent = Event::where('slug', $event->slug)->first();

        $stats = [
            'total_registrations' => 0,
            'total_paid' => 0,
            'total_attended' => 0,
            'total_not_attended' => 0,
        ];
        $participants = collect();
        $attendances = collect();

        if ($publicEvent) {
            // Ambil peserta & absensi untuk event publik ini
            $participants = $publicEvent->participants()->get();

            $attendances = \App\Models\Attendance::with('participant')
                ->where('event_id', $publicEvent->id)
                ->get();

            $stats['total_registrations'] = $participants->count();
            $stats['total_paid'] = $participants->where('payment_status', 'paid')->count();
            $stats['total_attended'] = $attendances->count();

            // Peserta yang bayar tapi belum tercatat hadir
            $paidParticipantIds = $participants->where('payment_status', 'paid')->pluck('id');
            $attendedParticipantIds = $attendances->pluck('participant_id');
            $stats['total_not_attended'] = $paidParticipantIds->diff($attendedParticipantIds)->count();
        }

        return view('partner.events.show', [
            'event' => $event,
            'publicEvent' => $publicEvent,
            'stats' => $stats,
            'participants' => $participants,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Preview certificate HTML for this event (organizer side).
     */
    public function previewCertificate($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->with('organization')->findOrFail($id);

        $rawMetadata = $event->metadata ?? [];
        if (is_string($rawMetadata)) {
            $decoded = json_decode($rawMetadata, true);
            $metadata = is_array($decoded) ? $decoded : [];
        } else {
            $metadata = is_array($rawMetadata) ? $rawMetadata : [];
        }

        $certMeta = $metadata['certificate'] ?? [];
        $hasCertificate = (bool)($certMeta['has_certificate'] ?? false);
        $template = $certMeta['certificate_template'] ?? 'template_a';

        $participantName = 'Jonathan Smith';

        return view('partner.events.certificate.preview', [
            'event' => $event,
            'template' => $template,
            'hasCertificate' => $hasCertificate,
            'participantName' => $participantName,
        ]);
    }

    /**
     * Show attendance page for this event (EO side in Diantara Nexus)
     */
    public function attendance($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->with('organization')->findOrFail($id);

        // Find mirrored public event by slug
        $publicEvent = Event::where('slug', $event->slug)->first();

        if (!$publicEvent) {
            abort(404, 'Event publik tidak ditemukan untuk absensi.');
        }

        $totalRegistrations = EventAttendance::where('event_id', $publicEvent->id)->count();
        $totalAttended = EventAttendance::where('event_id', $publicEvent->id)
            ->where('is_attended', true)
            ->count();

        return view('partner.events.attendance', [
            'event' => $publicEvent,
            'totalRegistrations' => $totalRegistrations,
            'totalAttended' => $totalAttended,
        ]);
    }

    /**
     * Handle attendance token submission from EO (Diantara Nexus)
     */
    public function attendanceStore(Request $request, $id)
    {
        $request->validate([
            'attendance_token' => 'required|string|size:10',
        ]);

        $partner = Auth::guard('partner')->user();
        $partnerEvent = $partner->events()->with('organization')->findOrFail($id);

        // Resolve mirrored public event
        $publicEvent = Event::where('slug', $partnerEvent->slug)->first();
        if (!$publicEvent) {
            return back()->with('error', 'Event publik untuk absensi tidak ditemukan.');
        }

        try {
            $attendance = EventAttendance::where('event_id', $publicEvent->id)
                ->where('attendance_token', $request->attendance_token)
                ->with('user')
                ->first();

            if (!$attendance) {
                return back()->with('error', 'Token absensi tidak valid untuk event ini.');
            }

            if ($attendance->is_attended) {
                return back()->with('error', 'Peserta ini sudah ditandai hadir sebelumnya.');
            }

            $verifiedBy = $partnerEvent->organization->name
                ?? ($partner->name ?? 'Organizer');

            $attendance->markAttended($verifiedBy);

            return back()->with('success', 'Absensi berhasil untuk peserta: ' . ($attendance->user->full_name ?? $attendance->user->name ?? $attendance->user->email));
        } catch (\Exception $e) {
            \Log::error('Partner attendanceStore failed: ' . $e->getMessage(), [
                'partner_id' => $partner->id,
                'event_id' => $partnerEvent->id,
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memproses absensi.');
        }
    }

    /**
     * Submit event for review
     */
    public function submitForReview($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($id);

        // Hanya event dengan status draft atau published yang boleh diajukan untuk review
        if (!in_array($event->status, ['draft', 'published'])) {
            return back()->with('error', 'Only draft or published events can be submitted for review.');
        }

        // Tandai sebagai pending_review sehingga perubahan harus di-approve admin
        $event->update(['status' => 'pending_review']);

        // Sembunyikan dari katalog publik sementara (status mirror di tabel events menjadi pending)
        try {
            \App\Models\Event::where('slug', $event->slug)->update(['status' => 'pending']);
        } catch (\Throwable $e) {
            \Log::warning('Sync partner event submitForReview to events table failed: '.$e->getMessage());
        }

        return back()->with('success', 'Event submitted for review successfully!');
    }

    /**
     * Edit event basic info (redirect to step1 form for drafts)
     */
    public function edit($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($id);

        // Hanya event dengan status draft atau pending_review yang bisa diedit dari organizer
        if (!in_array($event->status, ['draft', 'pending_review'])) {
            return redirect()->route('diantaranexus.events.show', $event->id)
                ->with('error', 'Hanya event dengan status draft atau dalam review yang dapat diedit.');
        }

        // Arahkan ke step1 agar form yang sama bisa dipakai sebagai edit screen
        return redirect()->route('diantaranexus.events.create.step1', $event->id);
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy($id)
    {
        $partner = Auth::guard('partner')->user();
        $event = $partner->events()->findOrFail($id);

        // Untuk keamanan, batasi penghapusan hanya untuk draft / pending_review
        if (in_array($event->status, ['published'])) {
            return back()->with('error', 'Event yang sudah tayang tidak dapat dihapus dari organizer.');
        }

        DB::transaction(function () use ($event) {
            // Hapus tiket terkait
            $event->tickets()->delete();

            // Hapus mirror di tabel events (jika ada)
            try {
                Event::where('slug', $event->slug)->delete();
            } catch (\Throwable $e) {
                \Log::warning('Failed deleting mirrored Event for partner event '.$event->id.': '.$e->getMessage());
            }

            // Hapus partner event
            $event->delete();
        });

        return redirect()->route('diantaranexus.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
