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
        Log::info('📄 [BILLING INDEX] loading plans');

        $plans = SaasPlan::where('is_active', true)->get();

        Log::info('📦 [PLANS LOADED]', [
            'count' => $plans->count(),
            'plan_ids' => $plans->pluck('id')
        ]);

        return view('billing.plans', compact('plans'));
    }

    // =========================
    // SUBSCRIBE
    // =========================
    public function subscribe(Request $request)
    {
        Log::info('🚀 [SUBSCRIBE START]', [
            'request' => $request->all(),
            'user_id' => auth()->id(),
        ]);

        $planId = $request->saas_plan_id ?? $request->plan_id;

        Log::info('🔎 PLAN ID CHECK', [
            'plan_id' => $planId
        ]);

        if (!$planId) {
            Log::warning('⚠️ PLAN NOT SELECTED');
            return back()->with('error', 'Plan not selected');
        }

        $user = auth()->user();

        Log::info('👤 AUTH USER CHECK', [
            'user' => $user ? $user->id : null
        ]);

        if (!$user) {
            Log::error('❌ USER NOT AUTHENTICATED');
            return redirect()->route('login');
        }

        $tenantId = $user->getTenantId();

        Log::info('🏢 TENANT ID FETCHED', [
            'tenant_id' => $tenantId
        ]);

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            Log::error('❌ TENANT NOT FOUND', [
                'tenant_id' => $tenantId
            ]);
            return back()->with('error', 'Tenant not found');
        }

        $plan = SaasPlan::find($planId);

        if (!$plan) {
            Log::error('❌ INVALID PLAN', [
                'plan_id' => $planId
            ]);
            return back()->with('error', 'Invalid plan');
        }

        Log::info('📦 PLAN LOADED', $plan->toArray());

        // =========================
        // FREE PLAN FLOW
        // =========================
        if ((float) $plan->price === 0.0) {

            Log::info('🆓 FREE PLAN ACTIVATION START');

            $subscription = $tenant->subscription()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'saas_plan_id' => $plan->id,
                    'stripe_subscription_id' => 'free_' . uniqid(),
                    'status' => 'active',
                    'trial_ends_at' => now()->addDays(30),
                ]
            );

            Log::info('✅ FREE PLAN ACTIVATED', [
                'subscription_id' => $subscription->id
            ]);

            $tenant->update([
                'is_active' => true,
                'status' => 'active',
            ]);

            Log::info('🏢 TENANT ACTIVATED (FREE PLAN)', [
                'tenant_id' => $tenant->id
            ]);

            return redirect()->route('filament.admin.pages.dashboard')
                ->with('success', 'Free plan activated!');
        }

        // =========================
        // STRIPE CHECKOUT
        // =========================
        Log::info('💳 STRIPE CHECKOUT INIT');

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {

            Log::info('🔐 STRIPE KEY SET');

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
                'url' => $session->url
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {

            Log::error('❌ STRIPE SESSION ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Payment failed');
        }
    }

    // =========================
    // SUCCESS
    // =========================
    public function success(Request $request)
    {
        Log::info('🎯 [SUCCESS HIT]', $request->all());

        $planId = $request->get('plan_id');

        Log::info('🔎 SUCCESS PLAN CHECK', [
            'plan_id' => $planId
        ]);

        if (!$planId) {
            Log::warning('⚠️ PLAN MISSING IN SUCCESS');
            return redirect()->route('billing.plans')
                ->with('error', 'Plan missing');
        }

        $user = auth()->user();

        if (!$user) {
            Log::error('❌ SUCCESS USER NOT FOUND');
            return redirect()->route('login');
        }

        $tenant = Tenant::find($user->getTenantId());

        if (!$tenant) {
            Log::error('❌ SUCCESS TENANT NOT FOUND');
            return redirect()->route('billing.plans');
        }

        $plan = SaasPlan::find($planId);

        if (!$plan) {
            Log::error('❌ SUCCESS PLAN INVALID');
            return redirect()->route('billing.plans');
        }

        Log::info('📦 SUCCESS FLOW DATA READY', [
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id
        ]);

        try {

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            Log::info('🔐 VERIFYING STRIPE SUBSCRIPTION');

            $subscriptions = \Stripe\Subscription::all([
                'limit' => 1,
                'status' => 'active',
            ]);

            $stripeSubscription = $subscriptions->data[0] ?? null;

            if (!$stripeSubscription) {
                Log::error('❌ NO ACTIVE STRIPE SUBSCRIPTION');

                return redirect()->route('billing.plans')
                    ->with('error', 'Payment not confirmed');
            }

            Log::info('✅ STRIPE SUBSCRIPTION FOUND', [
                'id' => $stripeSubscription->id
            ]);

            $stripeSubscriptionId = $stripeSubscription->id;
            $stripeCustomerId = $stripeSubscription->customer;

        } catch (\Exception $e) {

            Log::error('❌ STRIPE VERIFY ERROR', [
                'message' => $e->getMessage()
            ]);

            return redirect()->route('billing.plans')
                ->with('error', 'Payment verification failed');
        }

        // =========================
        // SAVE
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

        Log::info('💾 SUBSCRIPTION SAVED', [
            'subscription_id' => $subscription->id
        ]);

        $tenant->update([
            'is_active' => true,
            'status' => 'active',
        ]);

        Log::info('🏢 TENANT ACTIVATED (PAID PLAN)', [
            'tenant_id' => $tenant->id
        ]);

        Log::info('🎉 BILLING SUCCESS COMPLETE');

        return redirect()
            ->route('filament.admin.pages.dashboard')
            ->with('success', 'Subscription activated!');
    }
}