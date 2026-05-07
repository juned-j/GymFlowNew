<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class EnsureTenantAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Middleware bypassed completely
        return $next($request);
    }
}
