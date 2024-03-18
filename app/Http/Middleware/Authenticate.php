<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Set the current user based on the given param.
     *
     * @see App\Http\Middleware\AuthenticateFromParam
     * @param string $param
     * @return string
     */
    public static function fromParam(string $param): string
    {
        return AuthenticateFromParam::class . ':' . $param;
    }
}
