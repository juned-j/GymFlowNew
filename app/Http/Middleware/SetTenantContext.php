<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    // public function handle(Request $request, Closure $next): Response
    // {
    //     app()->forgetInstance('tenant');
    //     app()->forgetInstance('tenant_id');

    //     if (!Auth::check()) {
    //         return $next($request);
    //     }

    //     $user = Auth::user();
    //     $tenantId = $user?->getTenantId();

    //     if (!$tenantId) {
    //         \Log::warning('⚠️ No tenant_id for user', [
    //             'user_id' => $user->id ?? null,
    //         ]);

    //         return $next($request);
    //     }

    //     $tenant = Tenant::find($tenantId);

    //     if (!$tenant) {
    //         \Log::error('❌ Tenant not found', [
    //             'tenant_id' => $tenantId,
    //             'user_id' => $user->id ?? null,
    //         ]);

    //         return $next($request);
    //     }

    //     app()->instance('tenant', $tenant);
    //     app()->instance('tenant_id', $tenant->id);

       

    //     \Log::info('✅ Tenant context set', [
    //         'tenant_id' => $tenant->id,
    //         'user_id' => $user->id,
    //     ]);

    //     return $next($request);
    // }

     public function handle(Request $request, Closure $next): Response
    {
        // ✅ Middleware bypassed completely
        return $next($request);
    }
}