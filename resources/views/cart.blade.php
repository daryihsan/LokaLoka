@extends('layouts.app')

@section('title', 'Keranjang Belanja - Loka Loka')

@push('head')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { color: #333; }
    .container-page-narrow { max-width: 80rem; margin: 0 auto; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:1rem; box-shadow:0 1px 2px rgba(0,0,0,.04); }
    .notification { position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 1rem 1.5rem; border-radius: 8px; display: none; z-index: 1000; animation: slideIn 0.3s ease-out; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); display: none; justify-content: center; align-items: center; z-index: 2000; }
    .modal-overlay.show { display: flex; }
    .modal-content { background: white; border-radius: 12px; padding: 0; max-width: 420px; width: 90%; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25); overflow: hidden; }
    .modal-header { background: #6B7C34; padding: 0.8rem 0; display: flex; align-items: center; gap: 0.5rem; }
    .modal-logo { background: #B8C951; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; margin-left: 1rem; }
    .modal-title { font-size: 1.3rem; font-weight: bold; color: white; margin: 0; }
    .modal-body { padding: 1.5rem; text-align: center; }
    .modal-message { font-size: 1.1rem; color: #333; margin-bottom: 1.5rem; }
    .modal-buttons { display: flex; justify-content: center; gap: 1rem; }
    .modal-btn { padding: 0.7rem 1.5rem; border: none; border-radius: 25px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
    .modal-btn-confirm { background: #6B7C34; color: white; }
    .modal-btn-confirm:hover { background: #5A6B2B; }
    .modal-btn-cancel { background: #C8A951; color: white; }
    .modal-btn-cancel:hover { background: #B8993D; }

    .qty-input {
        appearance: textfield;
        -moz-appearance: textfield;
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    @media (max-width: 768px) {
        .cart-item-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .cart-item-row > div {
            width: 100%;
        }
    }

    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endpush

@section('content')
<div class="container-page-narrow">
    <div class="flex items-center justify-between mb-6">
        {{-- PERBAIKAN: Tombol Kembali dengan history.back() atau ke homepage --}}
        <button class="btn btn-secondary"
            onclick="history.length > 1 ? history.back() : window.location.href = '{{ route('homepage') }}'">
            Kembali
        </button>
        <h1 class="font-roboto-slab text-3xl font-bold text-green-darker">Keranjang
            Belanja</h1>
        <div></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Daftar item -->
        <div class="lg:col-span-2 card">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="select-all" class="w-5 h-5 accent-primary" checked>
                    <label for="select-all" class="text-sm text-gray-700">Pilih semua</label>
                </div>
                <div class="text-sm text-gray-600">Total item: <span id="total-items">0</span></div>
            </div>

            <div id="empty-state" class="p-10 text-center hidden">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Keranjang Kosong</h3>
                <a href="{{ route('homepage') }}" class="btn btn-primary mt-2">Mulai Berbelanja</a>
            </div>

            <div id="cart-items-list" class="p-6 space-y-4"></div>
        </div>

        <!-- Ringkasan -->
        <aside class="card p-6 h-max sticky top-24">
            <h3 class="font-roboto text-xl font-bold text-green-darker mb-6">Ringkasan Belanja</h3>
            <div id="summary-content" class="space-y-3 mb-6">
                <div class="text-sm text-gray-600 flex justify-between"><span>Subtotal</span><span id="sum-subtotal">Rp0</span></div>
                <div class="text-sm text-gray-600 flex justify-between"><span>Ongkir (estimasi)</span><span id="sum-shipping">Rp0</span></div>
                <div class="text-base font-bold flex justify-between"><span>Total</span><span id="sum-total">Rp0</span></div>
            </div>
            <div id="checkout-section">
                <button onclick="proceedToCheckout()" class="btn btn-primary w-full" id="checkout-btn" disabled>
                    Lanjut ke Checkout
                </button>
                <p class="text-xs text-gray-500 mt-3 text-center">Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami</p>
            </div>
        </aside>
    </div>
</div>

<!-- Notification -->
<div id="notification" class="notification"></div>

<!-- Custom Modal: konfirmasi umum -->
<div id="confirmModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-content">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="font-roboto-slab text-xl font-bold text-green-darker">Loka Loka</h3>
            <button onclick="closeModal('confirmModal')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 text-center">
            <p class="text-lg text-gray-700 mb-6" id="modalMessage">Konfirmasi</p>
            <div class="flex justify-center gap-4">
                <button class="btn bg-red-600 hover:bg-red-700 text-white" id="modalCancel">Batal</button>
                <button class="btn btn-primary" id="modalConfirm">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Custom Modal: deskripsi produk --}}
<div id="productDetailModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="product-detail-name" class="font-roboto-slab text-xl font-bold text-green-darker">Detail Produk</h3>
            <button onclick="closeModal('productDetailModal')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 space-y-3">
            <img id="product-detail-image" src="" alt="Gambar Produk" class="w-full h-48 object-cover rounded-lg mb-3">
            <p id="product-detail-category" class="text-sm text-gray-500"></p>
            <p id="product-detail-price" class="text-xl font-bold text-red-600"></p>
            <h4 class="font-semibold text-green-darker border-t pt-3 mt-3">Deskripsi Produk</h4>
            <p id="product-detail-description" class="text-gray-700 whitespace-pre-wrap text-sm"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /* ==== Utilities ==== */
    const CSRF = '{{ csrf_token() }}';
    function rp(n){ return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(n).replace('Rp','Rp'); }
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.display = 'block';
        notification.style.background = type === 'success' ? '#28a745' : '#dc3545';
        setTimeout(() => { notification.style.display = 'none'; }, 2500);
    }
    function showConfirmModal(message) {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirmModal');
            const msgEl = document.getElementById('modalMessage');
            const btnOk = document.getElementById('modalConfirm');
            const btnCancel = document.getElementById('modalCancel');

            msgEl.textContent = message || 'Konfirmasi';
            modal.classList.add('show');

            const cleanup = () => {
                btnOk.removeEventListener('click', onOk);
                btnCancel.removeEventListener('click', onCancel);
                modal.removeEventListener('click', onBackdrop);
            };
            const onOk = () => { modal.classList.remove('show'); cleanup(); resolve(true); };
            const onCancel = () => { modal.classList.remove('show'); cleanup(); resolve(false); };
            const onBackdrop = (e) => { if (e.target === modal) onCancel(); };

            btnOk.addEventListener('click', onOk);
            btnCancel.addEventListener('click', onCancel);
            modal.addEventListener('click', onBackdrop);
            document.addEventListener('keydown', function esc(e){ if(e.key==='Escape'){ onCancel(); document.removeEventListener('keydown', esc);} });
        });
    }

    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }


    /* ==== Data fetching ==== */
    async function fetchCart() {
        const res = await fetch('{{ route('cart.items') }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (res.status === 401) {
            window.location.href = '{{ route('login') }}';
            return { items: [], count: 0, total: 0 };
        }
        return res.json();
    }

    /* ==== Rendering & events ==== */
    async function renderCart() {
        const list = document.getElementById('cart-items-list');
        const empty = document.getElementById('empty-state');
        const data = await fetchCart();
        
        // Total items ditampilkan di header list
        document.getElementById('total-items').textContent = data.count ?? 0;

        if (!data.items || data.items.length === 0) {
            list.innerHTML = '';
            empty.classList.remove('hidden');
            updateSummary(0);
            document.getElementById('checkout-btn').disabled = true;
            setSelectAllState();
            return;
        }

        empty.classList.add('hidden');

        // PERBAIKAN: Menggunakan map dan join untuk render HTML
        list.innerHTML = data.items.map(i => `
            <div class="cart-item-row flex items-center gap-4 border border-gray-200 rounded-xl p-4 transition duration-200 hover:shadow-md">
                <input type="checkbox" class="item-check w-5 h-5 accent-primary flex-shrink-0"
                    data-item-id="${i.id}"
                    data-price="${i.price}" data-name="${i.name}"
                    data-product-id="${i.product_id}" checked>
                
                <img src="${i.image_url || 'https://placehold.co/80x80/f3f4f6/6b7280?text=IMG'}"
                    onerror="this.onerror=null;this.src='https://placehold.co/80x80/f3f4f6/6b7280?text=IMG';"
                    alt="${i.name}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                
                <div class="flex-1 min-w-0 space-y-1">
                    <div class="font-semibold text-green-darker">${i.name}</div>
                    <div class="text-sm text-red-600 font-medium">${rp(i.price)}</div>
                    <button class="text-xs text-blue-500 hover:text-blue-700 underline" onclick="showProductDetails(${i.id}, '${i.name}', '${i.image_url}', '${rp(i.price)}', '${i.description}', '${i.category}')">Lihat Detail</button>
                </div>
                
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button class="btn btn-secondary px-2 py-1 h-8 w-8" onclick="changeQty(${i.id}, -1)">-</button>
                    <input type="number" class="w-12 text-center border rounded p-1 qty-input text-sm"
                        data-item-id="${i.id}" value="${i.quantity}" min="1" max="${i.stock}" onchange="updateQtyDirect(${i.id}, this.value)">
                    <button class="btn btn-secondary px-2 py-1 h-8 w-8" onclick="changeQty(${i.id}, 1)">+</button>
                </div>
                
                <div class="w-24 text-right font-semibold text-sm flex-shrink-0" data-subtotal-id="${i.id}">${rp(i.price * i.quantity)}</div>
                
                <button class="text-red-500 hover:text-red-700 text-sm flex-shrink-0"
                    onclick="removeItem(${i.id})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `).join('');

        bindEvents();
        recomputeSummary();
        setSelectAllState();
    }

    function bindEvents() {
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.onchange = (e) => {
                document.querySelectorAll('.item-check').forEach(c => c.checked = e.target.checked);
                recomputeSummary();
                setSelectAllState();
            };
        }

        document.querySelectorAll(".item-check").forEach(c => {
            c.addEventListener('change', () => {
                recomputeSummary();
                setSelectAllState();
            });
        });
    }

    function setSelectAllState() {
        const selectAll = document.getElementById('select-all');
        const checks = Array.from(document.querySelectorAll('.item-check'));

        if (checks.length === 0) {
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            return;
        }

        const checkedCount = checks.filter(c => c.checked).length;
        if (selectAll) {
            selectAll.checked = checkedCount === checks.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checks.length;
        }
    }

    function showProductDetails(id, name, image_url, price, description, category) {
        document.getElementById('product-detail-name').textContent = name;
        document.getElementById('product-detail-image').src = image_url || 'https://placehold.co/500x300/f3f4f6/6b7280?text=Produk';
        document.getElementById('product-detail-price').textContent = price;
        document.getElementById('product-detail-category').textContent = `Kategori: ${category}`;
        document.getElementById('product-detail-description').textContent = description || 'Tidak ada deskripsi tersedia.';
        openModal('productDetailModal');
    }

    /* ==== Item mutations ==== */
    async function changeQty(itemId, delta) {
        const row = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
        if (!row) return;

        // Cek max stock
        const maxStock = parseInt(row.max || 999);
        
        let newQ = parseInt(row.value || '1') + delta;
        newQ = Math.max(1, newQ);
        newQ = Math.min(maxStock, newQ);

        // Langsung update nilai input sementara menunggu server
        if (parseInt(row.value) !== newQ) {
            row.value = newQ;
            // Update UI subtotal cepat (optimistic update)
            const itemCheck = document.querySelector(`.item-check[data-item-id="${itemId}"]`);
            if (itemCheck) {
                const price = parseFloat(itemCheck.dataset.price);
                document.querySelector(`[data-subtotal-id="${itemId}"]`).textContent = rp(price * newQ);
            }
            recomputeSummary(); 
        }

        await updateQtyOnServer(itemId, newQ);
        await renderCart(); // Re-render setelah update server
    }

    async function updateQtyDirect(itemId, newQuantity) {
        const row = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
        if (!row) return;

        let newQ = parseInt(newQuantity || '1');
        newQ = Math.max(1, newQ);
        
        // Cek max stock
        const maxStock = parseInt(row.max || 999);
        newQ = Math.min(maxStock, newQ);
        
        row.value = newQ; // Set nilai yang sudah divalidasi
        
        // Update UI subtotal cepat (optimistic update)
        const itemCheck = document.querySelector(`.item-check[data-item-id="${itemId}"]`);
        if (itemCheck) {
            const price = parseFloat(itemCheck.dataset.price);
            document.querySelector(`[data-subtotal-id="${itemId}"]`).textContent = rp(price * newQ);
        }
        recomputeSummary(); 

        await updateQtyOnServer(itemId, newQ);
        await renderCart(); // Re-render setelah update server
    }


    async function updateQtyOnServer(itemId, qty) {
        const res = await fetch(`{{ url('/api/cart/item') }}/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                quantity: qty
            })
        });

        const data = await res.json().catch(() => ({}));

        if (res.status === 401) {
            window.location.href = '{{ route('login') }}';
            throw new Error('Unauthorized');
        }

        if (res.status === 400) {
            // Error Stok
            showNotification(data.error || 'Terjadi kesalahan saat update kuantitas.', 'error');
            return; // Jangan throw error, biarkan renderCart() berikutnya memperbaiki tampilan
        }

        if (res.ok) {
            if (!data.no_change) {
                showNotification('Jumlah produk diperbarui');
            }
            return;
        } else {
            showNotification('Gagal memperbarui produk.', 'error');
            throw new Error('Update failed');
        }
    }

    async function removeItem(itemId) {
        const ok = await showConfirmModal('Yakin ingin menghapus item dari keranjang?');
        if (!ok) return;

        const res = await fetch(`{{ url('/api/cart/item') }}/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (res.status === 401) {
            window.location.href = '{{ route('login') }}';
            return;
        }

        if (res.ok) {
            showNotification('Produk dihapus dari keranjang');
            await renderCart();
        } else {
            showNotification('Gagal menghapus produk.', 'error');
        }
    }

    /* ==== Summary / checkout ==== */
    function updateSummary(subtotal) {
        const shipping = subtotal > 0 ? 15000 : 0;
        document.getElementById('sum-subtotal').textContent = rp(subtotal);
        document.getElementById('sum-shipping').textContent = rp(shipping);
        document.getElementById('sum-total').textContent = rp(subtotal + shipping);
    }

    function recomputeSummary() {
        let subtotal = 0;
        const selectedItems = [];

        document.querySelectorAll('.item-check').forEach(check => {
            const itemId = parseInt(check.dataset.itemId);
            const qtyInput = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
            
            if (check.checked && qtyInput) {
                const price = parseFloat(check.dataset.price);
                const productId = parseInt(check.dataset.productId);
                const name = check.dataset.name;
                const qty = parseInt(qtyInput.value || '1');

                const itemRow = check.closest('.cart-item-row');
                const imageUrl = itemRow ? itemRow.querySelector('img').src : null; 

                subtotal += price * qty;

                // Kumpulkan data terpilih
                selectedItems.push({
                    id: itemId, // cart item id
                    product_id: productId,
                    name: name,
                    price: price,
                    quantity: qty,
                    image_url: imageUrl
                });
            }
        });

        updateSummary(subtotal);
        document.getElementById('checkout-btn').disabled = subtotal === 0;
        
        // Simpan item terpilih ke sessionStorage untuk dipakai di checkout
        sessionStorage.setItem('checkoutItems', JSON.stringify(selectedItems));
    }

    function proceedToCheckout() {
        const selected = JSON.parse(sessionStorage.getItem('checkoutItems') || '[]');
        
        if (selected.length === 0) {
            showNotification('Pilih minimal satu produk untuk checkout!', 'error');
            return;
        }

        window.location.href = '{{ route('checkout.show') }}';
    }

    /* ==== Init ==== */
    document.addEventListener('DOMContentLoaded', renderCart);
</script>

{{-- Modal component (hidden by default, used by showConfirmModal) --}}
@section('modals')
<div id="confirmModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-content">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="font-roboto-slab text-xl font-bold text-green-darker">Loka Loka</h3>
            <button onclick="closeModal('confirmModal')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 text-center">
            <p class="text-lg text-gray-700 mb-6" id="modalMessage">Konfirmasi</p>
            <div class="flex justify-center gap-4">
                <button class="btn bg-red-600 hover:bg-red-700 text-white" id="modalCancel">Batal</button>
                <button class="btn btn-primary" id="modalConfirm">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection
@endpush