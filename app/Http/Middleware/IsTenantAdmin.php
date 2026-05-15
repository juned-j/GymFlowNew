<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTenantAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow guests to hit the admin login page
        if (! auth('admin')->check()) {
            return $next($request);
        }

        $user = auth('admin')->user();

        // Prevent Super Admin from accessing admin panel
        if ($user->isSuperAdmin()) {
            auth('admin')->logout();
            return redirect('/admin/login')
                ->with('error', 'You are not authorized to access the Admin Panel.');
        }

        // Ensure user has a valid tenant role
        if (! $user->hasTenantRole()) {
            auth('admin')->logout();
            return redirect('/admin/login')
                ->with('error', 'You are not authorized to access the Admin Panel.');
        }

        return $next($request);
    }
}
