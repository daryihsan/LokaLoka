@extends('layouts.app')

@section('title', 'Ubah Alamat | Loka Loka')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('checkout.show') }}" class="btn btn-secondary">Kembali</a>
    <div class="flex items-center justify-between gap-3 mb-6">
        <h1 class="font-roboto-slab text-2xl text-green-darker">Ubah Alamat Pengiriman</h1>
        <div></div>
    </div>

    <div class="card p-6">
        {{-- Form Update Alamat (Inputs saja) --}}
        <form id="formUpdateAlamat" action="{{ route('alamat.update') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="fullName" class="block text-sm font-semibold text-green-darker mb-1">Nama Lengkap</label>
                <input
                    type="text"
                    id="fullName"
                    name="fullName"
                    value="{{ old('fullName', $defaultNama) }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus-ring"
                    placeholder="Nama penerima"
                >
            </div>

            <div>
                <label for="phoneNumber" class="block text-sm font-semibold text-green-darker mb-1">Nomor Telepon</label>
                <input
                    type="tel"
                    id="phoneNumber"
                    name="phoneNumber"
                    value="{{ old('phoneNumber', $defaultTelepon) }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus-ring"
                    placeholder="Contoh: 0812xxxxxxx"
                >
            </div>

            <div>
                <label for="streetAddress" class="block text-sm font-semibold text-green-darker mb-1">Alamat Lengkap</label>
                <textarea
                    id="streetAddress"
                    name="streetAddress"
                    rows="4"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus-ring"
                    placeholder="Nama jalan, nomor, RT/RW, kelurahan, kecamatan, kota/kabupaten, provinsi, kode pos"
                >{{ old('streetAddress', $defaultAlamat) }}</textarea>
            </div>
        </form>

        {{-- Baris Aksi (sebaris): Batal, Simpan, Hapus --}}
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('checkout.show') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" form="formUpdateAlamat" class="btn btn-primary">Simpan Perubahan</button>
            </div>

            {{-- Form Hapus Alamat --}}
            <form
                action="{{ route('alamat.delete') }}"
                method="POST"
                onsubmit="return confirm('Hapus alamat pengiriman? Data alamat di sesi akan dihapus.');"
                class="inline-block"
            >
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition"
                >
                    Hapus Alamat
                </button>
            </form>
        </div>
    </div>
</div>
@endsection