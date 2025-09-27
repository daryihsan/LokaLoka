<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Loka Loka</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap');
        :root {
            --primary-color: #5c6641;
            --background-color: #e3d8c2;
            --surface-color: #ffffff;
            --text-primary: #333333;
            --text-secondary: #666666;
            --border-color: #e0e0e0;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 40px 20px;
            color: var(--text-primary);
        }
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .card {
            background-color: var(--surface-color);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .card-header .icon { color: var(--primary-color); }
        .card-header h2 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            font-size: 1.4em;
            margin: 0;
        }
        .shipping-address p {
            margin: 0;
            line-height: 1.6;
        }
        .shipping-address strong { display: block; margin-bottom: 5px; }
        .change-link {
            display: block;
            margin-top: 10px;
            color: var(--primary-color);
            font-weight: bold;
            text-decoration: none;
            font-size: 0.9em;
        }
        .option-group label {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .option-group label:hover { border-color: var(--primary-color); }
        .option-group input[type="radio"] { display: none; }
        .option-group input[type="radio"]:checked + label {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(92, 102, 65, 0.3);
        }
        .option-details { flex-grow: 1; }
        .option-details strong { display: block; }
        .option-details span { font-size: 0.9em; color: var(--text-secondary); }
        .option-price { font-weight: bold; }
        .order-summary { position: sticky; top: 40px; }
        .order-summary h1 {
            font-family: 'Roboto Slab', serif;
            font-size: 2em;
            margin-top: 0;
            margin-bottom: 5px;
        }
        .product-list { margin-top: 25px; max-height: 300px; overflow-y: auto; padding-right: 10px; }
        .product-item { display: flex; gap: 15px; align-items: center; margin-bottom: 20px; }
        .product-item img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
        .product-details { flex-grow: 1; }
        .product-details h3 { font-size: 1em; margin: 0 0 5px 0; }
        .product-details p { margin: 0; color: var(--text-secondary); }
        .quantity-input { /* Mengganti nama class untuk lebih spesifik */
            width: 50px; text-align: center; border: 1px solid var(--border-color);
            border-radius: 5px; padding: 5px;
        }
        .product-price { font-weight: bold; min-width: 80px; text-align: right; }
        .summary-calculation {
            border-top: 1px solid var(--border-color); padding-top: 20px;
            margin-top: 20px; display: flex; flex-direction: column; gap: 15px;
        }
        .summary-row { display: flex; justify-content: space-between; }
        .summary-row span { transition: all 0.3s ease; }
        .summary-total { font-size: 1.2em; font-weight: bold; }
        .checkout-button {
            width: 100%; padding: 15px; border: none; border-radius: 8px;
            background-color: var(--primary-color); color: white; font-family: 'Roboto', sans-serif;
            font-size: 1.1em; font-weight: bold; cursor: pointer; margin-top: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .checkout-button:hover { background-color: #4a5335; transform: translateY(-2px); }
        @media (max-width: 992px) {
            .checkout-container { grid-template-columns: 1fr; }
            .order-summary { position: static; }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    <h2>Alamat Pengiriman</h2>
                </div>
                <div class="shipping-address">
                    <strong>{{ session('nama_penerima', 'John Doe') }}</strong>
                    <p>{{ session('telepon_penerima', '(+62) 812-3456-7890') }}</p>
                    <p>{{ session('alamat_penerima', 'Jl. Pahlawan No. 123, Mugassari, Kec. Semarang Sel., Kota Semarang, Jawa Tengah 50243') }}</p>
                    <a href="{{ route('alamat.form') }}" class="change-link">Ubah Alamat</a>
                </div>
            </div>
            <div class="card">
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
            <div class="card">
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
        <div class="order-summary">
            <div class="card">
                <h1>Ringkasan Pesanan</h1>
                <div class="product-list">
                    </div>
                <div class="summary-calculation">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">Rp120.000</span>
                    </div>
                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span id="summary-shipping">Rp15.000</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total Pembayaran</span>
                        <span id="summary-total">Rp135.000</span>
                    </div>
                </div>
                <button class="checkout-button">Bayar Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka).replace('Rp', 'Rp');
        }

        function recalculateTotals() {
            let subtotal = 0;
            const productItems = document.querySelectorAll('.product-item');
            
            // 1. Hitung subtotal dari semua barang
            productItems.forEach(item => {
                const price = parseInt(item.dataset.price);
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                const lineTotal = price * quantity;
                
                // Update harga per baris produk
                item.querySelector('.product-price').innerText = formatRupiah(lineTotal);
                
                subtotal += lineTotal;
            });

            // 2. Ambil biaya pengiriman yang dipilih
            const shippingOption = document.querySelector('.shipping-option:checked');
            const shippingCost = parseInt(shippingOption.dataset.cost);

            // 3. Hitung total akhir
            const total = subtotal + shippingCost;
            
            // 4. Tampilkan semua nilai baru ke ringkasan
            document.getElementById('summary-subtotal').innerText = formatRupiah(subtotal);
            document.getElementById('summary-shipping').innerText = formatRupiah(shippingCost);
            document.getElementById('summary-total').innerText = formatRupiah(total);
        }

        document.addEventListener('DOMContentLoaded', function() {
        const productListContainer = document.querySelector('.product-list');
        // Ambil data dari sessionStorage yang dikirim dari halaman keranjang
        const itemsFromCart = JSON.parse(sessionStorage.getItem('checkoutItems'));

        if (itemsFromCart && itemsFromCart.length > 0) {
            // Kosongkan kontainer list produk
            productListContainer.innerHTML = '';

            // Loop setiap item dan buat elemen HTML-nya
            itemsFromCart.forEach(item => {
                const productItemHTML = `
                    <div class="product-item" data-price="${item.price}">
                        <img src="${item.image}" alt="${item.name}" onerror="this.src='https://placehold.co/70x70/e3d8c2/5c6641?text=Produk'">
                        <div class="product-details">
                            <h3>${item.name}</h3>
                            <p>${formatRupiah(item.price)}</p>
                        </div>
                        <div class="product-quantity">
                           <input type="number" class="quantity-input" value="${item.quantity}" min="1">
                        </div>
                        <div class="product-price">${formatRupiah(item.price * item.quantity)}</div>
                    </div>
                `;
                // Masukkan HTML produk ke dalam kontainer
                productListContainer.insertAdjacentHTML('beforeend', productItemHTML);
            });

            // Hapus data dari session storage agar tidak digunakan lagi jika halaman di-refresh
            sessionStorage.removeItem('checkoutItems');

        } else {
            productListContainer.innerHTML = '<p style="text-align: center; color: #666;">Tidak ada item untuk di-checkout.</p>';
        }

        // Pasang event listener ke elemen yang ada atau baru dibuat
        setupEventListeners();
        // Panggil kalkulasi ulang untuk pertama kali
        recalculateTotals();
    });

    function setupEventListeners() {
        const shippingOptions = document.querySelectorAll('.shipping-option');
        const quantityInputs = document.querySelectorAll('.quantity-input');

        shippingOptions.forEach(option => {
            option.addEventListener('change', recalculateTotals);
        });

        quantityInputs.forEach(input => {
            input.addEventListener('input', recalculateTotals);
        });

        document.querySelector('.checkout-button').addEventListener('click', function(event) {
            event.preventDefault();
            const totalText = document.getElementById('summary-total').innerText;
            const totalValue = parseInt(totalText.replace(/[^0-9]/g, ''));
            window.location.href = `{{ route('payment') }}?total=${totalValue}`;
        });
    }
</script>
</body>
</html>