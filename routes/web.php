<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website (PTBuddy Landing)
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');

Route::view('/features', 'features')->name('features');

Route::view('/pricing', 'pricing')->name('pricing');

Route::view('/contact', 'contact')->name('contact');

Route::get('/login', function () {
    return redirect('/admin/login');
});
