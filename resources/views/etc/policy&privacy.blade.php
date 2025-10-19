@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Loka Loka')

@push('head')
    <meta name="description"
        content="Kebijakan Privasi Loka Loka yang menjelaskan cara kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.">
@endpush

@section('content')
    <article class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-roboto-slab text-green-darker">Kebijakan Privasi</h1>
            <p class="text-gray-700 mt-2">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>
        </header>

        <nav class="card p-4 mb-8">
            <h2 class="font-semibold text-green-darker mb-2">Daftar Isi</h2>
            <ul class="list-disc list-inside text-gray-800 space-y-1">
                <li><a href="#informasi-yang-dikumpulkan" class="btn-link">Informasi yang Dikumpulkan</a></li>
                <li><a href="#penggunaan-informasi" class="btn-link">Penggunaan Informasi</a></li>
                <li><a href="#berbagi-informasi" class="btn-link">Berbagi Informasi</a></li>
                <li><a href="#keamanan-data" class="btn-link">Keamanan Data</a></li>
                <li><a href="#hak-pengguna" class="btn-link">Hak Pengguna</a></li>
                <li><a href="#kontak" class="btn-link">Kontak</a></li>
            </ul>
        </nav>

        <section id="informasi-yang-dikumpulkan" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Informasi yang Dikumpulkan</h2>
            <p class="text-gray-800">Kami dapat mengumpulkan data identitas, kontak, dan informasi transaksi saat Anda
                menggunakan layanan kami.</p>
            <ul class="list-disc list-inside text-gray-800 mt-2">
                <li>Data akun: nama, email, nomor telepon.</li>
                <li>Data transaksi: riwayat pesanan, metode pembayaran.</li>
                <li>Data teknis: alamat IP, jenis perangkat, dan data cookie.</li>
            </ul>
        </section>

        <section id="penggunaan-informasi" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Penggunaan Informasi</h2>
            <p class="text-gray-800">Kami menggunakan data untuk menyediakan layanan, memproses pesanan, meningkatkan
                pengalaman pengguna, serta untuk kepentingan keamanan dan kepatuhan.</p>
        </section>

        <section id="berbagi-informasi" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Berbagi Informasi</h2>
            <p class="text-gray-800">Kami dapat membagikan informasi kepada mitra tepercaya untuk pemrosesan pembayaran,
                pengiriman, dan kepatuhan hukum sesuai kebutuhan.</p>
        </section>

        <section id="keamanan-data" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Keamanan Data</h2>
            <p class="text-gray-800">Kami menerapkan langkah‑langkah teknis dan organisasi yang wajar untuk melindungi data
                Anda. Namun, tidak ada metode transmisi yang 100% aman.</p>
        </section>

        <section id="hak-pengguna" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Hak Pengguna</h2>
            <p class="text-gray-800">Anda dapat meminta akses, perbaikan, penghapusan, atau pembatasan pemrosesan data
                pribadi sesuai hukum yang berlaku.</p>
        </section>

        <section id="kontak" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Kontak</h2>
            <p class="text-gray-800">Untuk pertanyaan mengenai privasi, silakan hubungi kami melalui alamat kontak yang
                tercantum di situs.</p>
        </section>
    </article>
@endsection