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
    // 1. Initial Log: Request check
    Log::info('🎯 [STRIPE SUCCESS] Method started', [
        'url' => $request->fullUrl(),
        'all_params' => $request->all(),
        'has_session_id' => $request->has('session_id'),
        'current_auth_id' => auth()->id() // Ye production par null ho sakta hai agar session lose hua ho
    ]);

    $sessionId = $request->get('session_id');
    $planId = $request->get('plan_id');

    // Check 1: Session ID check
    if (!$sessionId) {
        Log::error('❌ [STRIPE SUCCESS] Missing session_id in URL');
        return redirect()->route('billing.plans')->with('error', 'Invalid session data.');
    }

    // 2. Auth Check (The most common production failure)
    $user = auth()->user();
    if (!$user) {
        Log::warning('⚠️ [STRIPE SUCCESS] Auth session lost. Attempting to recover from session data.');
        // Agar auth lost hai, toh stripe ke retrieve ke baad hum dubara login karayenge
    }

    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        Log::info('🔍 [STRIPE SUCCESS] Retrieving session from Stripe...', ['session_id' => $sessionId]);
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        Log::info('📊 [STRIPE SUCCESS] Stripe Response Details', [
            'payment_status' => $session->payment_status,
            'customer_email' => $session->customer_details?->email,
            'subscription_id' => $session->subscription
        ]);

        if ($session->payment_status !== 'paid') {
            Log::warning('⛔ [STRIPE SUCCESS] Payment status is not PAID', ['status' => $session->payment_status]);
            return redirect()->route('billing.plans')->with('error', 'Payment was not completed.');
        }

        // Recovery: Agar Auth user null tha, toh email se user dhoondo
        if (!$user) {
            $user = \App\Models\User::where('email', $session->customer_details->email)->first();
            if ($user) {
                Log::info('🔄 [STRIPE SUCCESS] User recovered by email', ['user_id' => $user->id]);
                auth()->login($user, true); // Force login
            } else {
                Log::error('❌ [STRIPE SUCCESS] Could not recover user by email');
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }
        }

        // 3. Tenant Resolve with Deep Logs
        $tenantId = $user->getTenantId() ?? session('tenant_id');
        Log::info('🏢 [STRIPE SUCCESS] Resolving Tenant', ['id_from_user' => $user->getTenantId(), 'id_from_session' => session('tenant_id')]);
        
        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant) {
            Log::error('❌ [STRIPE SUCCESS] Tenant not found in DB', ['resolved_id' => $tenantId]);
            return redirect()->route('billing.plans')->with('error', 'Tenant profile not found.');
        }

        $plan = \App\Models\SaasPlan::find($planId);

        // 4. Update Database
        Log::info('💾 [STRIPE SUCCESS] Updating Tenant & Subscription...', ['tenant_id' => $tenant->id]);
        
        $tenant->subscription()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'saas_plan_id' => $plan->id ?? null,
                'stripe_subscription_id' => $session->subscription,
                'stripe_customer_id' => $session->customer,
                'status' => 'active',
                'trial_ends_at' => null,
            ]
        );

        $tenant->update([
            'is_active' => true,
            'status' => 'active',
        ]);

        // 5. Final Step: Session Persistence Fix
        auth()->login($user, true); // Login forcefully with remember-me
        session()->put('tenant_id', $tenant->id);
        session()->flash('success', '🎉 Subscription activated!');
        
        // 🔥 SABSE CRITICAL: Production par manual save zaroori hai
        session()->save(); 

        Log::info('✅ [STRIPE SUCCESS] Process completed. Redirecting to dashboard.', [
            'final_user_id' => auth()->id(),
            'final_tenant_id' => session('tenant_id')
        ]);

        return redirect()->route('filament.admin.pages.dashboard');

    } catch (\Exception $e) {
        Log::error('🔥 [STRIPE SUCCESS] CRITICAL ERROR', [
            'msg' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('billing.plans')->with('error', 'Verification failed: ' . $e->getMessage());
    }
}
}