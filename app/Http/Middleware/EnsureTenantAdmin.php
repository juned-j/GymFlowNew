<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class EnsureTenantAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // if ($request->routeIs('login') || $request->routeIs('register.*')) {
        //     return $next($request);
        // }

        // $user = auth()->user();

        // if (!$user) {
        //     return redirect()->route('login');
        // }

        // $tenant = \App\Models\Tenant::where('owner_user_id', $user->id)->first();

        // Log::info('TENANT FETCH', [
        //     'tenant' => $tenant->name,
        // ]);
        // if (!$tenant) {
        //     Log::info('New User detected - Redirecting to Gym Registration', [
        //         'user_id' => $user->id
        //     ]);

        //     return redirect()->route('register.gym');
        // }

        // $tenant->load('subscription');

        // $hasActiveTenant = ($tenant->is_active || $tenant->status === 'active');
        // $hasActivePlan = $tenant->subscription && $tenant->subscription->saas_plan_id !== null;

        // if ($hasActiveTenant && $hasActivePlan) {
        //     session(['tenant_id' => $tenant->id]);
        //     return $next($request);
        // }

        // Log::info('Owner needs payment - Redirecting to billing', [
        //     'tenant_id' => $tenant->id
        // ]);

        // return redirect()->route('billing.plans')
        //     ->with('info', 'Please activate your plan to access the dashboard.');
    }
}
