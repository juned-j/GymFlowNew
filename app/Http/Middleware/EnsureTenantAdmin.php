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

    // ❌ Not logged in
    if (! $user) {
        return redirect()->route('login');
    }

    // =========================
    // 🔥 AUTO FIX TENANT CONTEXT
    // =========================
    if (!session()->has('tenant_id') && $user->tenant_id) {
        session()->put('tenant_id', $user->tenant_id);
    }

    if (! $user->tenant_id && !session('tenant_id')) {
        abort(403, 'Tenant access only');
    }

    \Log::info('🔐 TENANT ACCESS OK', [
        'user_id' => $user->id,
        'tenant_id' => session('tenant_id'),
    ]);

    return $next($request);
}



}
