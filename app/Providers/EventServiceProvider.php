<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\LoginEvent' => ['App\Listeners\LoginListener'],
        'App\Events\EventCreationEvent' => ['App\Listeners\EventCreationListener'],
        'App\Events\SendMail'   => ['App\Listeners\Email'],
        'Laravel\Passport\Events\AccessTokenCreated' => ['App\Listeners\RevokeExistingTokens'],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
