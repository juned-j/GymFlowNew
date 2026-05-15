<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow guests to hit the platform login page
        if (! auth()->check()) {
            return $next($request);
        }
        $user = auth()->user();
        // Use the method from your model instead of just the property
        if (! $user->isSuperAdmin()) {
            // Optional: Automatically log them out of the platform guard 
            // so they don't get trapped in a 403 loop.
            auth()->logout();
            abort(403, 'Unauthorized. Super Admin only.');
        }
        return $next($request);
    }
}
