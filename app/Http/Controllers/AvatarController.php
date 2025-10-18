<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AvatarController extends Controller
{
    /**
     * Menyimpan URL avatar ke dalam Session.
     */
    public function update(Request $request)
    {
        // 1. Cek otorisasi
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return Redirect::route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        // 2. Validasi input
        $validated = $request->validate([
            'avatar_url' => 'required|url|max:255',
        ]);
        
        // 3. Simpan URL ke Session
        Session::put('user_avatar', $validated['avatar_url']);

        return Redirect::route('profile')->with('success', 'Avatar berhasil diperbarui (tersimpan sementara).');
    }
}