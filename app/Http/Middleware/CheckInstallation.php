<?php

namespace BT\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     * Redirects to the setup wizard if the app is not installed.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if APP_INSTALLED is explicitly set to false in .env
        // Also ensure we are not already trying to access the setup routes to prevent redirect loops
        if (config('app.installed') === false && !$request->is('setup*')) {
            return redirect('/setup');
        }
        return $next($request);
    }
}