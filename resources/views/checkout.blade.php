@extends('layouts.app')

@section('title', 'Checkout - Loka Loka')

@push('head')
<style>
/* ... (Bagian Style CSS tetap sama, tidak ada perubahan) ... */

    /* PERBAIKAN CSS: Menargetkan span total untuk mencegah tampilan hardcode */
    .summary-total span#summary-total {
        font-size: 1.2em; /* Ensure consistency */
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <div class="main-content">
        {{-- CARD ALAMAT --}}
        <div class="card">
            <div class="card-header">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </span>
                <h2>Alamat Pengiriman</h2>
            </div>
            <div class="shipping-address">
                {{-- PERBAIKAN: Gunakan data sesi atau data user yang sedang login ($user) sebagai fallback --}}
                <strong>{{ session('nama_penerima', $user->name ?? 'Pengguna') }}</strong>
                <p>{{ session('telepon_penerima', $user->phone_number ?? '(Nomor tidak tersedia)') }}</p>
                <p>{{ session('alamat_penerima', 'Anda belum mengisi alamat pengiriman.') }}</p>
                
                <button class="checkout-button" onclick="location.href='{{ route('alamat.form') }}'">Ubah Alamat</button>
            </div>
        </div>

        {{-- CARD METODE PENGIRIMAN --}}
        <div class="card">
            {{-- ... (Isi Metode Pengiriman tetap sama) ... --}}
            <div class="card-header">
                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
                <h2>Metode Pengiriman</h2>
            </div>
            <div class="option-group">
                <input type="radio" class="shipping-option" id="standard" name="shipping" data-cost="15000" checked>
                <label for="standard">
                    <div class="option-details"><strong>Reguler</strong><span>Estimasi 2-3 hari kerja</span></div>
                    <span class="option-price">Rp15.000</span>
                </label>
                <input type="radio" class="shipping-option" id="express" name="shipping" data-cost="25000">
                <label for="express">
                    <div class="option-details"><strong>Express</strong><span>Estimasi 1 hari kerja</span></div>
                    <span class="option-price">Rp25.000</span>
                </label>
            </div>
        </div>

        {{-- CARD METODE PEMBAYARAN --}}
        <div class="card">
            {{-- ... (Isi Metode Pembayaran tetap sama) ... --}}
            <div class="card-header">
                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="10" rx="2" ry="2"/><path d="M2 12h20"/></svg></span>
                <h2>Metode Pembayaran</h2>
            </div>
            <div class="option-group">
                <input type="radio" id="qris" name="payment" checked>
                <label for="qris">
                    <div class="option-details"><strong>QRIS</strong><span>Bayar dengan QR code (Gopay, OVO, Dana, dll)</span></div>
                </label>
            </div>
        </div>
    </div>

    {{-- RINGKASAN PESANAN --}}
    <div class="order-summary">
        <div class="card">
            <h1>Ringkasan Pesanan</h1>
            <div class="product-list">
                {{-- Konten diisi oleh JS dari sessionStorage --}}
            </div>
            <div class="summary-calculation">
                <div class="summary-row">
                    <span>Subtotal</span>
                    {{-- PERBAIKAN: Hapus Hardcode, set nilai awal ke Rp0 atau kosong --}}
                    <span id="summary-subtotal">Rp0</span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    {{-- PERBAIKAN: Hapus Hardcode, set nilai awal ke Rp0 atau kosong --}}
                    <span id="summary-shipping">Rp0</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total Pembayaran</span>
                    {{-- PERBAIKAN: Hapus Hardcode, set nilai awal ke Rp0 atau kosong --}}
                    <span id="summary-total">Rp0</span>
                </div>
            </div>
            {{-- PERBAIKAN KRUSIAL: Hapus onclick dengan route() yang bermasalah --}}
            {{-- Biarkan tombol ini murni memicu event listener di JS --}}
            <button class="checkout-button">Bayar Sekarang</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka).replace('Rp', 'Rp');
    }

    function recalculateTotals(){
        let subtotal = 0;
        const productItems = document.querySelectorAll('.product-item');
        productItems.forEach(item => {
            // Menggunakan attribute data-price
            const price = parseFloat(item.dataset.price); 
            const quantityInput = item.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value) || 1; // Pastikan minimal 1
            quantityInput.value = quantity; // Jika ada nilai invalid, set ke 1
            
            const lineTotal = price * quantity;
            item.querySelector('.product-price').innerText = formatRupiah(lineTotal);
            subtotal += lineTotal;
        });

        const shippingOption = document.querySelector('.shipping-option:checked');
        const shippingCost = parseFloat(shippingOption.dataset.cost); 
        const total = subtotal + shippingCost;

        document.getElementById('summary-subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('summary-shipping').innerText = formatRupiah(shippingCost);
        
        // PERBAIKAN: Pastikan ID summary-total di update di sini
        document.getElementById('summary-total').innerText = formatRupiah(total);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const productListContainer = document.querySelector('.product-list');
        // PERBAIKAN DUMMY: Coba ambil dari session storage
        const itemsFromCart = JSON.parse(sessionStorage.getItem('checkoutItems'));
        
        if (itemsFromCart && itemsFromCart.length > 0) {
            productListContainer.innerHTML = ''; 
            itemsFromCart.forEach(item => {
                const productItemHTML = `
                    <div class="product-item" data-price="${item.price}" data-product-id="${item.product_id}" data-item-id="${item.id}">
                        <img src="${item.image_url || 'https://placehold.co/70x70/e3d8c2/5c6641?text=Produk'}" alt="${item.name}">
                        <div class="product-details">
                            <h3>${item.name}</h3>
                            <p>${formatRupiah(item.price)}</p>
                        </div>
                        <div class="product-quantity">
                            <input type="number" class="quantity-input" data-product-id="${item.product_id}" 
                                value="${item.quantity}" min="1">
                        </div>
                        <div class="product-price">${formatRupiah(item.price * item.quantity)}</div>
                    </div>
                `;
                productListContainer.insertAdjacentHTML('beforeend', productItemHTML);
            });
        } else {
            // Jika kosong, arahkan kembali ke keranjang
            productListContainer.innerHTML = '<p style="text-align: center; color: #666;">Tidak ada item untuk di-checkout. <a href="{{ route('cart.show') }}">Kembali ke Keranjang</a></p>';
            document.querySelector('.checkout-button').disabled = true;
        }
        
        setupEventListeners();
        recalculateTotals();
    });

