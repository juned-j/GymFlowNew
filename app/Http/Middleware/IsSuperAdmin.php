<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow guests to access login page
        if (! auth()->check()) {
            return $next($request);
        }
        $user = auth()->user();
        if (! $user->is_super_admin) {
            abort(403, 'Unauthorized. Super Admin only.');
        }
        return $next($request);
    }
}
