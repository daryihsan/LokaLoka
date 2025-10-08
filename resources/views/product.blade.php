@extends('layouts.app')

@section('title', $product->name . ' - Loka Loka')

@section('content')
<div class="min-h-screen text-green-darker font-open-sans bg-white">

    <main class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
        <button onclick="history.back()" class="mb-4 text-sm text-gray-600 hover:text-green-darker font-medium">&larr; Kembali ke halaman sebelumnya</button>

        <h2 class="text-3xl font-roboto-slab font-bold text-green-darker mb-6">Detail Produk</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="mb-6">
                    <label class="block font-semibold text-green-darker mb-2">Gambar Produk</label>
                    <img src="{{ $product->image_url }}"
                         onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text={{ urlencode($product->name) }}';"
                         alt="Gambar Produk"
                         class="rounded-lg shadow-md w-full object-cover">
                </div>

                <form id="add-to-cart-form" onsubmit="addToCartFormSubmit(event)">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center gap-4 mb-6">
                        <label for="quantity" class="text-lg font-semibold
                            text-green-darker">Jumlah:</label>
                        
                        <input type="number" id="quantity" name="quantity" value="1"
                            min="1" 
                            max="{{ $product->stock }}"
                            class="w-20 text-center p-2 border border-gray-300
                            rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
                        
                        <span class="text-sm text-gray-500">Stok: {{ $product->stock
                        }}</span>
                    </div>
                    
                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white py-3
                        rounded-lg font-medium transition-colors {{ $product->stock == 0 ? 'opacity-50
                        cursor-not-allowed' : '' }}"
                        
                        {{ $product->stock == 0 ?
                        'disabled' : '' }}>
                        
                        {{ $product->stock == 0 ?
                        'Stok Habis' : 'Tambah ke Keranjang' }}
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                <h1 class="text-4xl font-roboto-slab font-bold text-green-darker mb-4">{{ $product->name }}</h1>

                <div class="pt-4 border-t border-green-light">
                    <label class="block font-semibold text-green-darker mb-2 text-xl">Harga</label>
                    <p class="text-5xl font-bold text-red-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block font-semibold text-green-darker mb-2">Kategori</label>
                    <p class="text-gray-700 font-medium text-lg">{{ $product->category->name ?? 'Tidak Berkategori' }}</p>
                </div>

                <div>
                    <label class="block font-semibold text-green-darker mb-2">Berat</label>
                    <p class="text-gray-700 font-medium text-lg">{{ number_format($product->weight ?? 0, 2) }} kg</p>
                </div>

                <div>
                    <label class="block font-semibold text-green-darker mb-2">Deskripsi</label>
                    <p class="text-gray-700 font-medium leading-relaxed whitespace-pre-wrap">{{ $product->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                </div>
            </div>
        </div>
    </main>

    <div id="toast-notification" class="fixed top-5 right-5 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
        <div id="toast-message" class="font-medium text-green-darker"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Perbaikan: Ganti event listener agar memanggil fungsi baru untuk detail produk
document.getElementById('add-to-cart-form')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // PERBAIKAN: Gunakan fungsi addToCart yang sudah didefinisikan di homepage.blade.php
    // agar logika Auth check & toast seragam. Kita akan re-define fungsi ini di global script.
    // Panggil langsung logika AJAX dari sini, meniru fungsi di homepage.blade.php
    
    const btn = event.currentTarget.querySelector('button[type="submit"]');
    const originalHtml = btn.innerHTML;
    
    // Cek Autentikasi di sisi client untuk UX yang lebih cepat (validasi server tetap ada)
    if (!{{ Session::has('logged_in') ? 'true' : 'false' }}) {
        showToast('error', 'Silakan login terlebih dahulu untuk menambah ke keranjang!');
        setTimeout(() => window.location.href = "{{ route('login') }}", 1200);
        return;
    }

    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');

    try {
        const res = await fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data) // Mengirim product_id dan quantity
        });

        const json = await res.json().catch(() => ({}));
        
        if (res.status === 401) {
            showToast('error', json.error || 'Sesi login tidak terdeteksi. Silakan login kembali.');
            setTimeout(() => window.location.href = "{{ route('login') }}", 1200);
            return;
        }

        if (!res.ok) {
            // Error code 400 atau 500
            throw new Error(json.error || 'Gagal menambahkan produk ke keranjang.');
        }

        showToast('success', json.message || 'Produk berhasil ditambahkan ke keranjang!');

    } catch (err) {
        showToast('error', err.message || 'Terjadi kesalahan saat menghubungi server.');
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = originalHtml;
    }
});

function showToast(type, message) {
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');
    if (type === 'success') {
        toast.style.backgroundColor = '#d1fae5';
        toastMessage.style.color = '#059669';
    } else {
        toast.style.backgroundColor = 'fee2e2';
        toastMessage.style.color = '#dc2626';
    }
    toastMessage.textContent = message;
    toast.classList.remove('translate-x-full');
    setTimeout(() => toast.classList.add('translate-x-full'), 3000);
}
</script>
@endpush