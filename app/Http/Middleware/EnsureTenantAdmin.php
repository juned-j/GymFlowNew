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

    if ($tenantId) {

        // 🔥 FORCE FIX हर request पर
        \App\Models\UserTenantRole::updateOrCreate(
            [
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
            ],
            [
                'role_id' => 1
            ]
        );

        return $next($request);
    }

    abort(403, 'Tenant access only');
}
}
