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
        Log::info('📄 [BILLING INDEX] START', [
            'auth_user' => auth()->id(),
            'session_id' => session()->getId(),
            'tenant_id' => session('tenant_id'),
        ]);

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
            'request_data' => $request->all(),
            'auth_user' => auth()->id(),
            'session_id' => $request->session()->getId(),
            'tenant_id' => session('tenant_id'),
        ]);

        $planId = $request->saas_plan_id ?? $request->plan_id;

        Log::info('🔎 PLAN CHECK', ['plan_id' => $planId]);

        if (!$planId) {
            Log::warning('⚠️ PLAN NOT SELECTED');
            return back()->with('error', 'Plan not selected');
        }

        $user = auth()->user();

        if (!$user) {
            Log::error('❌ USER NOT AUTH', [
                'session' => $request->session()->all()
            ]);
            return redirect()->route('login');
        }

        // ✅ FIX HERE
        $tenantId = session('tenant_id');

        Log::info('🏢 TENANT FETCH', [
            'tenant_id' => $tenantId,
            'user_id' => $user->id
        ]);

        if (!$tenantId) {
            Log::error('❌ TENANT NOT FOUND IN SESSION');
            return back()->with('error', 'Tenant session missing');
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            Log::error('❌ TENANT NOT FOUND', [
                'tenant_id' => $tenantId,
                'user_id' => $user->id
            ]);
            return back()->with('error', 'Tenant not found');
        }

        $plan = SaasPlan::find($planId);

        if (!$plan) {
            Log::error('❌ PLAN INVALID', ['plan_id' => $planId]);
            return back()->with('error', 'Invalid plan');
        }

        Log::info('📦 PLAN LOADED', $plan->toArray());

        // FREE PLAN
        if ((float) $plan->price === 0.0) {

            Log::info('🆓 FREE PLAN START', [
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id
            ]);

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

            Log::info('✅ FREE PLAN DONE', [
                'subscription_id' => $subscription->id,
                'tenant_id' => $tenant->id
            ]);

            return redirect()->route('filament.admin.pages.dashboard')
                ->with('success', 'Free plan activated!');
        }

        // STRIPE
        Log::info('💳 STRIPE INIT', [
            'user_email' => $user->email,
            'tenant_id' => $tenant->id
        ]);

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

                'success_url' => route('billing.success', [
                    'plan_id' => $plan->id
                ]),

                'cancel_url' => route('billing.plans'),
            ]);

            Log::info('✅ STRIPE SESSION CREATED', [
                'session_id' => $session->id,
                'url' => $session->url,
                'tenant_id' => $tenant->id
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {

            Log::error('❌ STRIPE ERROR', [
                'message' => $e->getMessage(),
                'tenant_id' => $tenant->id,
                'user_id' => $user->id ?? null
            ]);

            return back()->with('error', 'Payment failed');
        }
    }


   public function success(Request $request)
{
    Log::info('🎯 [SUCCESS HIT]', [
        'request' => $request->all(),
        'auth_user' => auth()->id(),
        'session_id' => $request->session()->getId(),
    ]);

    $planId = $request->get('plan_id');
    $user = auth()->user();

    if (!$user) {
        Log::error('❌ SUCCESS NO USER');
        return redirect()->route('login');
    }

    if (!$planId) {
        return redirect()->route('billing.plans')->with('error', 'Plan missing');
    }

    // Tenant nikalne ke liye session aur user relation dono check karein
    $tenantId = session('tenant_id') ?? $user->tenant_id;

    if (!$tenantId) {
        Log::error('❌ SUCCESS TENANT ID MISSING');
        return redirect()->route('billing.plans')->with('error', 'Tenant context lost.');
    }

    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
        Log::error('❌ SUCCESS NO TENANT FOUND', ['id' => $tenantId]);
        return redirect()->route('billing.plans');
    }

    // Database updates
    $user->update([
        'tenant_id' => $tenant->id,
        'status' => 'active'
    ]);

    $plan = SaasPlan::find($planId);
    if (!$plan) {
        Log::error('❌ SUCCESS INVALID PLAN');
        return redirect()->route('billing.plans');
    }

    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // Customer email se latest subscription fetch karein (zyada reliable method)
        $subscriptions = \Stripe\Subscription::all([
            'customer' => $user->stripe_id, // Agar aapne user model par stripe_id save kiya hai
            'limit' => 1,
            'status' => 'active',
        ]);

        // Agar user->stripe_id nahi hai, toh purana method fallback rakhein:
        $stripeSubscription = $subscriptions->data[0] ?? null;

        if (!$stripeSubscription) {
            // Log fallback search
            Log::warning('⚠️ Stripe subscription not found by user ID, fetching global latest...');
            $globalSubs = \Stripe\Subscription::all(['limit' => 1, 'status' => 'active']);
            $stripeSubscription = $globalSubs->data[0] ?? null;
        }

        if (!$stripeSubscription) {
            throw new \Exception("Active stripe subscription not found.");
        }

        $stripeSubscriptionId = $stripeSubscription->id;
        $stripeCustomerId = $stripeSubscription->customer;

    } catch (\Exception $e) {
        Log::error('❌ STRIPE VERIFY FAIL', ['message' => $e->getMessage()]);
        return redirect()->route('billing.plans')->with('error', 'Payment verification failed');
    }

    // Subscription update/create
    $subscription = $tenant->subscription()->updateOrCreate(
        ['tenant_id' => $tenant->id],
        [
            'saas_plan_id' => $plan->id,
            'stripe_subscription_id' => $stripeSubscriptionId,
            'stripe_customer_id' => $stripeCustomerId,
            'status' => 'active',
        ]
    );

    $tenant->update([
        'is_active' => true,
        'status' => 'active',
    ]);

    // 🔥 CRITICAL FIX: Session ko force update aur save karein
    session(['tenant_id' => $tenant->id]);
    $request->session()->put('tenant_id', $tenant->id);
    $request->session()->save(); 

    Log::info('🎉 SUCCESS COMPLETE - REDIRECTING', [
        'tenant_id' => $tenant->id,
        'subscription_id' => $subscription->id
    ]);

    // 🔥 FIX 2: intended() ki jagah direct route ya path par bhejein
    // Filament ke dashboard ke liye hamesha direct path use karna behtar hota hai
    return redirect('/admin')
        ->with('success', 'Subscription activated! Welcome to your dashboard.');
}
}