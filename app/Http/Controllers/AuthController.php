<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Exception;
use Laravel\Socialite\Facades\Socialite; // BARU
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Session::has('logged_in') && Session::get('logged_in') === true) {
            if (Session::get('user_role') === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('homepage');
        }
        return view('login');
    }

    // Process login
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

        $user = Users::where('email', $request->email)
            ->orWhere('name', $request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {

            // PERBAIKAN: Cek kolom 'approved' (TINYINT)
            if ($user->approved == 0) {
                return back()->withErrors(['login' => 'Akun Anda belum disetujui oleh admin.'])->withInput($request->except('password'));
            }

            // Cek Email Verifikasi (untuk User non-Google yang mendaftar)
            if ($user->email_verified_at === null && !$user->google_id) {
                // Untuk lingkungan non-production, kita bisa biarkan login.
                // Jika ingin memblokir, uncomment baris di bawah:
                // return back()->withErrors(['login' => 'Akun Anda belum diverifikasi. Silakan cek email Anda.'])->withInput($request->except('password'));
            }

            // --- Jika Approved, LANJUT ---
            $userStatus = $user->approved ?? ($user->approved == 1 ? 'approved' : 'pending');

            Session::put('logged_in', true);
            Session::put('user_id', $user->id);
            Session::put('username', $user->name);
            Session::put('user_email', $user->email);
            Session::put('user_role', $user->role);
            Session::put('user_status', $userStatus);


            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang Admin.');
            }
            return redirect()->route('homepage')->with('success', 'Login berhasil! Selamat datang ' . $user->name);
        } else {
            return back()->withErrors(['login' => 'Username/email atau password salah.'])->withInput($request->except('password'));
        }
    }

    // Show registration form
    public function showRegister()
    {
        if (Session::has('logged_in') && Session::get('logged_in') === true) {
            return redirect()->route('homepage');
        }
        return view('register');
    }

    // Process registration
    public function processRegister(Request $request)
    {
        // ... (Validasi tetap sama)
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
            // PERBAIKAN: Set 'approved' = 0 (false) dan 'status' = 'pending' saat registrasi
            $user = Users::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password_hash' => Hash::make($request->password),
                'role' => 'customer',
                'approved' => 0, // DEFAULT: Belum disetujui
                'email_verified_at' => null, // Belum diverifikasi
            ]);

            if ($user) {
                // Jika menggunakan fitur verifikasi email, kirim notifikasi:
                // $user->sendEmailVerificationNotification();
                return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda akan aktif setelah disetujui admin.');
            } else {
                return back()->withErrors(['register' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput($request->except('password', 'password_confirmation'));
            }
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['register' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    // Logout user
    public function logout(Request $request)
    {
        Session::forget(['logged_in', 'user_id', 'username', 'user_email', 'user_role', 'user_status']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    // Show user profile
    public function showProfile()
    {
        // === CHECK AUTH LANGSUNG ===
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }
        // ===========================

        $userId = Session::get('user_id');
        $user = Users::find($userId);

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'User tidak ditemukan. Silakan login kembali.']);
        }

        // Ambil data pesanan user (asumsi relasi sudah benar)
        $orders = $user->orders()->latest()->get();

        $avatars = [
            'https://i.pravatar.cc/150?img=1',
            'https://i.pravatar.cc/150?img=3',
            'https://i.pravatar.cc/150?img=5',
            'https://i.pravatar.cc/150?img=7',
            'https://i.pravatar.cc/150?img=9',
        ];

        return view('profile', compact('user', 'orders', 'avatars'));
    }

    // Process profile update (placeholder)
    public function updateProfile(Request $request)
    {
        // === CHECK AUTH LANGSUNG ===
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login')->withErrors(['access' => 'Unauthorized access.']);
        }
        // ===========================

        $userId = Session::get('user_id');
        $user = Users::find($userId);

        if (!$user) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only('name', 'email', 'phone_number');
        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        Session::put('username', $user->name);
        Session::put('user_email', $user->email);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    // LOGIKA SOCIALITE (SKELETON)
    // =======================================================

    // Redirect ke Google
    public function redirectToGoogle()
    {
        // Jika Socialite terinstal, uncomment baris di bawah:
        // return Socialite::driver('google')->redirect(); 

        // Karena ini skeleton, kita langsung redirect dengan error
        return redirect()->route('login')->withErrors(['google_socialite' => 'Fitur Google Login (Socialite) belum diimplementasikan sepenuhnya. Harap gunakan formulir standar.']);
    }

    // Handle Callback dari Google
    public function handleGoogleCallback(Request $request)
    {
        // Karena ini skeleton, kita langsung redirect dengan error
        /*
        // Code untuk Socialite di sini
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = Users::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (!$user) {
            $user = Users::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password_hash' => Hash::make(Str::random(16)), 
                'role' => 'customer',
                'approved' => 1, // Auto-approved
                'email_verified_at' => now(), // Auto-verified
                'avatar_url' => $googleUser->getAvatar(),
            ]);
        }

        // Loginkan user dan set sesi
        Session::put('logged_in', true);
        Session::put('user_id', $user->id);
        // ... set session lainnya
        return redirect()->route('homepage')->with('success', 'Login berhasil dengan Google!');
        */

        return redirect()->route('login')->withErrors(['google_socialite' => 'Fitur Google Login (Socialite) belum diimplementasikan sepenuhnya. Harap gunakan formulir standar.']);
    }

    // Show user orders page (sudah disatukan di showProfile, ini hanya fallback jika ada rute terpisah)
    public function showOrders()
    {
        // === CHECK AUTH LANGSUNG ===
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        return redirect()->route('profile');
    }
}