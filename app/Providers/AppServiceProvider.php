<?php

namespace App\Providers;

use App\Database\Query\Grammars\MySqlGrammar;
use App\Models\PersonalAccessToken;
use App\Rules\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
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
        DB::connection()->setQueryGrammar(new MySqlGrammar);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        View::addExtension('md.blade.php', 'blade');

        Password::defaults(
            Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()
        );

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