function setupEventListeners() {
    const shippingOptions = document.querySelectorAll('.shipping-option');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    shippingOptions.forEach(option => option.addEventListener('change', recalculateTotals));
    quantityInputs.forEach(input => input.addEventListener('input', recalculateTotals));

    document.querySelector('.checkout-button').addEventListener('click', function (event) {
        event.preventDefault();
        
        const selectedItemsData = [];
        const shippingOption = document.querySelector('.shipping-option:checked');
        const paymentOption = document.querySelector('input[name="payment"]:checked');
        
        // 1. Kumpulkan data dari DOM yang baru di-render
        document.querySelectorAll('.product-item').forEach(itemEl => {
            const price = parseFloat(itemEl.dataset.price);
            const quantity = parseInt(itemEl.querySelector('.quantity-input').value);
            
            // PERBAIKAN KRUSIAL: Update quantity di sessionStorage agar konsisten saat refresh/kembali
            const existingItemIndex = selectedItemsData.findIndex(i => i.id === itemEl.dataset.itemId);
            if (existingItemIndex > -1) {
                selectedItemsData[existingItemIndex].quantity = quantity;
            } else {
                 selectedItemsData.push({
                    id: itemEl.dataset.itemId, // cart_item_id
                    product_id: itemEl.dataset.productId,
                    name: itemEl.querySelector('h3').textContent,
                    price: price,
                    quantity: quantity
                });
            }
        });

        // 2. Simpan kembali data yang sudah diupdate kuantitasnya ke sessionStorage
        sessionStorage.setItem('checkoutItems', JSON.stringify(selectedItemsData));

        const addressText = '{{ session('alamat_penerima', 'Jl. Pahlawan No. 123, Semarang') }}';
        
        // 3. Siapkan form POST (SAMA SEPERTI KODE SEBELUMNYA)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('checkout.process') }}';
        
        // CSRF Token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden'; csrfInput.name = '_token'; csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Data cart JSON
        const cartDataInput = document.createElement('input');
        cartDataInput.type = 'hidden'; cartDataInput.name = 'cart_data'; cartDataInput.value = JSON.stringify(selectedItemsData);
        form.appendChild(cartDataInput);
        
        // Metode Pembayaran
        const paymentInput = document.createElement('input');
        paymentInput.type = 'hidden'; paymentInput.name = 'payment_method'; paymentInput.value = paymentOption?.id || 'qris';
        form.appendChild(paymentInput);
        
        // Ongkos Kirim
        const shippingInput = document.createElement('input');
        shippingInput.type = 'hidden'; shippingInput.name = 'shipping_cost'; shippingInput.value = shippingOption?.dataset.cost || 0;
        form.appendChild(shippingInput);
        
        // Alamat Teks
        const addressInput = document.createElement('input');
        addressInput.type = 'hidden'; addressInput.name = 'address_text'; addressInput.value = addressText;
        form.appendChild(addressInput);
        
        // 4. Submit Form
        document.body.appendChild(form);
        form.submit();
    });
}
</script>
@endpush