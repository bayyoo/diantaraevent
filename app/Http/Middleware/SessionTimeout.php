<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivityTime = Session::get('last_activity_time');
            $sessionTimeout = 120 * 60; // 120 minutes (2 hours) in seconds
            
            // Check if session has expired
            if ($lastActivityTime && (time() - $lastActivityTime) > $sessionTimeout) {
                // Session has expired - logout user
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();
                
                // Redirect to login with message
                return redirect()->route('login')
                    ->with('error', 'Sesi Anda telah berakhir karena tidak aktif selama 2 jam. Silakan login kembali.');
            }
            
            // Update last activity time
            Session::put('last_activity_time', time());
        }
        
        return $next($request);
    }
}
