<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        $isSuper = $user?->roles()
            ->where('role', 'super_admin')
            ->exists();

        if (! $isSuper) {
            abort(403, 'Super Admin only');
        }

        return $next($request);
    }
}
