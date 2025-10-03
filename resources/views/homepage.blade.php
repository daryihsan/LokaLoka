@extends('layouts.app')

@section('title', 'Loka Loka - Marketplace Produk Lokal')

@push('head')
<style>
    .font-roboto-slab { font-family: 'Roboto Slab', serif; }
    .font-roboto { font-family: 'Roboto', sans-serif; }
    .font-open-sans { font-family: 'Open Sans', sans-serif; }

    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.6s ease-out forwards; }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <section class="p-6">
        <div class="flex items-center gap-3 mb-6">
            <h2 class="font-roboto text-2xl font-bold text-green-darker">Produk Terlaris</h2>
            <span class="text-2xl">🔥</span>
        </div>

        <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" id="hot-products">
            @forelse ($hotProducts as $i => $product)
                <div class="min-w-[220px] bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border hover:-translate-y-1 opacity-0 animate-fade-in-left"
                     style="animation-delay: {{ $i * 100 }}ms">
                    <a href="{{ route('product.show', $product->id) }}" class="block">
                        <div class="p-0">
                            <div class="relative">
                                <div class="h-32 w-full rounded-t-2xl relative overflow-hidden bg-gray-100">
                                    <img src="{{ $product->image_url }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text={{ urlencode($product->name) }}';" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @if($product->discount_percent ?? null)
                                    <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold">-{{ $product->discount_percent }}%</div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-open-sans font-semibold text-green-darker mb-2 line-clamp-2">{{ $product->name }}</h3>

                                    <div class="flex items-center gap-1 mb-2">
                                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-xs text-gray-600">{{ number_format($product->rating ?? 4.5, 1) }}</span>
                                        <span class="text-xs text-gray-500">• Terjual {{ number_format($product->total_sold ?? 0) }}+</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-green-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <button onclick="addToCart(event, {{ $product->id }})" class="bg-primary hover:bg-primary-dark text-white rounded-full p-2" title="Tambah ke keranjang">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-gray-600 py-4">Tidak ada produk terlaris saat ini.</p>
            @endforelse
        </div>
    </section>

    <section class="p-6">
        <h2 class="font-roboto text-2xl font-bold text-green-darker mb-6">Jelajahi Kategori</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4" id="categories">
            @forelse ($categories as $i => $category)
            <a href="{{ route('searchfilter', ['category' => $category->id]) }}" class="block">
                <div class="flex flex-col items-center justify-center bg-white rounded-2xl p-6 border-2 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer group opacity-0 animate-fade-in-up"
                     style="animation-delay: {{ $i * 50 }}ms; border-color: #C2C5AA;">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200"
                         style="background-color: #C2C5AA20; border: 2px solid #A4AC86;">
                        <span class="text-2xl">🛍️</span>
                    </div>
                    <p class="font-open-sans text-sm font-semibold text-green-darker text-center">{{ $category->name }}</p>
                </div>
            </a>
            @empty
                <p class="text-gray-600">Tidak ada kategori ditemukan.</p>
            @endforelse
        </div>
    </section>

    <section class="p-6">
        <h2 class="font-roboto text-2xl font-bold text-green-darker mb-6">Rekomendasi Untukmu</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="recommended-products">
            @forelse ($recommendedProducts as $i => $product)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border hover:-translate-y-1 opacity-0 animate-fade-in-up"
                     style="animation-delay: {{ $i * 50 }}ms">
                    <a href="{{ route('product.show', $product->id) }}" class="block">
                        <div class="p-0">
                            <div class="relative">
                                <div class="h-40 w-full rounded-t-2xl relative overflow-hidden bg-gray-100">
                                    <img src="{{ $product->image_url }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text={{ urlencode($product->name) }}';" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-open-sans font-semibold text-green-darker mb-2 line-clamp-2">{{ $product->name }}</h3>

                                    <div class="flex items-center gap-1 mb-2">
                                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-xs text-gray-600">{{ number_format($product->rating ?? 4.0, 1) }}</span>
                                        <span class="text-xs text-gray-500">• Stok: {{ $product->stock }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-green-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <button onclick="addToCart(event, {{ $product->id }})" class="bg-primary hover:bg-primary-dark text-white rounded-full p-2" title="Tambah ke keranjang">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-gray-600">Tidak ada produk rekomendasi saat ini.</p>
            @endforelse
        </div>
    </section>
</div>

<div id="toast-notification" class="fixed top-5 right-5 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
    <div id="toast-message" class="font-medium text-green-darker"></div>
</div>
@endsection

@push('scripts')
<script>
    function addToCart(event, productId) {
        event.preventDefault();

        @guest
            showToast('error', 'Silakan login terlebih dahulu untuk menambah ke keranjang!');
            setTimeout(() => window.location.href = '{{ route('login') }}', 1200);
            return;
        @endguest

        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(async (response) => {
            const data = await response.json().catch(() => ({}));
            if (response.status === 401) {
                showToast('error', 'Silakan login terlebih dahulu untuk menambah ke keranjang!');
                setTimeout(() => window.location.href = '{{ route('login') }}', 1200);
                return;
            }
            if (!response.ok) throw new Error(data?.error || 'Gagal menambahkan produk ke keranjang.');
            showToast('success', data.message || 'Produk berhasil ditambahkan ke keranjang!');
        })
        .catch((err) => showToast('error', err.message || 'Terjadi kesalahan saat menghubungi server.'))
        .finally(() => {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = originalHtml;
        });
    }

    function showToast(type, message) {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        if (type === 'success') { toast.style.backgroundColor = '#d1fae5'; toastMessage.style.color = '#059669'; }
        else { toast.style.backgroundColor = '#fee2e2'; toastMessage.style.color = '#dc2626'; }
        toastMessage.textContent = message;
        toast.classList.remove('translate-x-full');
        setTimeout(() => toast.classList.add('translate-x-full'), 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var successAlert = document.querySelector('.bg-green-100');
            if (successAlert) {
                successAlert.style.opacity = '0';
                setTimeout(function() {
                    successAlert.parentNode?.removeChild(successAlert);
                }, 500);
            }
        }, 5000);
    });
</script>
@endpush