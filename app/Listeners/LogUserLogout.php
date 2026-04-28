<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserLoginLog;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        UserLoginLog::where('user_id', $event->user->id)
            ->whereNull('logged_out_at')
            ->latest()
            ->first()?->update([
                'logged_out_at' => now(),
            ]);
    }
}