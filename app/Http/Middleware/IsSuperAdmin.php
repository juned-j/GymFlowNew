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
        if (! auth('platform')->check()) {
            return $next($request);
        }
        $user = auth('platform')->user();
        // Use the method from your model instead of just the property
        if (! $user->isSuperAdmin()) {
            // Optional: Automatically log them out of the platform guard
            // so they don't get trapped in a 403 loop.
            auth('platform')->logout();
            return redirect('/platform/login')
                ->with('error', 'You are not authorized to access the Platform Panel.');
        }
        return $next($request);
    }
}
