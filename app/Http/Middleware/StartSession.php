<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Session\Middleware\StartSession as IlluminateStartSession;

class StartSession extends IlluminateStartSession
{
    /**
     * Store the current URL for the request if necessary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @return void
     */
    protected function storeCurrentUrl(Request $request, $session)
    {
        if (
            $request->isMethod('GET') &&
            $request->route() instanceof Route &&
            !$request->route()->named('api.*') &&
            !$request->ajax() &&
            !$request->prefetch() &&
            !$request->isPrecognitive()
        ) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }
}
