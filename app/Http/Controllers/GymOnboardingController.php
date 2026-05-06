<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Country;

use App\Models\Tenant;
use App\Models\User;
use App\Models\SaasPlan;

class GymOnboardingController extends Controller
{
    public function showGymStep()
    {
        $countries = Country::orderBy('name')->get();
        return view('onboarding.gym', compact('countries'));
    }

    // ==============================
    // STEP 1: ONLY STORE IN SESSION
    // ==============================
 public function storeGym(Request $request)
{
    Log::info('🚀 storeGym started', [
        'input' => $request->all()
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:100|min:3',
        'email' => 'required|email|unique:tenants,email', // 🔥 UNIQUE FIX
        'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s]+$/',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:50',
        'country' => 'required|string|max:50', // 🔥 REQUIRED FIX (UI match)
        'timezone' => 'nullable|string|max:50',
    ]);

    session()->put('gym_data', $validated);

    return redirect()->route('register.user')
        ->with('success', 'Gym details saved. Now create your account!');
}

    // ==============================
    // STEP 2
    // ==============================
public function storeUser(Request $request)
{
    Log::info('🚀 storeUser started', [
        'input' => $request->all(),
    ]);

    // SESSION CHECK
    if (!session()->has('gym_data')) {
        return back()
            ->withInput()
            ->with('error', 'Session expired. Please refill gym details.');
    }

    // SESSION TOUCH
    session()->put('gym_data', session('gym_data'));

    // ✅ VALIDATION
    $validated = $request->validate([
        'name' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email',
        'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/[A-Z]/',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
        ],
    ]);

    try {

        // =========================
        // CREATE USER
        // =========================
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        $gym = session('gym_data');

        // =========================
        // CREATE TENANT
        // =========================
        $tenant = Tenant::create([
            'name' => $gym['name'],
            'slug' => Str::slug($gym['name']) . '-' . uniqid(),
            'email' => $gym['email'],
            'owner_user_id' => $user->id,
            'phone' => $gym['phone'] ?? null,
            'address' => $gym['address'] ?? null,
            'city' => $gym['city'] ?? null,
            'country' => $gym['country'] ?? null,
            'timezone' => $gym['timezone'] ?? 'Asia/Kolkata',
            'currency' => 'INR',
            'currency_symbol' => '₹',
            'status' => 'active',
            'is_active' => false,
        ]);

        // 🔥🔥🔥 MOST IMPORTANT FIX
        // USER → TENANT LINK
        $user->tenant_id = $tenant->id;
        $user->save();

        // =========================
        // ROLE MAPPING
        // =========================
        \App\Models\UserTenantRole::create([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'role_id' => 1
        ]);

        // =========================
        // SESSION SET
        // =========================
        session()->put('tenant_id', $tenant->id);

        Log::info('✅ USER + TENANT LINKED', [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id
        ]);

        // =========================
        // CLEANUP
        // =========================
        session()->forget('gym_data');

        // =========================
        // EMAIL VERIFY
        // =========================
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please verify your email.');

    } catch (\Exception $e) {

        Log::error('❌ storeUser failed', [
            'message' => $e->getMessage(),
            'line' => $e->getLine()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Something went wrong. Please try again.');
    }
}

    public function showUserStep()
    {
        if (!session()->has('gym_data')) {
            return redirect()->route('register.gym')
                ->with('error', 'Please complete gym step first');
        }

        // 🔥 SESSION TOUCH
        session()->put('gym_data', session('gym_data'));

        return view('onboarding.user');
    }

 public function verifyEmail(Request $request, $id, $hash)
{
    $user = User::findOrFail($id);

    if (! $request->hasValidSignature()) {
        abort(403);
    }

    if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        $user->update(['status' => 'active']);
    }

    // =========================
    // 🔥 LOGIN
    // =========================
    Auth::login($user);

    // =========================
    // 🔥🔥 MOST IMPORTANT FIX
    // TENANT SESSION SET
    // =========================
    if ($user->tenant_id) {
        session()->put('tenant_id', $user->tenant_id);
    }

    // Optional debug
    \Log::info('✅ EMAIL VERIFIED LOGIN', [
        'user_id' => $user->id,
        'tenant_id' => $user->tenant_id
    ]);

    return redirect()->route('billing.plans');
}

    public function showPlans()
    {
        $plans = SaasPlan::where('is_active', true)->get();
        return view('onboarding.plans', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'saas_plan_id' => 'required|exists:plans,id',
        ]);

        $user = auth()->user();

        $user->update([
            'saas_plan_id' => $request->saas_plan_id,
        ]);

        return redirect()->route('home')
            ->with('success', 'Plan selected successfully!');
    }
}