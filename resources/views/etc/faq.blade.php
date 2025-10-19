@extends('layouts.app')

@section('title', 'FAQ - Loka Loka')

@push('head')
    <meta name="description"
        content="Pertanyaan yang sering diajukan (FAQ) tentang layanan, pemesanan, pembayaran, dan kebijakan di Loka Loka.">
@endpush

@section('content')
    <section class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-roboto-slab text-green-darker">FAQ (Pertanyaan yang Sering Diajukan)</h1>
            <p class="text-gray-700 mt-2">Temukan jawaban cepat terkait penggunaan Loka Loka.</p>
        </header>

        <div class="space-y-4">
            <details class="card p-4" open>
                <summary class="cursor-pointer font-semibold text-green-darker">Bagaimana cara membuat akun?</summary>
                <div class="mt-2 text-gray-800">
                    Anda dapat mendaftar melalui halaman Register, isi data yang diperlukan, lalu verifikasi jika diminta.
                </div>
            </details>

            <details class="card p-4">
                <summary class="cursor-pointer font-semibold text-green-darker">Metode pembayaran apa saja yang didukung?
                </summary>
                <div class="mt-2 text-gray-800">
                    Kami mendukung berbagai metode pembayaran yang ditampilkan saat checkout. Ikuti instruksi pada halaman
                    pembayaran.
                </div>
            </details>

            <details class="card p-4">
                <summary class="cursor-pointer font-semibold text-green-darker">Apakah saya bisa membatalkan pesanan?
                </summary>
                <div class="mt-2 text-gray-800">
                    Pembatalan mengikuti kebijakan masing-masing produk/penjual. Silakan cek detail pesanan Anda atau
                    hubungi dukungan.
                </div>
            </details>

            <details class="card p-4">
                <summary class="cursor-pointer font-semibold text-green-darker">Bagaimana cara mengubah alamat pengiriman?
                </summary>
                <div class="mt-2 text-gray-800">
                    Masuk ke profil Anda dan perbarui alamat pada bagian Alamat. Perubahan setelah pesanan dibuat mungkin
                    terbatas.
                </div>
            </details>

            <details class="card p-4">
                <summary class="cursor-pointer font-semibold text-green-darker">Saya mengalami kendala. Ke mana harus
                    menghubungi?</summary>
                <div class="mt-2 text-gray-800">
                    Hubungi dukungan pelanggan melalui formulir kontak atau email yang tercantum di footer.
                </div>
            </details>
        </div>
    </section>
@endsection