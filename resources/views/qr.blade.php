<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - Loka Loka</title>
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
        .text-accent { color: #A6A604; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-spin { animation: spin 1s linear infinite; }
        
        /* QR Code styling */
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .qr-grid {
            display: grid;
            gap: 1px;
            background: #000;
            border: 2px solid #000;
        }
        
        .qr-cell {
            width: 8px;
            height: 8px;
            background: #fff;
        }
        
        .qr-cell.filled {
            background: #000;
        }
        
        /* Timer styles */
        .timer-ring {
            stroke-dasharray: 251;
            stroke-dashoffset: 251;
            animation: timer-countdown 300s linear;
        }
        
        @keyframes timer-countdown {
            from { stroke-dashoffset: 251; }
            to { stroke-dashoffset: 0; }
        }
    </style>
</head>
<body class="min-h-screen text-green-darker font-open-sans bg-gray-50">
    <!-- Header -->
    <header class="p-4 text-white shadow-xl bg-primary">
        <div class="flex items-center justify-between max-w-4xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold text-lg">L</span>
                </div>
                <h1 class="font-roboto-slab text-2xl font-bold tracking-wide">Loka Loka</h1>
            </div>
            
            <div class="text-sm">
                <span class="opacity-80">Order #{{ str_pad($order->id ?? 1, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </header>

    <!-- Payment Content -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- QR Code Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="font-roboto text-2xl font-bold text-green-darker mb-2">Scan QR Code</h2>
                    <p class="text-gray-600">Gunakan aplikasi e-wallet atau banking untuk scan QR code di bawah ini</p>
                </div>

                <!-- QR Code -->
                <div class="bg-gray-100 p-8 rounded-xl mb-6 inline-block">
                    <div class="qr-container">
                        <div id="qr-code" class="qr-grid" style="grid-template-columns: repeat(25, 1fr);">
                            <!-- QR Code will be generated here -->
                        </div>
                    </div>
                </div>

                <!-- Payment Amount -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-6">
                    <div class="text-sm text-gray-600 mb-1">Total Pembayaran</div>
                    <div class="text-3xl font-bold text-green-800">
                        Rp {{ number_format($order->total ?? 135000, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Payment Timer -->
                <div class="flex items-center justify-center mb-6">
                    <div class="relative">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" stroke="#e5e7eb" stroke-width="8" fill="none"></circle>
                            <circle cx="40" cy="40" r="36" stroke="#3b82f6" stroke-width="8" fill="none" 
                                    class="timer-ring"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span id="timer" class="text-lg font-bold text-blue-600">05:00</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div id="payment-status" class="text-center">
                    <div class="pulse inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-yellow-600 mr-2"></div>
                        Menunggu Pembayaran...
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="mt-8 text-left">
                    <h3 class="font-semibold text-gray-800 mb-3">Cara Bayar:</h3>
                    <ol class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                            Buka aplikasi e-wallet (GoPay, OVO, Dana, ShopeePay, dll)
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                            Pilih menu "Scan QR" atau "QRIS"
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                            Arahkan kamera ke QR code di atas
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                            Konfirmasi pembayaran di aplikasi Anda
                        </li>
                    </ol>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h3 class="font-roboto text-xl font-bold text-green-darker mb-6">Ringkasan Pesanan</h3>
                
                <!-- Order Items -->
                <div class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">☕</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-green-darker">Kopi Gayo Robusta</h4>
                            <p class="text-sm text-gray-600">1x Rp 120,000</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold">Rp 120,000</p>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="border-t pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>Rp 120,000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span>Rp 15,000</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-green-800">Rp {{ number_format($order->total ?? 135000, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-green-darker mb-2">Alamat Pengiriman</h4>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium">{{ session('nama_penerima', 'John Doe') }}</p>
                        <p>{{ session('telepon_penerima', '(+62) 812-3456-7890') }}</p>
                        <p>{{ session('alamat_penerima', 'Jl. Pahlawan No. 123, Semarang') }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <button onclick="checkPaymentStatus()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Cek Status Pembayaran
                    </button>
                    <button onclick="simulatePayment()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Simulasi Pembayaran Berhasil
                    </button>
                    <a href="{{ route('orders') }}" 
                       class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-medium transition-colors">
                        Lihat Pesanan Saya
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 backdrop-blur-sm hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-green-darker mb-2">Pembayaran Berhasil!</h3>
                <p class="text-gray-600 mb-6">Pesanan Anda sedang diproses dan akan segera dikirim.</p>
                <div class="space-y-3">
                    <a href="{{ route('orders') }}" 
                       class="block w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-lg font-medium transition-colors">
                        Lihat Status Pesanan
                    </a>
                    <a href="{{ route('homepage') }}" 
                       class="block w-full text-center text-gray-600 hover:text-gray-800">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate QR Code pattern (simplified)
        function generateQRCode() {
            const qrContainer = document.getElementById('qr-code');
            const size = 25; // 25x25 grid
            
            // Simple pattern for demo QR code
            const pattern = [
                [1,1,1,1,1,1,1,0,1,0,1,0,1,0,1,1,1,1,1,1,1,1,1,1,1],
                [1,0,0,0,0,0,1,0,1,1,0,1,0,0,1,0,0,0,0,0,1,0,0,0,1],
                [1,0,1,1,1,0,1,0,0,1,1,0,1,0,1,0,1,1,1,0,1,0,1,0,1],
                [1,0,1,1,1,0,1,0,1,0,0,1,0,0,1,0,1,1,1,0,1,0,1,0,1],
                [1,0,1,1,1,0,1,0,0,1,1,0,1,0,1,0,1,1,1,0,1,0,1,0,1],
                [1,0,0,0,0,0,1,0,1,0,0,1,0,0,1,0,0,0,0,0,1,0,0,0,1],
                [1,1,1,1,1,1,1,0,1,0,1,0,1,0,1,1,1,1,1,1,1,1,1,1,1],
                [0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0],
                [1,0,1,1,0,1,1,1,0,0,1,0,1,1,0,1,1,0,1,0,1,1,0,1,1],
                [0,1,0,0,1,0,0,0,1,1,0,1,0,0,1,0,0,1,0,1,0,0,1,0,0],
                [1,0,1,1,0,1,1,0,0,0,1,0,1,1,0,1,1,0,1,0,1,1,0,1,1],
                [0,1,0,0,1,0,0,1,1,1,0,1,0,0,1,0,0,1,0,1,0,0,1,0,0],
                [1,0,1,1,0,1,1,0,0,0,1,0,1,1,0,1,1,0,1,0,1,1,0,1,1],
                [0,1,0,0,1,0,0,1,1,1,0,1,0,0,1,0,0,1,0,1,0,0,1,0,0],
                [1,0,1,1,0,1,1,0,0,0,1,0,1,1,0,1,1,0,1,0,1,1,0,1,1],
                [0,1,0,0,1,0,0,1,1,1,0,1,0,0,1,0,0,1,0,1,0,0,1,0,0],
                [1,0,1,1,0,1,1,0,0,0,1,0,1,1,0,1,1,0,1,0,1,1,0,1,1],
                [0,0,0,0,0,0,0,0,1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0],
                [1,1,1,1,1,1,1,0,0,1,0,1,0,1,1,1,1,1,1,1,1,1,1,1,1],
                [1,0,0,0,0,0,1,0,1,0,1,0,1,0,0,0,0,0,1,0,0,0,0,0,1],
                [1,0,1,1,1,0,1,0,0,1,0,1,0,0,1,1,1,0,1,0,1,1,1,0,1],
                [1,0,1,1,1,0,1,0,1,0,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1],
                [1,0,1,1,1,0,1,0,0,1,0,1,0,0,1,1,1,0,1,0,1,1,1,0,1],
                [1,0,0,0,0,0,1,0,1,0,1,0,1,0,0,0,0,0,1,0,0,0,0,0,1],
                [1,1,1,1,1,1,1,0,0,1,0,1,0,1,1,1,1,1,1,1,1,1,1,1,1]
            ];

            qrContainer.innerHTML = '';
            
            for (let row = 0; row < size; row++) {
                for (let col = 0; col < size; col++) {
                    const cell = document.createElement('div');
                    cell.className = 'qr-cell';
                    
                    if (pattern[row] && pattern[row][col] === 1) {
                        cell.classList.add('filled');
                    }
                    
                    qrContainer.appendChild(cell);
                }
            }
        }

        // Timer functionality
        let timeRemaining = 300; // 5 minutes in seconds
        let timerInterval;

        function startTimer() {
            timerInterval = setInterval(() => {
                timeRemaining--;
                
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    expirePayment();
                }
            }, 1000);
        }

        function expirePayment() {
            const statusDiv = document.getElementById('payment-status');
            statusDiv.innerHTML = `
                <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Waktu Pembayaran Habis
                </div>
            `;
            
            setTimeout(() => {
                if (confirm('Waktu pembayaran telah habis. Buat pesanan baru?')) {
                    window.location.href = '{{ route("checkout") }}';
                }
            }, 2000);
        }

        // Payment status check
        function checkPaymentStatus() {
            const statusDiv = document.getElementById('payment-status');
            
            // Show loading
            statusDiv.innerHTML = `
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                    Mengecek status...
                </div>
            `;

            // Simulate API call
            setTimeout(() => {
                // In real app, this would check with payment gateway
                const isSuccess = Math.random() > 0.7; // 30% chance of success for demo
                
                if (isSuccess) {
                    paymentSuccess();
                } else {
                    statusDiv.innerHTML = `
                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-yellow-600 mr-2"></div>
                            Menunggu Pembayaran...
                        </div>
                    `;
                }
            }, 2000);
        }

        // Simulate successful payment
        function simulatePayment() {
            paymentSuccess();
        }

        function paymentSuccess() {
            clearInterval(timerInterval);
            
            const statusDiv = document.getElementById('payment-status');
            statusDiv.innerHTML = `
                <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Pembayaran Berhasil!
                </div>
            `;

            // Show success modal
            setTimeout(() => {
                document.getElementById('success-modal').classList.remove('hidden');
                
                // Update order status in database
                updateOrderStatus();
            }, 1000);
        }

        function updateOrderStatus() {
            // In real app, send AJAX request to update order status
            fetch('/api/orders/{{ $order->id ?? 1 }}/payment-confirm', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_status: 'paid',
                    payment_method: 'qris'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Order status updated:', data);
            })
            .catch(error => {
                console.error('Error updating order:', error);
            });
        }

        // Auto-refresh payment status
        function autoCheckPayment() {
            setInterval(() => {
                if (timeRemaining > 0) {
                    // In real app, check payment status with backend
                    // checkPaymentStatus();
                }
            }, 10000); // Check every 10 seconds
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            generateQRCode();
            startTimer();
            autoCheckPayment();
            
            // Close modal when clicking outside
            document.getElementById('success-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>