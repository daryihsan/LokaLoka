<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // Redirect to homepage if already logged in
        if (Session::has('logged_in') && Session::get('logged_in') === true) {
            return redirect()->route('homepage');
        }
        
        return view('login');
    }

    /**
     * Process login
     */
    public function processLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Username atau email harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Find user by email or name
        $user = Users::where('email', $request->email)
                       ->orWhere('name', $request->email)
                       ->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            // Login successful - start session
            Session::put('logged_in', true);
            Session::put('user_id', $user->id);
            Session::put('username', $user->name);
            Session::put('user_email', $user->email);
            Session::put('user_role', $user->role);
            
            // Regenerate session ID for security
            $request->session()->regenerate();
            
            return redirect()->route('homepage')->with('success', 'Login berhasil! Selamat datang ' . $user->name);
        } else {
            return back()->withErrors(['login' => 'Username/email atau password salah.'])->withInput($request->except('password'));
        }
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        // Redirect to homepage if already logged in
        if (Session::has('logged_in') && Session::get('logged_in') === true) {
            return redirect()->route('homepage');
        }
        
        return view('register');
    }

    /**
     * Process registration
     */
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap harus diisi.',
            'name.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone_number.required' => 'Nomor telepon harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            // Create new user
            $user = Users::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password_hash' => Hash::make($request->password),
                'role' => 'customer', // Default role
            ]);

            if ($user) {
                return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
            } else {
                return back()->withErrors(['register' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput($request->except('password', 'password_confirmation'));
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['register' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Show homepage (dashboard)
     */
    public function showHomepage()
    {
        // Check if user is logged in
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        // Get user data from database
        $userId = Session::get('user_id');
        $user = Users::find($userId);
        
        if (!$user) {
            // If user not found, logout and redirect to login
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'User tidak ditemukan. Silakan login kembali.']);
        }

        return view('homepage', compact('user'));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Clear all session data
        Session::flush();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    /**
     * Show user profile
     */
    public function showProfile()
    {
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login');
        }

        $user = Users::find(Session::get('user_id'));
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'User tidak ditemukan.']);
        }
        
        return view('profile', compact('user'));
    }

    /**
     * Show user orders
     */
    public function showOrders()
    {
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        $user = Users::find($userId);
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'User tidak ditemukan.']);
        }
        
        // You can add orders logic here when you have Order model
        // $orders = Orders::where('user_id', $userId)->get();
        
        return view('orders', ['orders' => [], 'user' => $user]);
    }

    /**
     * Show checkout page
     * INI FUNGSI YANG DITAMBAHKAN
     */
    public function showCheckout()
    {
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login');
        }

        $user = Users::find(Session::get('user_id'));
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'User tidak ditemukan.']);
        }
        
        return view('checkout', compact('user'));
    }
}