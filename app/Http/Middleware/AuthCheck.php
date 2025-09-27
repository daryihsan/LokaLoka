<?php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('logged_in')) {
            return redirect()->route('login')
                           ->withErrors(['auth' => 'Please login first']);
        }
        return $next($request);
    }
}