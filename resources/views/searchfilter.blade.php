@extends('layouts.app')

@section('title', 'Pencarian Produk - Loka Loka')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div class="flex items-center gap-2">
        @if(request()->filled('q'))
            <h2 class="font-roboto text-xl md:text-2xl font-bold text-green-darker">Hasil untuk "{{ request('q') }}"</h2>
        @else
            <h2 class="font-roboto text-xl md:text-2xl font-bold text-green-darker">Semua produk</h2>
        @endif
        @isset($total)
            <span class="text-sm text-gray-600">• {{ number_format($total) }} produk</span>
        @endisset
    </div>

    <div class="flex items-center gap-2">
        <div class="flex flex-wrap gap-2">
            @if(request('category'))
                <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">
                    Kategori:
                    {{ optional($categories->firstWhere('id', (int)request('category')))->name ?? request('category') }}
                </span>
            @endif

            @if(request('location'))
                <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">
                    Lokasi: {{ request('location') }}
                </span>
            @endif

            @if(request('min_price') || request('max_price'))
                <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">
                    Harga:
                    {{ request('min_price') ? 'Rp '.number_format((int)request('min_price'), 0, ',', '.') : '0' }}
                    -
                    {{ request('max_price') ? 'Rp '.number_format((int)request('max_price'), 0, ',', '.') : 'Tak terbatas' }}
                </span>
            @endif

            @if(request('min_rating'))
                <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">
                    Rating: {{ request('min_rating') }}+
                </span>
            @endif
        </div>
        <a href="{{ route('homepage') }}" class="btn btn-secondary">Reset</a>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
@forelse ($products as $product)
    <div class="card p-3 flex flex-col">
        <a href="{{ route('product.show', $product->id) }}" class="block">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text={{ urlencode($product->name) }}';"
                 class="w-full h-40 object-cover rounded-lg">
            <div class="mt-3">
                <h3 class="font-semibold text-green-darker">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Kategori' }}</p>
                <p class="font-bold text-green-800 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
        </a>
        <div class="mt-auto pt-3">
            <button class="btn btn-primary w-full"
                    onclick="addToCart(event, {{ $product->id }})"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                {{ $product->stock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
            </button>
        </div>
    </div>
@empty
    <div class="col-span-full card p-10 text-center">
        <h3 class="font-roboto-slab text-xl font-bold mb-2">Tidak ada hasil</h3>
        <p class="text-gray-600">Coba ubah kata kunci atau filter.</p>
    </div>
@endforelse
</div>

<div class="mt-8">
    {{ $products->appends(request()->query())->links() }}
</div>

@push('scripts')
<script>
async function addToCart(e, productId) {
    e.preventDefault();
    try {
        const res = await fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest' },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        if (res.status === 401) { window.location.href = '{{ route('login') }}'; return; }
        const data = await res.json();
        if (data.error) { alert(data.error); } else { alert('Produk ditambahkan ke keranjang!'); }
    } catch { alert('Terjadi kesalahan.'); }
}
</script>
@endpush
@endsection