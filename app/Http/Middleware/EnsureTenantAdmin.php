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

    // Check 1: User has tenant roles in DB OR Session has a valid tenant_id
    if ($user->isTenantUser() || session()->has('tenant_id')) {
        return $next($request);
    }

    // Agar dono fail ho jayein
    Log::warning('403 Forbidden: User has no tenant access', ['user_id' => $user->id]);
    abort(403, 'Tenant access only');
}
}
