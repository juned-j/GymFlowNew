<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SaasPlan;
use App\Models\Tenant;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class BillingController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('billing.plans', compact('plans'));
    }


public function subscribe(Request $request)
{
    Log::info('🚀 [SUBSCRIBE START]', [
        'request' => $request->all(),
        'user_id' => auth()->id(),
    ]);

    try {

        $planId = $request->saas_plan_id ?? $request->plan_id;

        if (!$planId) {
            return back()->with('error', 'Plan not selected');
        }

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // ✅ Tenant resolve
        $tenantId = session('tenant_id') ?? $user->getTenantId();
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return back()->with('error', 'Tenant not found');
        }

        // ✅ Plan
        $plan = SaasPlan::find($planId);
        if (!$plan) {
            return back()->with('error', 'Invalid plan');
        }

        // =========================
        // FREE PLAN
        // =========================
        if ((float) $plan->price === 0.0) {

            $tenant->subscription()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'saas_plan_id' => $plan->id,
                    'stripe_subscription_id' => 'free_' . uniqid(),
                    'status' => 'active',
                    'trial_ends_at' => now()->addDays(30),
                ]
            );

            $tenant->update([
                'is_active' => true,
                'status' => 'active',
            ]);

            return redirect()->route('filament.admin.pages.dashboard')
                ->with('success', 'Free plan activated!');
        }

        // =========================
        // STRIPE
        // =========================
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // 🔥 IMPORTANT: route() use nahi karna
        $successUrl = url('/billing/success')
            . '?session_id={CHECKOUT_SESSION_ID}&plan_id=' . $plan->id;

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $user->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',

            // ✅ FIXED URL
            'success_url' => $successUrl,

            'cancel_url' => route('billing.plans'),
        ]);

        Log::info('✅ STRIPE SESSION CREATED', [
            'session_id' => $session->id,
            'success_url' => $successUrl,
        ]);

        return redirect($session->url);

    } catch (\Throwable $e) {

        Log::error('❌ STRIPE ERROR', [
            'message' => $e->getMessage(),
        ]);

        return back()->with('error', $e->getMessage());
    }
}

    // =========================
    // SUCCESS (PRODUCTION SAFE)
    // =========================
public function success(Request $request)
{
    Log::info('🎯 SUCCESS HIT', $request->all());

    $sessionId = $request->get('session_id');
    $planId = $request->get('plan_id');

    // ❌ session_id missing
    if (!$sessionId) {
        Log::warning('⚠️ Missing session_id');
        return redirect()->route('billing.plans')
            ->with('error', 'Session expired, try again');
    }

    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $tenantId = $user->getTenantId() ?? session('tenant_id');
    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
        return redirect()->route('billing.plans')
            ->with('error', 'Tenant not found');
    }

    $plan = SaasPlan::find($planId);
    if (!$plan) {
        return redirect()->route('billing.plans')
            ->with('error', 'Plan not found');
    }

    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        Log::info('🔍 STRIPE SESSION', [
            'id' => $session->id,
            'payment_status' => $session->payment_status,
        ]);

        if ($session->payment_status !== 'paid') {
            return redirect()->route('billing.plans')
                ->with('error', 'Payment not completed');
        }

        if (!$session->subscription || !$session->customer) {
            return redirect()->route('billing.plans')
                ->with('error', 'Invalid session');
        }

        // ✅ SAVE
        $tenant->subscription()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'saas_plan_id' => $plan->id,
                'stripe_subscription_id' => $session->subscription,
                'stripe_customer_id' => $session->customer,
                'status' => 'active',
            ]
        );

        // ✅ Activate tenant
        $tenant->update([
            'is_active' => true,
            'status' => 'active',
        ]);

    } catch (\Exception $e) {

        Log::error('❌ VERIFY FAILED', [
            'error' => $e->getMessage(),
        ]);

        return redirect()->route('billing.plans')
            ->with('error', 'Payment verification failed');
    }

    // ✅ Session restore
    auth()->login($user);
    session()->put('tenant_id', $tenant->id);

    return redirect()
        ->route('filament.admin.pages.dashboard')
        ->with('success', '🎉 Subscription activated!');
}
}