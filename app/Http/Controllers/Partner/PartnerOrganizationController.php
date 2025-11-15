<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerOrganizationController extends Controller
{
    public function show()
    {
        $partner = Auth::guard('partner')->user();
        $organizationId = session('selected_organization_id');
        $organization = $partner->organizations()->findOrFail($organizationId);

        return view('partner.organization.index', compact('organization'));
    }

    public function update(Request $request)
    {
        $partner = Auth::guard('partner')->user();
        $organizationId = session('selected_organization_id');
        $organization = $partner->organizations()->findOrFail($organizationId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'socials' => 'nullable|array',
            'socials.*.platform' => 'nullable|string|max:50',
            'socials.*.handle' => 'nullable|string|max:100',

            // Signatures
            'signature1_name' => 'nullable|string|max:100',
            'signature1_title' => 'nullable|string|max:100',
            'signature1_type' => 'nullable|in:upload,draw',
            'signature1_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'signature1_draw' => 'nullable|string', // base64

            'signature2_name' => 'nullable|string|max:100',
            'signature2_title' => 'nullable|string|max:100',
            'signature2_type' => 'nullable|in:upload,draw',
            'signature2_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'signature2_draw' => 'nullable|string', // base64

            'stamp_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $update = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'] ?? null,
            'website' => $validated['website'] ?? null,
        ];

        // Build contact_info JSON
        $contact = [
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'socials' => $validated['socials'] ?? [],
        ];
        $update['contact_info'] = $contact;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations/logos', 'public');
            $update['logo'] = $path;
        }

        // Handle stamp upload
        if ($request->hasFile('stamp_image')) {
            $update['stamp_image'] = $request->file('stamp_image')->store('organizations/stamps', 'public');
        }

        // Helper to save base64 signature
        $saveBase64 = function (string $data, string $slot) {
            if (!preg_match('/^data:image\/(png|jpeg);base64,/', $data)) return null;
            $data = substr($data, strpos($data, ',') + 1);
            $binary = base64_decode($data);
            if ($binary === false) return null;
            $ext = 'png';
            $filename = "organizations/signatures/{$slot}_".time().".".$ext;
            \Storage::disk('public')->put($filename, $binary);
            return $filename;
        };

        // Signature 1
        $update['signature1_name'] = $validated['signature1_name'] ?? $organization->signature1_name;
        $update['signature1_title'] = $validated['signature1_title'] ?? $organization->signature1_title;
        if (($validated['signature1_type'] ?? null) === 'upload' && $request->hasFile('signature1_image')) {
            $update['signature1_image'] = $request->file('signature1_image')->store('organizations/signatures', 'public');
            $update['signature1_type'] = 'upload';
        } elseif (($validated['signature1_type'] ?? null) === 'draw' && !empty($validated['signature1_draw'])) {
            if ($saved = $saveBase64($validated['signature1_draw'], 'sig1')) {
                $update['signature1_image'] = $saved;
                $update['signature1_type'] = 'draw';
            }
        }

        // Signature 2
        $update['signature2_name'] = $validated['signature2_name'] ?? $organization->signature2_name;
        $update['signature2_title'] = $validated['signature2_title'] ?? $organization->signature2_title;
        if (($validated['signature2_type'] ?? null) === 'upload' && $request->hasFile('signature2_image')) {
            $update['signature2_image'] = $request->file('signature2_image')->store('organizations/signatures', 'public');
            $update['signature2_type'] = 'upload';
        } elseif (($validated['signature2_type'] ?? null) === 'draw' && !empty($validated['signature2_draw'])) {
            if ($saved = $saveBase64($validated['signature2_draw'], 'sig2')) {
                $update['signature2_image'] = $saved;
                $update['signature2_type'] = 'draw';
            }
        }

        $organization->update($update);

        return back()->with('success', 'Informasi organisasi berhasil disimpan.');
    }
}
