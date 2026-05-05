<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\SaasPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('billing.plans', compact('plans'));
    }

public function subscribe(Request $request)
{
    Log::info('🚀 SUBSCRIBE START', $request->all());

    $planId = $request->saas_plan_id ?? $request->plan_id;

    if (!$planId) {
        return back()->with('error', 'Plan not selected');
    }

    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $tenant = \App\Models\Tenant::find($user->getTenantId());

    if (!$tenant) {
        return back()->with('error', 'Tenant not found');
    }

    // ✅ PLAN FETCH
    $plan = SaasPlan::findOrFail($planId);

    Log::info('📦 PLAN', [
        'id' => $plan->id,
        'interval' => $plan->billing_interval,
        'stripe_price_id' => $plan->stripe_price_id,
    ]);

    // FREE PLAN
    if ((float)$plan->price == 0) {
        $tenant->subscription()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'saas_plan_id' => $plan->id,
                'status' => 'active',
                'trial_ends_at' => now()->addDays(30),
            ]
        );

        return redirect()->route('billing.success');
    }

    // Stripe validation
    $priceId = $plan->stripe_price_id;

    if (!$priceId || !str_starts_with($priceId, 'price_')) {
        return back()->with('error', 'Stripe Price ID invalid');
    }

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $session = \Stripe\Checkout\Session::create([
        'customer_email' => $user->email,
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $priceId,
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'success_url' => route('billing.success'),
        'cancel_url' => route('billing.plans'),
    ]);

    return redirect($session->url);
}
}