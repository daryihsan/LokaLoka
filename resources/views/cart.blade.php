<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Loka Loka</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-roboto-slab { font-family: 'Roboto Slab', serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-open-sans { font-family: 'Open Sans', sans-serif; }
        
        .bg-primary { background-color: #5c6641; }
        .bg-primary-dark { background-color: #414833; }
        .bg-accent { background-color: #A6A604; }
        .text-green-darker { color: #333D29; }
        .text-green-dark { color: #656D4A; }
        .text-green-olive { color: #A4AC86; }
        .text-brown-dark { color: #5E0E0E; }
        .text-accent { color: #A6A604; }
        .border-green-light { border-color: #C2C5AA; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); }
            to { transform: translateX(100%); }
        }

        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-slide-in { animation: slideIn 0.3s ease-out forwards; }
        .animate-slide-out { animation: slideOut 0.3s ease-out forwards; }
        
        .cart-item {
            transition: all 0.3s ease;
        }
        
        .cart-item:hover {
            background-color: #f9fafb;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .quantity-btn {
            transition: all 0.2s ease;
        }
        
        .quantity-btn:hover {
            transform: scale(1.05);
        }
        
        .quantity-btn:active {
            transform: scale(0.95);
        }

        .checkbox-custom {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            background-color: white;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .checkbox-custom:checked {
            background-color: #5c6641;
            border-color: #5c6641;
        }

        .checkbox-custom:checked::after {
            content: '✓';
            position: absolute;
            top: -2px;
            left: 2px;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .loading-overlay {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(2px);
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .bounce-animation { animation: bounce 0.3s ease-in-out; }
    </style>
</head>
<body class="min-h-screen text-green-darker font-open-sans bg-gray-50">
    <!-- Header -->
    <header class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold text-lg">L</span>
                </div>
                <a href="{{ route('homepage') }}" class="font-roboto-slab text-3xl font-bold tracking-wide hover:text-accent">
                    Loka Loka
                </a>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('homepage') }}" class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl px-4 py-2 text-sm">
                    Kembali Berbelanja
                </a>
                
                <!-- User Menu -->
                <div class="relative">
                    <button class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl p-3 flex items-center gap-2" onclick="toggleUserMenu()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="hidden md:inline">{{ $user->name ?? 'User' }}</span>
                    </button>
                    <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl p-2 w-48 border">
                        <div class="px-4 py-2 border-b">
                            <p class="font-semibold text-green-darker">{{ $user->name ?? 'User' }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-green-darker hover:bg-gray-100 rounded-lg">Profile</a>
                        <a href="{{ route('orders') }}" class="block px-4 py-2 text-green-darker hover:bg-gray-100 rounded-lg">Pesanan</a>
                        <hr class="my-2">
                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto p-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-7xl mx-auto p-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="font-roboto-slab text-3xl font-bold text-green-darker mb-2">Keranjang Belanja</h1>
            <p class="text-gray-600">Kelola produk dalam keranjang Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg">
                    <!-- Cart Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="select-all" class="checkbox-custom" onchange="toggleSelectAll()">
                                <label for="select-all" class="font-semibold text-green-darker cursor-pointer">
                                    Pilih Semua (<span id="total-items">0</span> item)
                                </label>
                            </div>
                            <button onclick="clearSelectedItems()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                Hapus Terpilih
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    <div id="cart-items-container">
                        <!-- Loading State -->
                        <div id="loading-state" class="p-8 text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-4"></div>
                            <p class="text-gray-600">Memuat keranjang...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="hidden p-12 text-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Keranjang Kosong</h3>
                            <p class="text-gray-500 mb-6">Belum ada produk dalam keranjang Anda</p>
                            <a href="{{ route('homepage') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-medium transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Mulai Berbelanja
                            </a>
                        </div>

                        <!-- Cart Items will be populated here -->
                        <div id="cart-items-list" class="hidden"></div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h3 class="font-roboto text-xl font-bold text-green-darker mb-6">Ringkasan Belanja</h3>
                    
                    <!-- Summary Items -->
                    <div id="summary-content" class="space-y-4 mb-6">
                        <div class="text-center text-gray-500 py-8">
                            <p>Pilih produk untuk melihat ringkasan</p>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <div id="checkout-section" class="hidden">
                        <button onclick="proceedToCheckout()" 
                                class="w-full bg-primary hover:bg-primary-dark text-white py-4 rounded-xl font-bold text-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                id="checkout-btn" disabled>
                            Lanjut ke Checkout
                        </button>
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 backdrop-blur-sm hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-darker mb-2">Konfirmasi Hapus</h3>
                    <p id="modal-message" class="text-gray-600">Apakah Anda yakin ingin menghapus item ini dari keranjang?</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                    <button onclick="confirmAction()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-20 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
        <div class="flex items-center gap-3">
            <div id="toast-icon" class="flex-shrink-0"></div>
            <div>
                <p id="toast-message" class="font-medium text-green-darker"></p>
                <p id="toast-submessage" class="text-sm text-gray-600"></p>
            </div>
            <button onclick="hideToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Global variables
        let cartItems = [];
        let selectedItems = [];
        let pendingAction = null;

        // Initialize cart
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
            setupEventListeners();
        });

        // Load cart items from backend
        async function loadCartItems() {
            try {
                const response = await fetch('{{ route("cart.items") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                cartItems = data.items || [];
                updateCartDisplay();
                
            } catch (error) {
                console.error('Error loading cart:', error);
                showEmptyState();
                showToast('error', 'Gagal memuat keranjang', error.message);
            }
        }

        // Update cart display
        function updateCartDisplay() {
            const loadingState = document.getElementById('loading-state');
            const emptyState = document.getElementById('empty-state');
            const cartList = document.getElementById('cart-items-list');
            
            loadingState.style.display = 'none';
            
            if (cartItems.length === 0) {
                emptyState.classList.remove('hidden');
                cartList.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                cartList.classList.remove('hidden');
                renderCartItems();
            }
            
            updateSummary();
            updateTotalItems();
        }

        // Render cart items
        function renderCartItems() {
            const container = document.getElementById('cart-items-list');
            
            container.innerHTML = cartItems.map((item, index) => {
                const isSelected = selectedItems.includes(item.id);
                const subtotal = item.price * item.quantity;
                
                return `
                    <div class="cart-item p-6 border-b border-gray-200 ${isSelected ? 'bg-green-50' : ''}" 
                         data-item-id="${item.id}">
                        <div class="flex items-center gap-4">
                            <!-- Checkbox -->
                            <input type="checkbox" class="checkbox-custom item-checkbox" 
                                   data-item-id="${item.id}" 
                                   ${isSelected ? 'checked' : ''} 
                                   onchange="toggleItemSelection(${item.id})">
                            
                            <!-- Product Image -->
                            <div class="w-20 h-20 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                                ${item.image_url ? 
                                    `<img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover">` :
                                    `<div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>`
                                }
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-green-darker text-lg mb-1 truncate">${item.name}</h3>
                                <p class="text-sm text-gray-600 mb-2">Stok: ${item.stock}</p>
                                <p class="text-lg font-bold text-green-800">Rp ${parseInt(item.price).toLocaleString()}</p>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3">
                                <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" 
                                        class="quantity-btn w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center font-bold text-lg ${item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                                        ${item.quantity <= 1 ? 'disabled' : ''}>
                                    -
                                </button>
                                <span class="font-semibold text-lg min-w-[2rem] text-center">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" 
                                        class="quantity-btn w-10 h-10 bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center font-bold text-lg ${item.quantity >= item.stock ? 'opacity-50 cursor-not-allowed' : ''}"
                                        ${item.quantity >= item.stock ? 'disabled' : ''}>
                                    +
                                </button>
                            </div>
                            
                            <!-- Subtotal & Remove -->
                            <div class="text-right">
                                <p class="font-bold text-lg text-green-800 mb-2">Rp ${subtotal.toLocaleString()}</p>
                                <button onclick="removeItem(${item.id})" 
                                        class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Toggle item selection
        function toggleItemSelection(itemId) {
            const index = selectedItems.indexOf(itemId);
            if (index > -1) {
                selectedItems.splice(index, 1);
            } else {
                selectedItems.push(itemId);
            }
            
            updateCartDisplay();
            updateSelectAllCheckbox();
        }

        // Toggle select all
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('select-all');
            
            if (selectAllCheckbox.checked) {
                selectedItems = cartItems.map(item => item.id);
            } else {
                selectedItems = [];
            }
            
            updateCartDisplay();
        }

        // Update select all checkbox state
        function updateSelectAllCheckbox() {
            const selectAllCheckbox = document.getElementById('select-all');
            
            if (selectedItems.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedItems.length === cartItems.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        // Update quantity
        async function updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;
            
            const item = cartItems.find(item => item.id === itemId);
            if (!item || newQuantity > item.stock) return;
            
            try {
                const response = await fetch(`{{ route('cart.update', ':id') }}`.replace(':id', itemId), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity: newQuantity })
                });
                
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                // Update local data
                item.quantity = newQuantity;
                updateCartDisplay();
                
                showToast('success', 'Jumlah diperbarui', `${item.name} - ${newQuantity} pcs`);
                
            } catch (error) {
                console.error('Error updating quantity:', error);
                showToast('error', 'Gagal memperbarui', error.message);
            }
        }

        // Remove item
        function removeItem(itemId) {
            const item = cartItems.find(item => item.id === itemId);
            if (!item) return;
            
            showModal(
                'Hapus Item',
                `Apakah Anda yakin ingin menghapus "${item.name}" dari keranjang?`,
                () => confirmRemoveItem(itemId)
            );
        }

        // Confirm remove item
        async function confirmRemoveItem(itemId) {
            try {
                const response = await fetch(`{{ route('cart.remove', ':id') }}`.replace(':id', itemId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                // Remove from local data
                cartItems = cartItems.filter(item => item.id !== itemId);
                selectedItems = selectedItems.filter(id => id !== itemId);
                
                updateCartDisplay();
                showToast('success', 'Item dihapus', 'Produk berhasil dihapus dari keranjang');
                
            } catch (error) {
                console.error('Error removing item:', error);
                showToast('error', 'Gagal menghapus', error.message);
            }
        }

        // Clear selected items
        function clearSelectedItems() {
            if (selectedItems.length === 0) {
                showToast('warning', 'Tidak ada item terpilih', 'Pilih item yang ingin dihapus');
                return;
            }
            
            const itemNames = selectedItems.map(id => {
                const item = cartItems.find(item => item.id === id);
                return item ? item.name : '';
            }).filter(name => name);
            
            showModal(
                'Hapus Item Terpilih',
                `Apakah Anda yakin ingin menghapus ${selectedItems.length} item terpilih?`,
                () => confirmClearSelected()
            );
        }

        // Confirm clear selected
        async function confirmClearSelected() {
            try {
                const promises = selectedItems.map(itemId => 
                    fetch(`{{ route('cart.remove', ':id') }}`.replace(':id', itemId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                );
                
                await Promise.all(promises);
                
                // Update local data
                cartItems = cartItems.filter(item => !selectedItems.includes(item.id));
                selectedItems = [];
                
                updateCartDisplay();
                showToast('success', 'Items dihapus', 'Semua item terpilih berhasil dihapus');
                
            } catch (error) {
                console.error('Error clearing selected items:', error);
                showToast('error', 'Gagal menghapus', error.message);
            }
        }

        // Update summary
        function updateSummary() {
            const summaryContent = document.getElementById('summary-content');
            const checkoutSection = document.getElementById('checkout-section');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            if (selectedItems.length === 0) {
                summaryContent.innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <p>Pilih produk untuk melihat ringkasan</p>
                    </div>
                `;
                checkoutSection.classList.add('hidden');
                return;
            }
            
            const selectedCartItems = cartItems.filter(item => selectedItems.includes(item.id));
            const subtotal = selectedCartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const shippingCost = 15000; // Fixed shipping cost
            const total = subtotal + shippingCost;
            
            summaryContent.innerHTML = `
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal (${selectedItems.length} item)</span>
                        <span class="font-medium">Rp ${subtotal.toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium">Rp ${shippingCost.toLocaleString()}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-green-800">Rp ${total.toLocaleString()}</span>
                    </div>
                </div>
            `;
            
            checkoutSection.classList.remove('hidden');
            checkoutBtn.disabled = false;
        }

        // Update total items count
        function updateTotalItems() {
            document.getElementById('total-items').textContent = cartItems.length;
        }

        // Proceed to checkout
        function proceedToCheckout() {
            if (selectedItems.length === 0) {
                showToast('warning', 'Pilih produk', 'Pilih minimal satu produk untuk checkout');
                return;
            }
            
            const selectedCartItems = cartItems.filter(item => selectedItems.includes(item.id));
            const checkoutData = selectedCartItems.map(item => ({
                id: item.id,
                product_id: item.product_id,
                name: item.name,
                price: item.price,
                quantity: item.quantity,
                image_url: item.image_url
            }));
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("checkout.post") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const cartDataInput = document.createElement('input');
            cartDataInput.type = 'hidden';
            cartDataInput.name = 'cart_data';
            cartDataInput.value = JSON.stringify(checkoutData);
            form.appendChild(cartDataInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const userDropdown = document.getElementById('user-dropdown');
                if (!event.target.closest('#user-dropdown') && !event.target.closest('button[onclick="toggleUserMenu()"]')) {
                    userDropdown.classList.add('hidden');
                }
            });
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                const successAlert = document.querySelector('[role="alert"]');
                if (successAlert && successAlert.classList.contains('bg-green-100')) {
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500);
                }
            }, 5000);
        }

        // Show empty state
        function showEmptyState() {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('empty-state').classList.remove('hidden');
            document.getElementById('cart-items-list').classList.add('hidden');
        }

        // Modal functions
        function showModal(title, message, confirmCallback) {
            document.getElementById('modal-message').textContent = message;
            document.getElementById('confirmation-modal').classList.remove('hidden');
            pendingAction = confirmCallback;
        }

        function closeModal() {
            document.getElementById('confirmation-modal').classList.add('hidden');
            pendingAction = null;
        }

        function confirmAction() {
            if (pendingAction) {
                pendingAction();
            }
            closeModal();
        }

        // Toast functions
        function showToast(type, title, message) {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            const titleEl = document.getElementById('toast-message');
            const messageEl = document.getElementById('toast-submessage');
            
            // Set icon based on type
            const icons = {
                success: `<div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                          </div>`,
                error: `<div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                          <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                        </div>`,
                warning: `<div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                          </div>`
            };
            
            icon.innerHTML = icons[type] || icons.success;
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            // Show toast
            toast.classList.remove('translate-x-full');
            toast.classList.add('bounce-animation');
            
            // Hide after 4 seconds
            setTimeout(() => {
                hideToast();
            }, 4000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.add('translate-x-full');
            toast.classList.remove('bounce-animation');
        }

        // User menu toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('confirmation-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>