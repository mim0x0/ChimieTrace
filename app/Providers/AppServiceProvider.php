<?php

namespace App\Providers;

use Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log($event->user->name . ' logged in');
        });

        Event::listen(Logout::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log($event->user->name . ' logged out');
        });

        Event::listen(Registered::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log($event->user->name . ' registered');
        });
    }
}
