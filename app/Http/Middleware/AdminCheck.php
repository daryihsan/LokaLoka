<?php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_role') || Session::get('user_role') !== 'admin') {
            return redirect()->route('homepage')
                           ->withErrors(['auth' => 'Unauthorized access']);
        }
        return $next($request);
    }
}