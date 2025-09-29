<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Users;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Session::has('logged_in') && Session::get('logged_in') === true) {
            $userId = Session::get('user_id');
            $user = Users::find($userId);
            if ($user && $user->role !== $role) {
                return redirect()->route('homepage')->withErrors(['access' => 'Anda tidak memiliki izin untuk mengakses halaman ini.']);
            }
        }
        return $next($request);
    }
}