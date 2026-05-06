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
        'session' => session()->all(),
    ]);

    try {

        // =========================
        // PLAN ID
        // =========================
        $planId = $request->saas_plan_id ?? $request->plan_id;

        Log::info('🔍 [PLAN ID]', ['plan_id' => $planId]);

        if (!$planId) {
            Log::warning('⚠️ PLAN ID MISSING');
            return back()->with('error', 'Plan not selected');
        }

        // =========================
        // USER
        // =========================
        $user = auth()->user();

        Log::info('👤 [USER]', [
            'user' => $user?->toArray()
        ]);

        if (!$user) {
            Log::warning('⚠️ USER NOT AUTHENTICATED');
            return redirect()->route('login');
        }

        // =========================
        // 🔥 TENANT FIX (IMPORTANT)
        // =========================
        $tenantId = session('tenant_id') ?? $user->getTenantId();

        Log::info('🏢 [TENANT ID FINAL]', ['tenant_id' => $tenantId]);

        $tenant = Tenant::find($tenantId);

        Log::info('🏢 [TENANT]', [
            'tenant' => $tenant?->toArray()
        ]);

        if (!$tenant) {
            Log::error('❌ TENANT NOT FOUND');
            return back()->with('error', 'Tenant not found');
        }

        // =========================
        // PLAN
        // =========================
        $plan = SaasPlan::find($planId);

        Log::info('📦 [PLAN DATA]', [
            'plan' => $plan?->toArray()
        ]);

        if (!$plan) {
            Log::error('❌ PLAN NOT FOUND');
            return back()->with('error', 'Invalid plan');
        }

        // =========================
        // FREE PLAN
        // =========================
        if ((float) $plan->price === 0.0) {

            Log::info('🆓 FREE PLAN FLOW START');

            $subscription = $tenant->subscription()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'saas_plan_id' => $plan->id,
                    'stripe_subscription_id' => 'free_' . uniqid(),
                    'status' => 'active',
                    'trial_ends_at' => now()->addDays(30),
                ]
            );

            Log::info('✅ FREE SUBSCRIPTION CREATED', [
                'subscription' => $subscription->toArray()
            ]);

            $tenant->update([
                'is_active' => true,
                'status' => 'active',
            ]);

            return redirect()->route('filament.admin.pages.dashboard')
                ->with('success', 'Free plan activated!');
        }

        // =========================
        // STRIPE CONFIG
        // =========================
        $stripeKey = config('services.stripe.secret');

        Log::info('🔑 STRIPE KEY CHECK', [
            'key_exists' => !empty($stripeKey),
            'key_prefix' => substr($stripeKey ?? 'NULL', 0, 10),
        ]);

        \Stripe\Stripe::setApiKey($stripeKey);

        // =========================
        // STRIPE SESSION CREATE
        // =========================
        Log::info('💳 CREATING STRIPE SESSION', [
            'email' => $user->email,
            'price_id' => $plan->stripe_price_id,
        ]);

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $user->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('billing.success', [
                'plan_id' => $plan->id
            ]),
            'cancel_url' => route('billing.plans'),
        ]);

        Log::info('✅ STRIPE SESSION CREATED', [
            'session_id' => $session->id,
            'url' => $session->url,
        ]);

        return redirect($session->url);

    } catch (\Throwable $e) {

        Log::error('❌ STRIPE FATAL ERROR', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => substr($e->getTraceAsString(), 0, 1000),
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

    if (!$sessionId || !$planId) {
        return redirect()->route('billing.plans')
            ->with('error', 'Invalid payment response');
    }

    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    // 🔥 FIX: TENANT RESOLVE (USER + SESSION FALLBACK)
    $tenantId = $user->getTenantId() ?? session('tenant_id');
    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
        return redirect()->route('billing.plans')
            ->with('error', 'Tenant not found');
    }

    $plan = SaasPlan::find($planId);
    if (!$plan) {
        return redirect()->route('billing.plans');
    }

    try {

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // ✅ FETCH CHECKOUT SESSION
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        Log::info('🔍 STRIPE SESSION', [
            'id' => $session->id,
            'subscription' => $session->subscription,
            'customer' => $session->customer,
            'payment_status' => $session->payment_status,
        ]);

        // ❌ PAYMENT NOT COMPLETE
        if ($session->payment_status !== 'paid') {
            return redirect()->route('billing.plans')
                ->with('error', 'Payment not completed');
        }

        $stripeSubscriptionId = $session->subscription;
        $stripeCustomerId = $session->customer;

    } catch (\Exception $e) {

        Log::error('❌ STRIPE VERIFY FAILED', [
            'error' => $e->getMessage(),
        ]);

        return redirect()->route('billing.plans')
            ->with('error', 'Payment verification failed');
    }

    // =========================
    // SAVE SUBSCRIPTION
    // =========================
    $subscription = $tenant->subscription()->updateOrCreate(
        ['tenant_id' => $tenant->id],
        [
            'saas_plan_id' => $plan->id,
            'stripe_subscription_id' => $stripeSubscriptionId,
            'stripe_customer_id' => $stripeCustomerId,
            'status' => 'active',
        ]
    );

    Log::info('✅ SUBSCRIPTION SAVED', [
        'id' => $subscription->id,
    ]);

    // =========================
    // ACTIVATE TENANT
    // =========================
    $tenant->update([
        'is_active' => true,
        'status' => 'active',
    ]);

    // 🔥 FIX: SESSION + LOGIN RESTORE (VERY IMPORTANT)
    Auth::login($user);
    session()->put('tenant_id', $tenant->id);

    return redirect()
        ->route('filament.admin.pages.dashboard')
        ->with('success', 'Subscription activated!');
}
}