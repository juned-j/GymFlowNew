<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log; 


class BillingController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)->get();
        return view('billing.plans', compact('plans'));
    }

public function subscribe(Request $request)
{
    Log::info('🚀 [SUBSCRIBE HIT] Request received', $request->all());

    $request->validate([
        'plan_id' => 'required|exists:plans,id',
        'billing_cycle' => 'required|in:monthly,annual',
    ]);

    $user = auth()->user();

    if (!$user) {
        Log::error('❌ User not authenticated');
        return redirect()->route('login');
    }

    Log::info('👤 Auth User Found', ['id' => $user->id]);

    // ✅ FIXED TENANT RESOLUTION
    $tenantId = $user->getTenantId();

    Log::info('🏢 Tenant ID resolved', ['tenant_id' => $tenantId]);

    if (!$tenantId) {
        Log::error('❌ Tenant ID missing from user roles');
        return back()->with('error', 'Tenant not linked to user');
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        Log::error('❌ Tenant record not found in DB');
        return back()->with('error', 'Tenant not found');
    }

    Log::info('🏢 Tenant Found', ['tenant' => $tenant]);

    $plan = \App\Models\Plan::findOrFail($request->plan_id);

    Log::info('📦 Plan selected', ['plan' => $plan->id]);

    // FREE PLAN
    if ($plan->monthly_price == 0) {

        Log::info('🟢 Free plan activated');

        $tenant->subscription()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'saas_plan_id' => $plan->id,
                'status' => 'active',
                'trial_ends_at' => now()->addDays(30),
            ]
        );

        Log::info('✅ Free subscription saved');

        return redirect()->route('billing.success');
    }

    // STRIPE FLOW
    try {

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $priceId = $request->billing_cycle === 'annual'
            ? $plan->stripe_annual_price_id
            : $plan->stripe_monthly_price_id;

        Log::info('💳 Stripe Price ID', ['price' => $priceId]);

        if (!$priceId) {
            return back()->with('error', 'Stripe price missing');
        }

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

        Log::info('✅ Stripe session created', ['url' => $session->url]);

        return redirect($session->url);

    } catch (\Exception $e) {

        Log::error('❌ Stripe Error', ['msg' => $e->getMessage()]);

        return back()->with('error', $e->getMessage());
    }
}
}