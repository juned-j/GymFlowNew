<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;

use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;
use App\Listeners\LogFailedLogin;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        Login::class => [
            LogUserLogin::class,
        ],

        Logout::class => [
            LogUserLogout::class,
        ],

        Failed::class => [
            LogFailedLogin::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}