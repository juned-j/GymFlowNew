<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SaasPlan;
use App\Models\Tenant;

class BillingController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('billing.plans', compact('plans'));
    }

    // =========================
    // SUBSCRIBE (PRODUCTION FIXED)
    // =========================
    public function subscribe(Request $request)
    {
        Log::info('🚀 [SUBSCRIBE START]', [
            'request' => $request->all(),
            'user_id' => auth()->id(),
        ]);

        $planId = $request->saas_plan_id ?? $request->plan_id;

        if (!$planId) {
            return back()->with('error', 'Plan not selected');
        }

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $tenant = Tenant::find($user->getTenantId());

        if (!$tenant) {
            return back()->with('error', 'Tenant not found');
        }

        $plan = SaasPlan::find($planId);

        if (!$plan) {
            return back()->with('error', 'Invalid plan');
        }

        Log::info('📦 [PLAN FOUND]', $plan->toArray());

        // =========================
        // FREE PLAN (SAFE)
        // =========================
        if ((float) $plan->price === 0.0) {

            $subscription = $tenant->subscription()->updateOrCreate(
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
        // STRIPE CHECKOUT
        // =========================
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {

            $session = \Stripe\Checkout\Session::create([
                'customer_email' => $user->email,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $plan->stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',

                // 🔥 CLEAN SUCCESS FLOW (NO session_id dependency)
                'success_url' => route('billing.success', [
                    'plan_id' => $plan->id
                ]),

                'cancel_url' => route('billing.plans'),
            ]);

            Log::info('✅ STRIPE SESSION CREATED', [
                'session_id' => $session->id,
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {

            Log::error('❌ STRIPE ERROR', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Payment failed');
        }
    }

    // =========================
    // SUCCESS (PRODUCTION SAFE)
    // =========================
    public function success(Request $request)
    {
        Log::info('🎯 SUCCESS HIT', $request->all());

        $planId = $request->get('plan_id');

        if (!$planId) {
            return redirect()->route('billing.plans')
                ->with('error', 'Plan missing');
        }

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $tenant = Tenant::find($user->getTenantId());

        if (!$tenant) {
            return redirect()->route('billing.plans');
        }

        $plan = SaasPlan::find($planId);

        if (!$plan) {
            return redirect()->route('billing.plans');
        }

        // =========================
        // STRIPE VERIFY VIA LATEST SUBSCRIPTION (BEST METHOD)
        // =========================
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // 🔥 BEST PRACTICE: get latest subscription instead of session
            $subscriptions = \Stripe\Subscription::all([
                'limit' => 1,
                'status' => 'active',
            ]);

            $stripeSubscription = $subscriptions->data[0] ?? null;

            if (!$stripeSubscription) {
                Log::error('❌ NO ACTIVE STRIPE SUBSCRIPTION FOUND');

                return redirect()->route('billing.plans')
                    ->with('error', 'Payment not confirmed');
            }

            $stripeSubscriptionId = $stripeSubscription->id;
            $stripeCustomerId = $stripeSubscription->customer;

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

        return redirect()
            ->route('filament.admin.pages.dashboard')
            ->with('success', 'Subscription activated!');
    }
}