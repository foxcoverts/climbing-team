<?php

use Carbon\Carbon;
use Carbon\Factory;

if (!function_exists('localDate')) {
    function localDate(Carbon|string $date): Carbon
    {
        return app()->make(Factory::class)->make($date);
    }
}
