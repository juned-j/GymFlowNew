<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->status === 'blocked') {
            auth()->logout(); // force logout
            return redirect()->route('filament.auth.login')
                ->withErrors([
                    'email' => 'Your account is blocked. Contact admin.',
                ]);
        }
        return $next($request);
    }
}
