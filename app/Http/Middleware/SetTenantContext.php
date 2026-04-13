<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class SetTenantContext
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $tenantId = $user->current_tenant_id
                ?? $user->tenant_id
                ?? null;

            if ($tenantId) {
                app()->instance('tenant_id', $tenantId);
            }
        }

        return $next($request);
    }
}
