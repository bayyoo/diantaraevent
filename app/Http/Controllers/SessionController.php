<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    /**
     * Extend user session
     */
    public function extend(Request $request)
    {
        if (auth()->check()) {
            // Update last activity time - use same key as SessionTimeout middleware
            Session::put('last_activity_time', time());
            
            return response()->json([
                'success' => true,
                'message' => 'Session extended successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ], 401);
    }
}
