<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Request;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        UserLoginLog::create([
            'user_id'      => $event->user->id,
            'email'        => $event->user->email,
            'ip_address'   => Request::ip(),
            'user_agent'   => Request::userAgent(),
            'logged_in_at' => now(),
            'status'       => 'success',
        ]);
    }
}