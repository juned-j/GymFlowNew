<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // super admin can also access tenant panel (optional)
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        if (!$user->hasRole('owner') && !$user->hasRole('trainer')) {
            abort(403, 'Tenant admin access only.');
        }

        return $next($request);
    }
}
