<?php

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Carbon\Factory;

if (! function_exists('localDate')) {
    function localDate(Carbon|string $date, CarbonTimeZone|string|null $timezone = null): Carbon
    {
        if (is_null($timezone)) {
            $timezone = config('app.timezone', 'UTC');
        }
        if (auth()->check() && ! is_null(auth()->user()->timezone)) {
            $timezone = auth()->user()->timezone;
        }

        $factory = new Factory([
            'locale' => config('app.locale', 'en_GB'),
            'timezone' => $timezone,
        ]);

        return $factory->make($date);
    }
}
