<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Request;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        UserLoginLog::create([
            'user_id'        => optional($event->user)->id,
            'email'          => $event->credentials['email'] ?? null,
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
            'logged_in_at'   => now(),
            'status'         => 'failed',
            'failure_reason' => 'Invalid credentials',
        ]);
    }
}