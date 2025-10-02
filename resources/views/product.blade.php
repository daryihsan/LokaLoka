@extends('layouts.app')

@section('title', $product->name . ' - Loka Loka')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="space-y-6">
        <div>
            <label class="block font-semibold text-green-darker mb-2">Gambar Produk</label>
            <img src="{{ $product->image_url }}"
                 onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text={{ urlencode($product->name) }}';"
                 alt="Gambar Produk"
                 class="rounded-lg shadow w-full object-cover">
        </div>

        <form id="add-to-cart-form" class="space-y-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="flex items-center gap-4">
                <label for="quantity" class="text-lg font-semibold text-green-darker">Jumlah:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                       class="w-24 text-center p-2 border border-gray-300 rounded-lg focus-ring" />
                <span class="text-sm text-gray-500">Stok: {{ $product->stock }}</span>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="btn btn-primary flex-1 {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                    {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                </button>
                <a href="{{ route('cart.show') }}" class="btn btn-secondary">Buka Keranjang</a>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <h1 class="text-3xl font-roboto-slab font-bold text-green-darker">{{ $product->name }}</h1>
        <div class="pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">Kategori: {{ $product->category->name ?? 'N/A' }}</p>
            <p class="text-2xl font-bold text-green-800 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <div class="mt-4 text-gray-700 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-notification" class="fixed top-5 right-5 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
    <div id="toast-message" class="font-medium text-green-darker"></div>
</div>

@push('scripts')
<script>
document.getElementById('add-to-cart-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const payload = Object.fromEntries(fd.entries());
    try {
        const res = await fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With':'XMLHttpRequest' },
            body: JSON.stringify(payload)
        });
        if (res.status === 401) { window.location.href = '{{ route('login') }}'; return; }
        const data = await res.json();
        if (data.error) { showToast('error', data.error); } else { showToast('success', data.message || 'Produk ditambahkan!'); }
    } catch (err) { showToast('error', 'Terjadi kesalahan'); }
});

function showToast(type, message) {
    const toast = document.getElementById('toast-notification');
    const msg = document.getElementById('toast-message');
    if (type === 'success') { toast.style.backgroundColor = '#d1fae5'; msg.style.color = '#059669'; }
    else { toast.style.backgroundColor = '#fee2e2'; msg.style.color = '#dc2626'; }
    msg.textContent = message;
    toast.classList.remove('translate-x-full');
    setTimeout(() => toast.classList.add('translate-x-full'), 2500);
}
</script>
@endpush
@endsection