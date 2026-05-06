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

    // Pehle DB check karein, phir session fallback
    $hasDbAccess = $user->isTenantUser();
    $hasSessionAccess = session()->has('tenant_id');

    if ($hasDbAccess || $hasSessionAccess) {
        // Agar DB mein link nahi hai but session mein hai, toh link create kar dein (Self-healing)
        if (!$hasDbAccess && $hasSessionAccess) {
            \App\Models\UserTenantRole::firstOrCreate([
                'user_id' => $user->id,
                'tenant_id' => session('tenant_id'),
                'role_id' => 1 // Admin role
            ]);
        }
        return $next($request);
    }

    \Illuminate\Support\Facades\Log::warning('403 Forbidden: User has no tenant access', ['user_id' => $user->id]);
    abort(403, 'Tenant access only');
}
}
