<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAdmin
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        $hasTenantRole = $user?->roles()
            ->whereIn('role', ['owner', 'trainer'])
            ->exists();

        if (! $hasTenantRole) {
            abort(403, 'Tenant Admin only');
        }

        return $next($request);
    }
}
