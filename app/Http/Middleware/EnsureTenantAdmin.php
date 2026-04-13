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

        if (! $user || ! $user->isTenantUser()) {
            abort(403, 'Tenant access only');
        }

        return $next($request);
    }
}
