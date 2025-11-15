<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfTokenController extends Controller
{
    /**
     * Return fresh CSRF token
     * Used by JavaScript to refresh token before expiration
     */
    public function refresh(Request $request)
    {
        return response()->json([
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
