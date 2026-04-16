<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class SetTenantContext
{
    public function handle($request, \Closure $next)
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $tenantId = $user?->getTenantId();

            if ($tenantId) {
                app()->instance('tenant_id', $tenantId);
            }
        }

        return $next($request);
    }
}
