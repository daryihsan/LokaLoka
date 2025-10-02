<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}

protected $routeMiddleware = [
    'auth.check' => \App\Http\Middleware\AuthCheck::class,
    'auth.admin' => \App\Http\Middleware\AdminCheck::class,
];