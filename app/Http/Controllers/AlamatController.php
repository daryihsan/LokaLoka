<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function showForm()
    {
        return view('alamat');
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
}