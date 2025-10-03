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
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endpush

@section('content')
<div class="container-page-narrow">
    <div class="flex items-center justify-between mb-6">
        <button class="btn btn-secondary" onclick="history.length>1?history.back():window.location='{{ route('homepage') }}'">← Kembali</button>
        <h1 class="font-roboto-slab text-3xl font-bold text-green-darker">Keranjang Belanja</h1>
        <div></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Daftar item -->
        <div class="lg:col-span-2 card">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="select-all" class="w-5 h-5">
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
        <div class="modal-header">
            <div class="modal-logo">L</div>
            <h3 class="modal-title">Loka Loka</h3>
        </div>
        <div class="modal-body">
            <p class="modal-message" id="modalMessage">Konfirmasi</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-confirm" id="modalConfirm">OK</button>
                <button class="modal-btn modal-btn-cancel" id="modalCancel">Batal</button>
            </div>
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

/* ==== Data fetching ==== */
async function fetchCart() {
    const res = await fetch('{{ route('cart.items') }}', { headers:{'X-Requested-With':'XMLHttpRequest'}});
    if (res.status === 401) { window.location.href = '{{ route('login') }}'; return { items:[], count:0, total:0 }; }
    return res.json();
}

/* ==== Rendering & events ==== */
async function renderCart() {
    const list = document.getElementById('cart-items-list');
    const empty = document.getElementById('empty-state');
    const data = await fetchCart();

    document.getElementById('total-items').textContent = data.count ?? 0;

    if (!data.items || data.items.length === 0) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        updateSummary(0);
        document.getElementById('checkout-btn').disabled = true;
        setSelectAllState();
        return;
    } else {
        empty.classList.add('hidden');
    }

    list.innerHTML = data.items.map(i => `
        <div class="flex items-center gap-4 border border-gray-200 rounded-lg p-4">
            <input type="checkbox" class="item-check w-5 h-5" data-item-id="${i.id}" data-price="${i.price}" data-name="${i.name}" data-product-id="${i.product_id}" checked>
            <img src="${i.image_url || 'https://placehold.co/80x80?text=IMG'}" alt="${i.name}" class="w-16 h-16 object-cover rounded">
            <div class="flex-1">
                <div class="font-semibold">${i.name}</div>
                <div class="text-sm text-gray-500">${rp(i.price)}</div>
            </div>
            <div class="flex items-center gap-2">
                <button class="btn btn-secondary px-2 py-1" onclick="changeQty(${i.id}, -1)">-</button>
                <input type="number" class="w-16 text-center border rounded p-1 qty-input" data-item-id="${i.id}" value="${i.quantity}" min="1">
                <button class="btn btn-secondary px-2 py-1" onclick="changeQty(${i.id}, 1)">+</button>
            </div>
            <div class="w-24 text-right font-semibold">${rp(i.price * i.quantity)}</div>
            <button class="btn btn-secondary" onclick="removeItem(${i.id})">Hapus</button>
        </div>
    `).join('');

    bindEvents();
    recomputeSummary();
    setSelectAllState();
}

function bindEvents() {
    document.querySelectorAll('.qty-input').forEach(inp => {
        inp.addEventListener('change', async (e) => {
            const itemId = e.target.dataset.itemId;
            const qty = Math.max(1, parseInt(e.target.value || '1'));
            await updateQty(itemId, qty);
            showNotification('Jumlah produk diperbarui');
            await renderCart();
        });
    });

    const selectAll = document.getElementById('select-all');
    selectAll.onchange = (e) => {
        document.querySelectorAll('.item-check').forEach(c => c.checked = e.target.checked);
        recomputeSummary();
        setSelectAllState();
    };

    document.querySelectorAll('.item-check').forEach(c => c.addEventListener('change', () => {
        recomputeSummary();
        setSelectAllState();
    }));
}

/* Tri-state select-all */
function setSelectAllState() {
    const selectAll = document.getElementById('select-all');
    const checks = Array.from(document.querySelectorAll('.item-check'));
    if (checks.length === 0) { selectAll.checked = false; selectAll.indeterminate = false; return; }
    const checkedCount = checks.filter(c => c.checked).length;
    selectAll.checked = checkedCount === checks.length;
    selectAll.indeterminate = checkedCount > 0 && checkedCount < checks.length;
}

/* ==== Item mutations ==== */
async function changeQty(itemId, delta) {
    const row = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
    const newQ = Math.max(1, parseInt(row?.value || '1') + delta);
    await updateQty(itemId, newQ);
    showNotification('Jumlah produk diperbarui');
    await renderCart();
}

async function updateQty(itemId, qty) {
    const res = await fetch(`{{ url('/cart/item') }}/${itemId}`, {
        method: 'PATCH',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
        body: JSON.stringify({ quantity: qty })
    });
    if (res.status === 401) { window.location.href = '{{ route('login') }}'; }
}

async function removeItem(itemId) {
    const ok = await showConfirmModal('Hapus item dari keranjang?');
    if (!ok) return;
    const res = await fetch(`{{ url('/cart/item') }}/${itemId}`, {
        method: 'DELETE',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}
    });
    if (res.status === 401) { window.location.href = '{{ route('login') }}'; return; }
    showNotification('Produk dihapus dari keranjang');
    await renderCart();
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
    document.querySelectorAll('.item-check').forEach(check => {
        if (check.checked) {
            const price = parseInt(check.dataset.price);
            const itemId = check.dataset.itemId;
            const qty = parseInt(document.querySelector(`.qty-input[data-item-id="${itemId}"]`)?.value || '1');
            subtotal += price * qty;
        }
    });
    updateSummary(subtotal);
    document.getElementById('checkout-btn').disabled = subtotal === 0;
}

function proceedToCheckout() {
    // Kumpulkan item terpilih: disimpan ke sessionStorage untuk dipakai halaman checkout
    const selected = [];
    document.querySelectorAll('.item-check').forEach(check => {
        if (check.checked) {
            const itemId = parseInt(check.dataset.itemId);       // cart_item id
            const productId = parseInt(check.dataset.productId); // product id
            const name = check.dataset.name;
            const price = parseInt(check.dataset.price);
            const qty = parseInt(document.querySelector(`.qty-input[data-item-id="${itemId}"]`)?.value || '1');
            selected.push({ id: itemId, product_id: productId, name, price, quantity: qty });
        }
    });
    sessionStorage.setItem('checkoutItems', JSON.stringify(selected));
    window.location.href = "{{ url('/checkout') }}";
}

/* ==== Init ==== */
document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endpush