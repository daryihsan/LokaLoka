<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - LokaLoka</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fffceb;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #6B7C34 0%, #8B9D46 100%);
            color: white;
            padding: 0.8rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            gap: 2rem;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.3rem;
            font-weight: bold;
            color: #FFF;
            gap: 0.5rem;
        }
        
        .logo-icon {
            background: rgba(255,255,255,0.2);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        
        .search-container {
            flex: 1;
            max-width: 500px;
            position: relative;
        }
        
        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-bar input {
            width: 100%;
            padding: 0.7rem 1rem;
            border: none;
            border-radius: 25px;
            font-size: 0.95rem;
            background: rgba(255,255,255,0.95);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .search-bar input:focus {
            outline: none;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.9rem;
        }
        
        .filter-btn, .location-btn {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-btn:hover, .location-btn:hover {
            background: rgba(255,255,255,0.25);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .cart-icon {
            font-size: 1.5rem;
        }
        
        .cart-container {
            background: #e8f2e8;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .cart-header {
            background: linear-gradient(135deg, #7B8C45 0%, #6B7C34 100%);
            color: white;
            padding: 1.2rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem 2rem;
            background: white;
            margin: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .cart-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .item-checkbox {
            margin-right: 1.5rem;
        }
        
        .item-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #7B8C45;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            background: #e9ecef;
            border-radius: 12px;
            margin-right: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 0.8rem;
            overflow: hidden;
        }
        
        .item-details {
            flex: 1;
            margin-right: 1rem;
        }
        
        .item-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #2c3e50;
        }
        
        .item-price {
            font-size: 1rem;
            color: #e74c3c;
            font-weight: 600;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 1rem;
        }
        
        .quantity-btn {
            background: #7B8C45;
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-btn:hover {
            background: #6B7C34;
            transform: scale(1.05);
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .remove-btn {
            background: #66371B;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .remove-btn:hover {
            background: #66371B;
            transform: scale(1.05);
        }
        
        .cart-summary {
            background: #e8f2e8;
            padding: 2rem;
            text-align: right;
        }
        
        .total-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #7B8C45 0%, #6B7C34 100%);
            color: white;
            border: none;
            padding: 1rem 3rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(107, 124, 52, 0.3);
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(107, 124, 52, 0.4);
        }
        
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
            background: white;
            margin: 1rem;
            border-radius: 12px;
        }
        
        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .continue-shopping {
            background: linear-gradient(135deg, #7B8C45 0%, #6B7C34 100%);
            color: white;
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .continue-shopping:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            display: none;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Custom Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #6B7C34 0%, #8B9D46 100%);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .modal-logo {
            background: #B8C951;
            color: #2c3e50;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .modal-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            margin: 0;
        }
        
        .modal-body {
            padding: 2rem 1.5rem 1.5rem 1.5rem;
        }
        
        .modal-message {
            font-size: 1.1rem;
            color: #333;
            margin: 0 0 2rem 0;
            line-height: 1.4;
            font-weight: 500;
        }
        
        .modal-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }
        
        .modal-btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 80px;
        }
        
        .modal-btn-cancel {
            background: #C8A951;
            color: white;
        }
        
        .modal-btn-cancel:hover {
            background: #B8993D;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .modal-btn-confirm {
            background: #6B7C34;
            color: white;
        }
        
        .modal-btn-confirm:hover {
            background: #5A6B2B;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes modalSlideIn {
            from {
                transform: scale(0.9) translateY(-30px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
                gap: 1rem;
            }
            
            .item-checkbox {
                margin-right: 0;
                align-self: flex-start;
            }
            
            .item-image {
                margin-right: 0;
            }
            
            .item-details {
                margin-right: 0;
                width: 100%;
            }
            
            .quantity-controls {
                margin-right: 0;
            }
            
            .header-content {
                padding: 0 1rem;
                gap: 1rem;
            }
            
            .container {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>


    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <!-- Custom Modal -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-logo">L</div>
                <h3 class="modal-title">Loka Loka</h3>
            </div>
            <div class="modal-body">
                <p class="modal-message" id="modalMessage">Hapus produk dari keranjang?</p>
                <div class="modal-buttons">
                    <button class="modal-btn modal-btn-confirm" id="modalConfirm">OK</button>
                    <button class="modal-btn modal-btn-cancel" id="modalCancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1 class="page-title">
            Keranjang Belanja
        </h1>
        
        <div class="cart-container">
            <div id="cart-content">
                <!-- Cart items will be rendered here -->
            </div>
        </div>
    </div>

    <script>
        // Cart data simulation
        let cartData = {
            '1': {
                name: 'Kopi Gayo',
                price: 32000,
                image: 'kopi-gayo.jpg',
                quantity: 1
            },
            '2': {
                name: 'Matcha',
                price: 28000,
                image: 'matcha.jpg',
                quantity: 2
            }
        };

        // Format number to Indonesian Rupiah
        function formatRupiah(number) {
            return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Show custom confirmation modal
        function showConfirmModal(message) {
            return new Promise((resolve) => {
                const modal = document.getElementById('confirmModal');
                const messageElement = document.getElementById('modalMessage');
                const confirmButton = document.getElementById('modalConfirm');
                const cancelButton = document.getElementById('modalCancel');
                
                messageElement.textContent = message;
                modal.classList.add('show');
                
                const handleConfirm = () => {
                    modal.classList.remove('show');
                    cleanup();
                    resolve(true);
                };
                
                const handleCancel = () => {
                    modal.classList.remove('show');
                    cleanup();
                    resolve(false);
                };
                
                const cleanup = () => {
                    confirmButton.removeEventListener('click', handleConfirm);
                    cancelButton.removeEventListener('click', handleCancel);
                    modal.removeEventListener('click', handleOverlayClick);
                };
                
                const handleOverlayClick = (e) => {
                    if (e.target === modal) {
                        handleCancel();
                    }
                };
                
                confirmButton.addEventListener('click', handleConfirm);
                cancelButton.addEventListener('click', handleCancel);
                modal.addEventListener('click', handleOverlayClick);
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';
            notification.style.background = type === 'success' ? '#28a745' : '#dc3545';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Calculate total price
        function calculateTotal() {
            let total = 0;
            Object.values(cartData).forEach(item => {
                total += item.price * item.quantity;
            });
            return total;
        }

        // Update quantity
        async function updateQuantity(productId, change) {
            if (cartData[productId]) {
                const newQuantity = cartData[productId].quantity + change;
                if (newQuantity >= 1) {
                    cartData[productId].quantity = newQuantity;
                    renderCart();
                    showNotification('Jumlah produk diperbarui');
                } else if (newQuantity === 0) {
                    const confirmed = await showConfirmModal(`Hapus ${cartData[productId].name} dari keranjang?`);
                    if (confirmed) {
                        delete cartData[productId];
                        renderCart();
                        showNotification('Produk dihapus dari keranjang');
                    } else {
                        renderCart();
                    }
                }
            }
        }

        // Update quantity directly
        async function updateQuantityDirect(productId, newQuantity) {
            newQuantity = parseInt(newQuantity);
            if (cartData[productId]) {
                if (newQuantity >= 1) {
                    cartData[productId].quantity = newQuantity;
                    renderCart();
                    showNotification('Jumlah produk diperbarui');
                } else if (newQuantity === 0) {
                    const confirmed = await showConfirmModal(`Hapus ${cartData[productId].name} dari keranjang?`);
                    if (confirmed) {
                        delete cartData[productId];
                        renderCart();
                        showNotification('Produk dihapus dari keranjang');
                    } else {
                        renderCart();
                    }
                }
            }
        }

        // Remove item from cart
        async function removeItem(productId) {
            const confirmed = await showConfirmModal(`Hapus ${cartData[productId].name} dari keranjang?`);
            if (confirmed) {
                delete cartData[productId];
                renderCart();
                showNotification('Produk dihapus dari keranjang');
            }
        }

        // Add to cart
        function addToCart(productId, productName, productPrice, productImage = 'default.jpg') {
            if (cartData[productId]) {
                cartData[productId].quantity++;
            } else {
                cartData[productId] = {
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    quantity: 1
                };
            }
            renderCart();
            showNotification(`${productName} ditambahkan ke keranjang`);
        }

        // Go to checkout
        function goToCheckout() {
            if (Object.keys(cartData).length === 0) {
                alert('Keranjang kosong! Silakan tambahkan produk terlebih dahulu.');
                return;
            }
            alert('Menuju halaman checkout...');
        }

        // Render cart items
        function renderCart() {
            const cartContent = document.getElementById('cart-content');
            
            if (Object.keys(cartData).length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <h2>Keranjang Anda Kosong</h2>
                        <p>Mulai berbelanja produk lokal terbaik!</p>
                        <a href="#" class="continue-shopping" onclick="addSampleProducts()">Mulai Belanja</a>
                    </div>
                `;
                return;
            }

            let cartHTML = `
                <div class="cart-header">
                    Kopi Tuku
                </div>
            `;

            Object.keys(cartData).forEach(productId => {
                const item = cartData[productId];
                
                cartHTML += `
                    <div class="cart-item">
                        <div class="item-checkbox">
                            <input type="checkbox" checked>
                        </div>
                        
                        <div class="item-image">
                            <img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        </div>
                        
                        <div class="item-details">
                            <div class="item-name">${item.name}</div>
                            <div class="item-price">${formatRupiah(item.price)}</div>
                        </div>
                        
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity('${productId}', -1)">-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="0" 
                                   onchange="updateQuantityDirect('${productId}', this.value)">
                            <button class="quantity-btn" onclick="updateQuantity('${productId}', 1)">+</button>
                        </div>
                        
                        <button class="remove-btn" onclick="removeItem('${productId}')">Hapus</button>
                    </div>
                `;
            });

            const total = calculateTotal();
            cartHTML += `
                <div class="cart-summary">
                    <div class="total-price">
                        Total: ${formatRupiah(total)}
                    </div>
                    <button class="checkout-btn" onclick="goToCheckout()">Checkout</button>
                </div>
            `;

            cartContent.innerHTML = cartHTML;
        }

        // Add sample products for demo
        function addSampleProducts() {
            cartData = {
                '1': {
                    name: 'Kopi Gayo',
                    price: 32000,
                    image: 'kopi-gayo.jpg',
                    quantity: 1
                },
                '2': {
                    name: 'Matcha',
                    price: 28000,
                    image: 'matcha.jpg',
                    quantity: 2
                }
            };
            renderCart();
            showNotification('Produk contoh ditambahkan ke keranjang');
        }

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderCart();
        });
    </script>
</body>
</html>