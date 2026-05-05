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
        if (! $user) {
            return $next($request); // allow login page
        }
        if (! $user->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin only.');
        }
        return $next($request);
    }

//      public function handle($request, Closure $next)
// {
//     return $next($request); // ✅ TEMP disable
// }
}
