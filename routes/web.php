<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\GymOnboardingController;
use App\Http\Controllers\BillingController;

/*
|--------------------------------------------------------------------------
| Public Website (Landing)
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');
Route::view('/features', 'features')->name('features');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/contact', 'contact')->name('contact');


/*
|--------------------------------------------------------------------------
| LOGIN (IMPORTANT FIX FOR EMAIL VERIFY)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');


/*
|--------------------------------------------------------------------------
| LOGS (DEV ONLY)
|--------------------------------------------------------------------------
*/

Route::get('/view-logs', function () {

    $path = storage_path('logs/laravel.log');

    if (!file_exists($path)) {
        return 'No log file found.';
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lastLines = array_slice($lines, -2000);

    $content = nl2br(htmlspecialchars(implode("\n", $lastLines)));

    return <<<HTML
    <html>
    <head>
        <title>Laravel Logs</title>
        <style>
            body {
                background: #111;
                color: #0f0;
                font-family: monospace;
                white-space: pre-wrap;
                padding: 20px;
            }
        </style>
    </head>
    <body>{$content}</body>
    </html>
    HTML;
});


/*
|--------------------------------------------------------------------------
| GYM ONBOARDING FLOW
|--------------------------------------------------------------------------
*/

Route::get('/register', [GymOnboardingController::class, 'showGymStep'])
    ->name('register.gym');

Route::post('/register/gym', [GymOnboardingController::class, 'storeGym'])
    ->name('register.gym.store');

Route::get('/register/user', [GymOnboardingController::class, 'showUserStep'])
    ->name('register.user');

Route::post('/register/user', [GymOnboardingController::class, 'storeUser'])
    ->name('register.user.store');

Route::get('/register/plan', [GymOnboardingController::class, 'showPlans'])
    ->name('register.plan');

Route::post('/register/plan', [GymOnboardingController::class, 'storePlan'])
    ->name('register.plan.store');

Route::post('/register/checkout', [GymOnboardingController::class, 'createCheckout'])
    ->name('register.checkout');


/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION (FIXED - NO AUTH MIDDLEWARE)
|--------------------------------------------------------------------------
*/

// verify link
Route::get('/email/verify/{id}/{hash}', [GymOnboardingController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

// notice page (IMPORTANT FIX)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

// resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['throttle:6,1'])->name('verification.send');


/*
|--------------------------------------------------------------------------
| BILLING (GYM SAAS)
|--------------------------------------------------------------------------
*/

Route::get('/billing/plans', [BillingController::class, 'index'])
    ->name('billing.plans');

Route::post('/billing/subscribe', [BillingController::class, 'subscribe'])
    ->name('billing.subscribe');

Route::get('/billing/success', function () {
    return redirect('/admin')->with('success', 'Subscription activated!');
})->name('billing.success');