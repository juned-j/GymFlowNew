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
        Log::info('🚀 [SUBSCRIBE HIT]', $request->all());

        $request->validate([
            'saas_plan_id' => 'required|exists:saas_plans,id',
            'billing_cycle' => 'required|in:monthly,annual',
        ]);

        $user = auth()->user();

        if (!$user) {
            Log::error('❌ User not authenticated');
            return redirect()->route('login');
        }

        $tenantId = $user->getTenantId();

        if (!$tenantId) {
            return back()->with('error', 'Tenant not linked to user');
        }

        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant) {
            return back()->with('error', 'Tenant not found');
        }

        // ✅ SaaS Plan fetch
        $plan = SaasPlan::findOrFail($request->saas_plan_id);

        Log::info('📦 SaaS Plan selected', ['id' => $plan->id]);

        // -----------------------------
        // 🟢 FREE PLAN
        // -----------------------------
        if ($plan->monthly_price == 0 && $plan->annual_price == 0) {

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

        // -----------------------------
        // 💳 STRIPE FLOW
        // -----------------------------
        try {

            Stripe::setApiKey(config('services.stripe.secret'));

            $priceId = $request->billing_cycle === 'annual'
                ? $plan->stripe_annual_price_id
                : $plan->stripe_monthly_price_id;

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

            return redirect($session->url);

        } catch (\Exception $e) {

            Log::error('❌ Stripe Error', ['msg' => $e->getMessage()]);

            return back()->with('error', $e->getMessage());
        }
    }
}