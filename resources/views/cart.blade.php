@extends('layouts.app')

@section('title', 'Keranjang Belanja - Loka Loka')

@section('content')
<div class="mb-8">
    <h1 class="font-roboto-slab text-3xl font-bold text-green-darker mb-2">Keranjang Belanja</h1>
    <p class="text-gray-600">Kelola produk dalam keranjang Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 card">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all" class="w-5 h-5">
                <label for="select-all" class="text-sm text-gray-700">Pilih semua</label>
            </div>
            <div class="text-sm text-gray-600">Total item: <span id="total-items">0</span></div>
        </div>
        <div id="empty-state" class="p-10 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Keranjang Kosong</h3>
            <a href="{{ route('homepage') }}" class="btn btn-primary mt-2">Mulai Berbelanja</a>
        </div>
        <div id="cart-items-list" class="hidden p-6 space-y-4"></div>
    </div>

    <aside class="card p-6 h-max sticky top-24">
        <h3 class="font-roboto text-xl font-bold text-green-darker mb-6">Ringkasan Belanja</h3>
        <div id="summary-content" class="space-y-3 mb-6">
            <div class="text-center text-gray-500 py-8">Pilih produk untuk melihat ringkasan</div>
        </div>
        <div id="checkout-section" class="hidden">
            <button onclick="proceedToCheckout()" class="btn btn-primary w-full" id="checkout-btn" disabled>
                Lanjut ke Checkout
            </button>
            <p class="text-xs text-gray-500 mt-3 text-center">Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami</p>
        </div>
    </aside>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 hidden alert"></div>

@push('scripts')
<script>
// Inisialisasi, fetch items, render (pakai implementasi Anda yang sudah ada)
</script>
@endpush
@endsection