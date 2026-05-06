<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAdmin
{
   public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    
    if (!$user) {
        return redirect()->route('login');
    }

    $tenant = \App\Models\Tenant::where('owner_user_id', $user->id)->first();

    if (!$tenant) {
        Log::info('New User detected - Redirecting to Gym Registration', ['user_id' => $user->id]);
        return redirect()->route('register.gym');
    }

    $tenant->load('subscription');

    $hasActiveTenant = ($tenant->is_active || $tenant->status === 'active');
    $hasActivePlan = $tenant->subscription && $tenant->subscription->saas_plan_id !== null;
    
   
    $isNewUser = $user->created_at->diffInMinutes(now()) < 60; 

    if ($hasActiveTenant && $hasActivePlan) {
        session(['tenant_id' => $tenant->id]);
        return $next($request);
    }

  
    if ($tenant->owner_user_id !== $user->id) {
        abort(403, 'Unauthorized action. You are not the owner of this tenant.');
    }

    Log::info('Owner needs payment - Redirecting to billing', ['tenant_id' => $tenant->id]);
    
    return redirect()->route('billing.plans')
        ->with('info', 'Please activate your plan to access the dashboard.');
}
}
