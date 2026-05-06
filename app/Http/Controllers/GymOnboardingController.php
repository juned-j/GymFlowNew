<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    // STEP 1: STORE IN SESSION
    // ==============================
  public function storeGym(Request $request)
{
    Log::info('🚀 storeGym started', [
        'input' => $request->all()
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:100|min:3',
        'email' => 'required|email|unique:users,email', // ✅ FIXED
        'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s]+$/',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:50',
        'country' => 'nullable|string|max:50',
        'timezone' => 'nullable|string|max:50',
    ], [
        'email.unique' => 'This email is already registered.', // ✅ NICE MESSAGE
    ]);

    // ✅ SESSION FIXED (request session use)
    $request->session()->put('gym_data', $validated);

    return redirect()->route('register.user')
        ->with('success', 'Gym details saved. Now create your account!');
}

    // ==============================
    // STEP 2: CREATE USER + TENANT
    // ==============================
   public function storeUser(Request $request)
{
    Log::info('🚀 storeUser started', [
        'input' => $request->all(),
        'session' => $request->session()->all(),
    ]);

    if (!$request->session()->has('gym_data')) {
        return redirect()->route('register.gym')
            ->with('error', 'Session expired. Please start again.');
    }

    try {

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
        ], [
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        Log::info('✅ Validation passed', $validated);

        // USER CREATE
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        Log::info('✅ User created', ['user_id' => $user->id]);

        // GYM SESSION DATA
        $gym = $request->session()->get('gym_data');

        // TENANT CREATE
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

        // LINK USER ↔ TENANT
        \App\Models\UserTenantRole::create([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'role_id' => 1
        ]);

        $request->session()->put('tenant_id', $tenant->id);

        // CLEANUP
        $request->session()->forget('gym_data');

        // LOGIN + SESSION FIX
        Auth::login($user);
        $request->session()->regenerate();

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please verify your email.');

    } catch (\Illuminate\Validation\ValidationException $e) {

        // ✅ IMPORTANT: field-wise errors properly return
        return back()
            ->withErrors($e->errors())
            ->withInput();

    } catch (\Exception $e) {

        Log::error('❌ storeUser failed', [
            'message' => $e->getMessage(),
            'line' => $e->getLine()
        ]);

        return back()
            ->withInput()
            ->withErrors([
                'error' => 'Something went wrong. Please try again.'
            ]);
    }
}

    public function showUserStep()
    {
        if (!request()->session()->has('gym_data')) {
            return redirect()->route('register.gym')
                ->with('error', 'Please complete gym step first');
        }

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

        Auth::login($user);
        $request->session()->regenerate();

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