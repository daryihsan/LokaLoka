<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Users;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('logged_in') && Session::get('logged_in') === true) {
            $userId = Session::get('user_id');
            $user = Users::find($userId);
            if ($user && !$user->approved) {
                return redirect()->route('login')->withErrors(['access' => 'Akun Anda belum diapprove oleh admin.']);
            }
        }
        return $next($request);
    }
}