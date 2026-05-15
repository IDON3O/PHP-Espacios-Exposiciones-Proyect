<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Venue;
use App\Observers\EventObserver;
use App\Observers\VenueObserver;
use Illuminate\Support\Facades\URL;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Event::observe(EventObserver::class);
        Venue::observe(VenueObserver::class);
    }
}
