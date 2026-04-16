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
            $tenantId = session('tenant_id');

            if (!$tenantId && !$user->isSuperAdmin()) {
                // Recovery: Try to find a tenant ID if session is missing
                $tenantId = $user->roles()
                    ->whereHas('role', fn($q) => $q->where('name', 'owner'))
                    ->value('tenant_id')
                    ?? $user->roles()->value('tenant_id');

                if ($tenantId) {
                    session(['tenant_id' => $tenantId]);
                }
            }

            if ($tenantId) {
                app()->instance('tenant_id', $tenantId);
            }
        }

        return $next($request);
    }
}
