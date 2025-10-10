<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Users; // Import model Users

class AlamatController extends Controller
{
    public function showForm()
    {
        // Ambil data user yang sedang login untuk default value
        $userId = Session::get('user_id');
        $user = Users::find($userId); 

        // Jika data alamat sudah ada di sesi, gunakan data sesi. Jika tidak, gunakan data user.
        $defaultNama = session('nama_penerima', $user->name ?? '');
        $defaultTelepon = session('telepon_penerima', $user->phone_number ?? '');
        $defaultAlamat = session('alamat_penerima', ''); // Biarkan alamat kosong jika belum diisi

        return view('alamat', compact('defaultNama', 'defaultTelepon', 'defaultAlamat'));
    }

    public function update(Request $request)
    {
        $namaLengkap = $request->input('fullName');
        $nomorTelepon = $request->input('phoneNumber');
        $alamatLengkap = $request->input('streetAddress');

        session([
            'nama_penerima' => $namaLengkap,
            'telepon_penerima' => $nomorTelepon,
            'alamat_penerima' => $alamatLengkap
        ]);

        return redirect('/checkout');
    }

    //hapus alamat dari session
    public function delete(Request $request)
    {
        session()->forget(['nama_penerima', 'telepon_penerima', 'alamat_penerima']);
        return redirect()->route('checkout.show')->with('success', 'Alamat berhasil dihapus.'); 
    }
}