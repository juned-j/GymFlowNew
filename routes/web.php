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
Route::get('/view-logs', function () {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) {
        return 'No log file found.';
    }
    
    // Read file into an array of lines
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Get only the last 2000 lines
    $lastLines = array_slice($lines, -2000);
    
    // Escape HTML and convert newlines for browser display
    $content = nl2br(htmlspecialchars(implode("\n", $lastLines)));
    
    // Return HTML page (Auto-refresh removed)
    return <<<HTML
    <html>
    <head>
        <title>Laravel Logs (Manual Refresh)</title>
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