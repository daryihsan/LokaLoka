@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - Loka Loka')

@push('head')
<meta name="description" content="Syarat dan Ketentuan penggunaan layanan Loka Loka. Harap baca dengan saksama sebelum menggunakan layanan kami.">
@endpush

@section('content')
    <article class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-roboto-slab text-green-darker">Syarat & Ketentuan</h1>
            <p class="text-gray-700 mt-2">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>
        </header>

        <nav class="card p-4 mb-8">
            <h2 class="font-semibold text-green-darker mb-2">Daftar Isi</h2>
            <ul class="list-disc list-inside text-gray-800 space-y-1">
                <li><a href="#penerimaan-syarat" class="btn-link">Penerimaan Syarat</a></li>
                <li><a href="#perubahan" class="btn-link">Perubahan</a></li>
                <li><a href="#penggunaan-layanan" class="btn-link">Penggunaan Layanan</a></li>
                <li><a href="#pembayaran" class="btn-link">Pembayaran</a></li>
                <li><a href="#batasan-tanggung-jawab" class="btn-link">Batasan Tanggung Jawab</a></li>
                <li><a href="#hukum-yang-berlaku" class="btn-link">Hukum yang Berlaku</a></li>
                <li><a href="#kontak" class="btn-link">Kontak</a></li>
            </ul>
        </nav>

        <section id="penerimaan-syarat" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Penerimaan Syarat</h2>
            <p class="text-gray-800">Dengan menggunakan layanan Loka Loka, Anda menyetujui Syarat & Ketentuan ini.</p>
        </section>

        <section id="perubahan" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Perubahan</h2>
            <p class="text-gray-800">Kami dapat memperbarui Syarat & Ketentuan dari waktu ke waktu. Versi terbaru akan dipublikasikan di halaman ini.</p>
        </section>

        <section id="penggunaan-layanan" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Penggunaan Layanan</h2>
            <p class="text-gray-800">Anda setuju untuk menggunakan layanan sesuai hukum yang berlaku, tidak menyalahgunakan fitur, dan menjaga keamanan akun.</p>
        </section>

        <section id="pembayaran" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Pembayaran</h2>
            <p class="text-gray-800">Ketentuan pembayaran mengikuti metode yang tersedia dan kebijakan pengembalian dana yang relevan.</p>
        </section>

        <section id="batasan-tanggung-jawab" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Batasan Tanggung Jawab</h2>
            <p class="text-gray-800">Loka Loka tidak bertanggung jawab atas kerugian tidak langsung atau konsekuensial yang timbul dari penggunaan layanan.</p>
        </section>

        <section id="hukum-yang-berlaku" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Hukum yang Berlaku</h2>
            <p class="text-gray-800">Syarat ini diatur oleh hukum yang berlaku di yurisdiksi kami beroperasi.</p>
        </section>

        <section id="kontak" class="mb-6">
            <h2 class="text-2xl font-roboto-slab text-green-darker mb-2">Kontak</h2>
            <p class="text-gray-800">Untuk pertanyaan terkait Syarat & Ketentuan, silakan hubungi kami melalui informasi kontak yang tersedia.</p>
        </section>
    </article>
@endsection