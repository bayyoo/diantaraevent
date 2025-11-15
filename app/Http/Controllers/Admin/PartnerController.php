<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of partners.
     */
    public function index()
    {
        $partners = Partner::with('organizations')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Display the specified partner.
     */
    public function show(Partner $partner)
    {
        $partner->load('organizations');
        
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Verify a partner.
     */
    public function verify(Partner $partner)
    {
        $partner->update([
            'status' => 'verified',
            'verified_at' => now()
        ]);

        return back()->with('success', 'Partner has been verified successfully.');
    }

    /**
     * Reject a partner.
     */
    public function reject(Partner $partner)
    {
        $partner->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        return back()->with('success', 'Partner has been rejected.');
    }
}
