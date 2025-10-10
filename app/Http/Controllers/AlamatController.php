<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Users; // Import model Users
use Illuminate\Support\Facades\Redirect;

class AlamatController extends Controller
{
    public function showForm()
    {
        // Cek otorisasi
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return Redirect::route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }
        
        // Ambil data user yang sedang login untuk default value
        $userId = Session::get('user_id');
        $user = Users::find($userId);

        // Jika data user tidak ada, alihkan
        if (!$user) {
            Session::flush();
            return Redirect::route('login')->withErrors(['error' => 'User tidak ditemukan. Silakan login kembali.']);
        }

        // Jika data alamat sudah ada di sesi, gunakan data sesi. Jika tidak, gunakan data user.
        $defaultNama = session('nama_penerima', $user->name ?? '');
        $defaultTelepon = session('telepon_penerima', $user->phone_number ?? '');
        $defaultAlamat = session('alamat_penerima', ''); // Biarkan alamat kosong jika belum diisi

        return view('alamat', compact('defaultNama', 'defaultTelepon', 'defaultAlamat'));
    }

    public function update(Request $request)
    {
        // Cek otorisasi
        if (!Session::has('logged_in') || Session::get('logged_in') !== true) {
            return Redirect::route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        $request->validate([
            'fullName' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20',
            'streetAddress' => 'required|string|max:500',
        ]);
        
        $namaLengkap = $request->input('fullName');
        $nomorTelepon = $request->input('phoneNumber');
        $alamatLengkap = $request->input('streetAddress');

        Session::put([
            'nama_penerima' => $namaLengkap,
            'telepon_penerima' => $nomorTelepon,
            'alamat_penerima' => $alamatLengkap
        ]);

        return Redirect::route('checkout.show')->with('success', 'Alamat pengiriman berhasil diubah.');
    }

    /**
     * Menghapus alamat dari session.
     */
    public function delete(Request $request)
    {
        Session::forget(['nama_penerima', 'telepon_penerima', 'alamat_penerima']);
        return Redirect::route('checkout.show')->with('success', 'Alamat berhasil dihapus dari sesi.');
    }
}