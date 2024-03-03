<?php

namespace App\Providers;

use App\Database\Query\Grammars\MySqlGrammar;
use App\Models\PersonalAccessToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\Sanctum;

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
    }
}
