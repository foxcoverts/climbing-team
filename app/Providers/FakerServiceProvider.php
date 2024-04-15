<?php

namespace App\Providers;

use App\Faker\PhoneNumber;
use App\Faker\Scouts;
use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $abstract = \Faker\Generator::class.':en_GB';

        $this->app->singleton($abstract, function () {
            $faker = Factory::create();
            $faker->addProvider(new PhoneNumber($faker));
            $faker->addProvider(new Scouts($faker));

            return $faker;
        });
    }
}
