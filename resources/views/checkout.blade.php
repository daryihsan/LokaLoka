@extends('layouts.app')

@section('title', 'Checkout Loka Loka')

@push('head')
<style>
    .checkout-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .checkout-container {
            grid-template-columns: 2fr 1fr;
        }
    }

    .card {
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-header h2 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--text-darker);
    }

    .option-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .option-group input[type="radio"] {
        display: none;
    }

    .option-group label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .option-group input[type="radio"]:checked + label {
        border-color: var(--brand-primary);
        background: #f7f9f2;
        box-shadow: 0 0 0 1px var(--brand-primary);
    }

    .option-details strong {
        font-weight: 700;
        color: var(--text-darker);
    }

    .option-details span {
        display: block;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .option-price {
        font-weight: 700;
        color: #ef4444;
    }

    /* Order Summary */
    .order-summary {
        position: sticky;
        top: 2rem;
        align-self: flex-start;
    }

    .product-list {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 1rem;
        margin-bottom: 1rem;
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .product-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .product-details {
        flex-grow: 1;
        font-size: 0.875rem;
    }

    .product-details h3 {
        font-weight: 600;
        margin-bottom: 0.125rem;
    }

    .product-details p {
        color: #6b7280;
    }

    .product-quantity input {
        width: 50px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        padding: 0.25rem;
        font-size: 0.875rem;
    }

    .product-price {
        font-weight: 600;
        font-size: 0.875rem;
        min-width: 80px;
        text-align: right;
        color: #10b981;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .summary-total {
        border-top: 2px solid var(--brand-primary);
        padding-top: 1rem;
        margin-top: 1rem;
        font-size: 1.25rem;
        font-weight: bold;
    }

    .shipping-address {
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

<button class="btn btn-secondary mb-4" onclick="history.back()">
    &larr; Kembali
</button>

<div class="checkout-container">

    <div class="main-content space-y-8">
        {{-- CARD ALAMAT --}}
        <div class="card">
            <div class="card-header">
                <span class="icon text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </span>
                <h2>Alamat Pengiriman</h2>
                {{-- PERBAIKAN: Tombol ubah alamat yang benar --}}
                <button class="btn btn-link ml-auto text-sm" onclick="location.href='{{ route('alamat.form') }}'">Ubah Alamat</button>
            </div>
            <div class="shipping-address">
                {{-- Data dari Session/User --}}
                <strong>{{ session('nama_penerima', $user->name ?? 'Pengguna') }}</strong>
                <p class="text-gray-600">{{ session('telepon_penerima', $user->phone_number ?? '(Nomor tidak tersedia)') }}</p>
                <p class="text-gray-600">{{ session('alamat_penerima', 'Anda belum mengisi alamat pengiriman.') }}</p>
            </div>
        </div>

        {{-- CARD METODE PENGIRIMAN --}}
        <div class="card">
            <div class="card-header">
                <span class="icon text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L11.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </span>
                <h2>Metode Pengiriman</h2>
            </div>
            <div class="option-group">
                {{-- Pilihan Pengiriman --}}
                <input type="radio" class="shipping-option" id="standard" name="shipping" data-cost="15000" checked>
                <label for="standard">
                    <div class="option-details"><strong>Reguler</strong><span>Estimasi 2-3 hari kerja</span></div>
                    <span class="option-price">Rp 15.000</span>
                </label>

                <input type="radio" class="shipping-option" id="express" name="shipping" data-cost="25000">
                <label for="express">
                    <div class="option-details"><strong>Express</strong><span>Estimasi 1 hari kerja</span></div>
                    <span class="option-price">Rp 25.000</span>
                </label>
            </div>
        </div>

        {{-- CARD METODE PEMBAYARAN --}}
        <div class="card">
            <div class="card-header">
                <span class="icon text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="10" rx="2" ry="2"></rect><path d="M2 12h20"/></svg>
                </span>
                <h2>Metode Pembayaran</h2>
            </div>
            <div class="option-group">
                {{-- Opsi Pembayaran QRIS --}}
                <input type="radio" id="qris" name="payment" data-method="qris" checked>
                <label for="qris">
                    <div class="option-details"><strong>QRIS</strong><span>Bayar dengan QR code (Gopay, OVO, Dana, dll)</span></div>
                </label>
                
                {{-- Opsi Pembayaran Transfer Bank --}}
                <input type="radio" id="transfer_bank" name="payment" data-method="transfer_bank">
                <label for="transfer_bank">
                    <div class="option-details"><strong>Transfer Bank</strong><span>Pembayaran via Bank Mandiri/BCA</span></div>
                </label>

                {{-- Opsi Pembayaran COD (Cash on Delivery) --}}
                <input type="radio" id="cod" name="payment" data-method="cod">
                <label for="cod">
                    <div class="option-details"><strong>COD (Bayar di Tempat)</strong><span>Pembayaran tunai saat barang diterima</span></div>
                </label>
            </div>
        </div>

    </div>

    {{-- RINGKASAN PESANAN --}}
    <div class="order-summary">
        <div class="card p-6 sticky top-8">
            <h2 class="font-roboto-slab text-xl font-bold mb-4">Ringkasan Pesanan</h2>
            
            <div class="product-list space-y-2 mb-4">
                {{-- Konten dari JS/sessionStorage --}}
            </div>

            <div class="summary-calculation border-t pt-4">
                <div class="summary-row text-gray-700">
                    <span>Subtotal Produk</span>
                    <span id="summary-subtotal">Rp 0</span>
                </div>
                <div class="summary-row text-gray-700">
                    <span>Ongkos Kirim</span>
                    <span id="summary-shipping">Rp 0</span>
                </div>
                
                <div class="summary-row summary-total text-green-darker">
                    <span>Total Pembayaran</span>
                    <span id="summary-total" class="text-red-600">Rp 0</span>
                </div>
            </div>

            <button class="btn btn-primary w-full mt-6" onclick="processCheckout()">
                Bayar Sekarang
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka).replace('Rp', 'Rp ');
    }

    // Variabel global untuk menyimpan data item saat ini
    let currentCheckoutItems = [];
    
    // --- CALCULATIONS ---
    function recalculateTotals() {
        let subtotal = 0;
        const shippingOption = document.querySelector('.shipping-option:checked');
        const shippingCost = parseFloat(shippingOption?.dataset.cost || 0);

        // Kumpulkan data dari DOM yang baru di-render
        const itemElements = document.querySelectorAll('.product-item');
        
        currentCheckoutItems = []; // Reset item data
        
        itemElements.forEach(itemEl => {
            const price = parseFloat(itemEl.dataset.price);
            const itemId = parseInt(itemEl.dataset.itemId);
            const productId = parseInt(itemEl.dataset.productId);
            const name = itemEl.dataset.name;
            
            const quantityInput = itemEl.querySelector(".quantity-input");
            let quantity = parseInt(quantityInput.value) || 1; 
            
            // Batasi kuantitas minimal 1 (meskipun idealnya sudah divalidasi di keranjang)
            if (quantity < 1) {
                quantity = 1;
                quantityInput.value = 1;
            }

            const lineTotal = price * quantity;

            // Update subtotal per item di view
            itemEl.querySelector('.product-price').innerText = formatRupiah(lineTotal);

            subtotal += lineTotal;
            
            // Simpan data item yang sudah diupdate kuantitasnya
            currentCheckoutItems.push({
                id: itemId,
                product_id: productId,
                name: name,
                price: price,
                quantity: quantity
            });
        });

        const total = subtotal + shippingCost;

        document.getElementById('summary-subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('summary-shipping').innerText = formatRupiah(shippingCost);
        document.getElementById('summary-total').innerText = formatRupiah(total);
        
        // Update sessionStorage dengan kuantitas terbaru (Penting untuk konsistensi)
        sessionStorage.setItem('checkoutItems', JSON.stringify(currentCheckoutItems));
    }
    
    // --- EVENT LISTENERS & INIT ---

    document.addEventListener('DOMContentLoaded', function() {
        const productListContainer = document.querySelector('.product-list');
        const itemsFromCart = JSON.parse(sessionStorage.getItem('checkoutItems'));
        
        productListContainer.innerHTML = '';

        if (itemsFromCart && itemsFromCart.length > 0) {
            itemsFromCart.forEach(item => {
                const productItemHTML = `
                    <div class="product-item" data-price="${item.price}"
                        data-product-id="${item.product_id}" 
                        data-item-id="${item.id}"
                        data-name="${item.name}">
                        
                        <img src="${item.image_url || 'https://placehold.co/50x50/e3d8c2/5c6641?text=P'}" alt="${item.name}">
                        
                        <div class="product-details">
                            <h3>${item.name}</h3>
                            <p>${formatRupiah(item.price)}</p>
                        </div>
                        
                        <div class="product-quantity">
                            <input type="number" class="quantity-input"
                                value="${item.quantity}" min="1">
                        </div>
                        
                        <div class="product-price">${formatRupiah(item.price * item.quantity)}</div>
                    </div>
                `;
                productListContainer.insertAdjacentHTML('beforeend', productItemHTML);
            });
        } else {
            productListContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 1rem;">Tidak ada item untuk di-checkout. <a href="{{ route('cart.show') }}" class="text-primary hover:underline">Kembali ke Keranjang</a></p>';
            document.querySelector('.checkout-button').disabled = true;
            return; 
        }

        setupEventListeners();
        recalculateTotals(); // Hitung total awal
    });

    function setupEventListeners() {
        const shippingOptions = document.querySelectorAll('.shipping-option');
        const quantityInputs = document.querySelectorAll('.quantity-input');

        // Event listener untuk pengiriman dan kuantitas (langsung hitung ulang total)
        shippingOptions.forEach(option => option.addEventListener('change', recalculateTotals));
        quantityInputs.forEach(input => input.addEventListener('input', recalculateTotals));
        quantityInputs.forEach(input => input.addEventListener('change', recalculateTotals));
    }
    
    // --- PROCESS CHECKOUT ---

    function processCheckout() {
        // Panggil recalculateTotals untuk memastikan currentCheckoutItems terbaru
        recalculateTotals(); 
        
        const shippingOption = document.querySelector('.shipping-option:checked');
        const paymentOption = document.querySelector('input[name="payment"]:checked');
        const addressText = '{{ session('alamat_penerima') }}'; // Ambil alamat dari sesi

        if (addressText === '') {
            alert('Harap isi alamat pengiriman terlebih dahulu!');
            location.href = '{{ route('alamat.form') }}';
            return;
        }
        
        if (!currentCheckoutItems || currentCheckoutItems.length === 0) {
            alert('Tidak ada item untuk di-checkout!');
            return;
        }
        
        if (!shippingOption || !paymentOption) {
            alert('Harap pilih metode pengiriman dan pembayaran!');
            return;
        }

        const btn = document.querySelector('.checkout-button');
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        // 1. Siapkan form POST ke route checkout.process
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('checkout.process') }}';

        // CSRF Token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        // Data cart JSON (item yang sudah divalidasi dan di-update kuantitasnya)
        const cartDataInput = document.createElement('input');
        cartDataInput.type = 'hidden';
        cartDataInput.name = 'cart_data';
        cartDataInput.value = JSON.stringify(currentCheckoutItems);
        form.appendChild(cartDataInput);

        // Metode Pembayaran
        const paymentInput = document.createElement('input');
        paymentInput.type = 'hidden';
        paymentInput.name = 'payment_method';
        paymentInput.value = paymentOption.dataset.method;
        form.appendChild(paymentInput);

        // Ongkos Kirim
        const shippingInput = document.createElement('input');
        shippingInput.type = 'hidden';
        shippingInput.name = 'shipping_cost';
        shippingInput.value = shippingOption.dataset.cost;
        form.appendChild(shippingInput);

        // Alamat Teks
        const addressInput = document.createElement('input');
        addressInput.type = 'hidden';
        addressInput.name = 'address_text';
        addressInput.value = addressText;
        form.appendChild(addressInput);

        // 4. Submit Form
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
