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

public function storeGym(Request $request)
{
    Log::info('🚀 storeGym started', [
        'input' => $request->all()
    ]);

    // ✅ Laravel will auto redirect back with errors
    $validated = $request->validate([
        'name' => 'required|string|max:100|min:3',
        'email' => 'required|email|unique:tenants,email',
        'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s]+$/',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:50',
        'country' => 'nullable|string|max:50',
        'timezone' => 'nullable|string|max:50',
    ], [
        'name.required' => 'Gym name is required',
        'name.min' => 'Gym name must be at least 3 characters',

        'email.required' => 'Email is required',
        'email.email' => 'Enter a valid email address',
        'email.unique' => 'This email is already registered',

        'phone.regex' => 'Enter valid phone number',
    ]);

    $tenant = Tenant::create([
        'name' => $validated['name'],
        'slug' => Str::slug($validated['name']) . '-' . uniqid(),
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'address' => $validated['address'] ?? null,
        'city' => $validated['city'] ?? null,
        'country' => $validated['country'] ?? null,
        'owner_user_id' => 1,
        'timezone' => $validated['timezone'] ?? 'Asia/Kolkata',
        'currency' => 'INR',
        'currency_symbol' => '₹',
        'status' => 'active',
        'is_active' => false,
    ]);

    Session::put('tenant_id', $tenant->id);

    return redirect()->route('register.user')
        ->with('success', 'Gym created successfully!');
}

public function storeUser(Request $request)
{
    Log::info('🚀 storeUser started', [
        'input' => $request->all(),
        'session' => session()->all(),
    ]);

    if (!Session::has('tenant_id')) {
        return redirect()->route('register.gym')
            ->with('error', 'Session expired. Please start again.');
    }

    try {

        // ✅ VALIDATION WITH RULES + MESSAGES
        $validated = $request->validate([
            'name' => 'required|string|max:50',

            'email' => 'required|email|unique:users,email',

            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',   // uppercase
                'regex:/[a-z]/',   // lowercase
                'regex:/[0-9]/',   // number
            ],
        ], [
            'name.required' => 'Name is required',

            'email.required' => 'Email is required',
            'email.email' => 'Enter valid email address',
            'email.unique' => 'This email is already registered',

            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation does not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain uppercase, lowercase and number',
        ]);

        Log::info('✅ Validation passed', $validated);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        Log::info('✅ User created', ['user_id' => $user->id]);

        \App\Models\UserTenantRole::create([
            'user_id' => $user->id,
            'tenant_id' => Session::get('tenant_id'),
            'role_id' => 1
        ]);

        Tenant::where('id', Session::get('tenant_id'))
            ->update(['owner_user_id' => $user->id]);

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
    Log::info('👤 showUserStep opened', [
        'tenant_id' => Session::get('tenant_id')
    ]);

    // safety check
    if (!Session::has('tenant_id')) {
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

    // OPTIONAL but recommended
    if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        $user->update(['status' => 'active']);
    }

    Auth::login($user);

   return redirect()->route('billing.plans');
}

    public function showPlans()
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('onboarding.plans', compact('plans'));
    }

    public function success()
    {
        $tenant = Tenant::find(Session::get('tenant_id'));

        if ($tenant) {
            $tenant->update([
                'is_active' => true,
                'status' => 'active',
            ]);
        }

        Session::forget(['tenant_id', 'user_id']);

        return redirect()->route('filament.admin.pages.dashboard')
            ->with('success', 'Gym Activated Successfully!');
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

    return redirect()->route('home')->with('success', 'Plan selected successfully!');
}
}