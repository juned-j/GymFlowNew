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
        dd([
            'isTenantUser' => $user->isTenantUser(),
            'roles' => $user->roles()->with('role')->get()->pluck('role.name'),
        ]);
        if (! $user || ! $user->isTenantUser()) {
            abort(403, 'Tenant access only');
        }

        return $next($request);
    }
}
