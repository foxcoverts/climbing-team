<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Policies\ActivityPolicy;
use App\Rules\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        Model::shouldBeStrict();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
        View::addExtension('md.blade.php', 'blade');

        if (App::isProduction()) {
            Password::defaults(
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()
            );
        }

        Mail::extend('sendgrid', function () {
            return (new SendgridTransportFactory)->create(
                new Dsn(
                    'sendgrid+api',
                    'default',
                    config('services.sendgrid.key')
                )
            );
        });
    }
}
