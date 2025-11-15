<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerEvent;
use App\Models\PartnerTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartnerDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware handled in routes
    }

    /**
     * Show organization selector or dashboard.
     */
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        $organizations = $partner->organizations;
        
        // If switch_org parameter or no organization selected in session, show selector
        if (request('switch_org') || !session('selected_organization_id')) {
            // Clear selected organization if switching
            if (request('switch_org')) {
                session()->forget('selected_organization_id');
            }
            if ($organizations->count() == 1) {
                // Auto-select if only one organization
                session(['selected_organization_id' => $organizations->first()->id]);
                return redirect()->route('diantaranexus.dashboard');
            } elseif ($organizations->count() > 1) {
                // Show selector if multiple organizations
                return view('partner.organization-selector', compact('organizations'));
            } else {
                // No organizations - redirect to setup
                return redirect()->route('diantaranexus.setup-organization');
            }
        }
        
        // Show dashboard for selected organization
        return $this->showDashboard();
    }
    
    /**
     * Select organization and redirect to dashboard.
     */
    public function selectOrganization($organizationId)
    {
        $partner = Auth::guard('partner')->user();
        $organization = $partner->organizations()->findOrFail($organizationId);
        
        session(['selected_organization_id' => $organization->id]);
        
        return redirect()->route('diantaranexus.dashboard');
    }
    
    /**
     * Show the actual dashboard.
     */
    private function showDashboard()
    {
        $partner = Auth::guard('partner')->user();
        $organizationId = session('selected_organization_id');
        $organization = $partner->organizations()->findOrFail($organizationId);
        
        // Get partner statistics for selected organization
        $stats = [
            'total_events' => PartnerEvent::where('organization_id', $organizationId)->count(),
            'active_events' => PartnerEvent::where('organization_id', $organizationId)
                ->whereIn('status', ['published', 'active'])
                ->count(),
            'draft_events' => PartnerEvent::where('organization_id', $organizationId)
                ->where('status', 'draft')
                ->count(),
            'pending_events' => PartnerEvent::where('organization_id', $organizationId)
                ->whereIn('status', ['pending', 'pending_review'])
                ->count(),
        ];

        // Get recent events for the selected organization (show latest 6)
        $recentEvents = PartnerEvent::where('organization_id', $organizationId)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Generate mock monthly revenue data for chart
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => 0, // Start with 0 for new partners
            ];
        }

        return view('partner.dashboard', compact('stats', 'recentEvents', 'monthlyRevenue', 'organization'));
    }

    /**
     * Show partner profile page.
     */
    public function profile()
    {
        $partner = Auth::guard('partner')->user();
        
        return view('partner.profile', compact('partner'));
    }

    /**
     * Update partner profile.
     */
    public function updateProfile(Request $request)
    {
        $partner = Auth::guard('partner')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'organization_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $partner->update($request->only(['name', 'phone', 'organization_name', 'address']));

        return back()->with('success', 'Profile updated successfully!');
    }
}
