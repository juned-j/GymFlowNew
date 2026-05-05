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

        $tenant = Tenant::find($user->getTenantId());

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

        // ✅ FREE PLAN (direct activate)
        if ((float)$plan->price == 0) {
            $tenant->subscription()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'saas_plan_id' => $plan->id,
                    'status' => 'active',
                    'trial_ends_at' => now()->addDays(30),
                ]
            );

            return redirect()->route('billing.success', [
                'plan_id' => $plan->id
            ]);
        }

        // ❌ Stripe validation
        $priceId = $plan->stripe_price_id;

        if (!$priceId || !str_starts_with($priceId, 'price_')) {
            return back()->with('error', 'Stripe Price ID invalid');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // 🔥 FIX: plan_id success_url me pass kar rahe hain
        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $user->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',

            'success_url' => route('billing.success', [
                'plan_id' => $plan->id
            ]),

            'cancel_url' => route('billing.plans'),
        ]);

        return redirect($session->url);
    }

    // 🔥 NEW SUCCESS METHOD (IMPORTANT FIX)
    public function success(Request $request)
    {
        Log::info('🎯 SUCCESS HIT', $request->all());

        $planId = $request->plan_id;

        if (!$planId) {
            Log::error('❌ PLAN ID MISSING');
            return redirect()->route('billing.plans')
                ->with('error', 'Plan missing');
        }

        $user = auth()->user();

        if (!$user) {
            Log::error('❌ USER NOT FOUND');
            return redirect()->route('login');
        }

        $tenant = Tenant::find($user->getTenantId());

        if (!$tenant) {
            Log::error('❌ TENANT NOT FOUND');
            return redirect()->route('billing.plans');
        }

        $plan = SaasPlan::find($planId);

        if (!$plan) {
            Log::error('❌ PLAN NOT FOUND');
            return redirect()->route('billing.plans');
        }

        // ✅ MAIN FIX (subscription insert)
        $subscription = $tenant->subscription()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'saas_plan_id' => $plan->id,
                'status' => 'active',
            ]
        );

        Log::info('✅ SUBSCRIPTION CREATED', [
            'subscription_id' => $subscription->id,
        ]);

        // ✅ activate tenant
        $tenant->update([
            'is_active' => true,
            'status' => 'active',
        ]);

        return redirect()->route('filament.admin.pages.dashboard')
            ->with('success', 'Subscription activated!');
    }
}