<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureTenantAdmin
{

public function handle($request, Closure $next)
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    $tenantId = session('tenant_id');

    if ($user->isTenantUser()) {
        return $next($request);
    }

    if ($tenantId) {

        \App\Models\UserTenantRole::updateOrCreate(
            [
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
            ],
            [
                'role_id' => 1
            ]
        );

        Log::info('🔧 Tenant access repaired from session', [
            'user_id' => $user->id,
            'tenant_id' => $tenantId
        ]);

        return $next($request);
    }

    // ❌ 3. Final block
    Log::warning('403 Forbidden: User has no tenant access', [
        'user_id' => $user->id
    ]);

    abort(403, 'Tenant access only');
}
}
