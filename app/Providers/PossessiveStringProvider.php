<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class PossessiveStringProvider extends ServiceProvider
{
    public function boot(): void
    {
        Str::macro('possessive', function ($string) {
            if (empty($string)) {
                return $string;
            }

            // Don't add 's after words ending in s if it is a plural of something.
            //
            // There are plenty of false positives here as most things ending in 's' are
            // assumed to be plurals, but it will still catch some words.
            if (Str::endsWith($string, ['s', 'S']) && Str::plural($string) == $string) {
                return $string.'\'';
            }

            return $string.'\'s';
        });
    }
}
