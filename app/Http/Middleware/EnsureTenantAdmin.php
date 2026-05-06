<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTenantAdmin
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isTenantUser()) {

            return redirect()
                ->route('billing.plans')
                ->with('error', 'Please activate your subscription first.');
        }

        return $next($request);
    }
}