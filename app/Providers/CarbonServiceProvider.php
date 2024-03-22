<?php

namespace App\Providers;

use Carbon\Factory;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function (): Factory {
            $timezone = config('app.timezone', 'UTC');
            if (auth()->check()) {
                $timezone = auth()->user()->timezone;
            }

            return new Factory([
                'locale' => config('app.locale', 'en_GB'),
                'timezone' => $timezone,
            ]);
        });
    }
}
