<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFromParam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $param, string $key = 'id'): Response
    {
        if (Auth::onceUsingId($request->route($param)->$key)) {
            return $next($request);
        }
        throw new AuthenticationException('Unauthenticated.', [], false);
    }
}
