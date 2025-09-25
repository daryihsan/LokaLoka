<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlamatController extends Controller
{
    // Fungsi untuk menampilkan form
    public function showForm()
    {
        return view('alamat');
    }

    // Fungsi untuk MENANGKAP dan MENYIMPAN data
    public function update(Request $request)
    {
        // 1. Ambil data dari form
        $namaLengkap = $request->input('fullName');
        $nomorTelepon = $request->input('phoneNumber');
        $alamatLengkap = $request->input('streetAddress');

        // 2. Simpan data ke dalam session
        session([
            'nama_penerima' => $namaLengkap,
            'telepon_penerima' => $nomorTelepon,
            'alamat_penerima' => $alamatLengkap
        ]);

        // 3. Kembalikan pengguna ke halaman checkout
        return redirect('/checkout');
    }
}