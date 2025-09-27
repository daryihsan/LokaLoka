<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Loka Loka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Roboto:wght@400;500;700&family=Open+Sans:wght@400;600&display=swap');

        /* Reset dan Basic Styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #e3d8c2;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Main Container */
        .container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1400px;
            min-height: 100vh;
            background-color: #fff7ef;
            margin: 0 auto;
            box-shadow: none;
            border-radius: 0;
            padding: 40px 60px;
        }

        /* Header Section */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #5c6641;
            background: linear-gradient(135deg, #5c6641 0%, #A6A604 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            color: white;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8em;
            color: #333;
            margin: 0;
        }

        .profile-info p {
            color: #666;
            margin: 5px 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #5c6641;
            color: rgb(255, 255, 255);
            font-family: 'Poppins', sans-serif;
            font-size: 1em;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            display: inline-block;
        }

        .btn:hover {
            background-color: #4a5135;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: #5c6641;
            border: 1px solid #5c6641;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        /* Success/Error Messages */
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Main Content Area */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        /* Card Section */
        .card {
            background-color: #f9f9f9;
            padding: 35px;
            border-radius: 15px;
            margin-bottom: 0;
            border: 1px solid #ddd;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #5c6641;
            padding-bottom: 8px;
        }

        .identity-info p {
            margin: 12px 0;
            font-size: 1em;
            color: #555;
            line-height: 1.6;
        }

        .identity-info strong {
            color: #111;
            font-weight: 600;
        }

        /* Order Section */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-container {
            position: relative;
        }

        #filter-btn {
            background-color: #f0f0f0;
            color: #5c6641;
            border: 1px solid #5c6641;
            padding: 10px 15px;
            font-size: 0.9em;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        #filter-btn:hover {
            background-color: #e0e0e0;
        }

        .filter-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 10;
            width: 150px;
            margin-top: 5px;
            flex-direction: column;
        }

        .filter-dropdown.show {
            display: flex;
        }

        .filter-dropdown a {
            padding: 10px 15px;
            text-decoration: none;
            color: #555;
            font-size: 0.9em;
            transition: background-color 0.2s;
        }

        .filter-dropdown a:hover {
            background-color: #f0f0f0;
        }

        .filter-dropdown a.active {
            background-color: #5c6641;
            color: white;
        }

        /* Order List Items */
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background-color: #fafafa;
        }

        .order-item.hidden {
            display: none;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .order-status {
            font-size: 0.85em;
            color: #777;
        }

        .status {
            padding: 2px 6px;
            border-radius: 5px;
            font-weight: 600;
            color: #fff;
            text-transform: capitalize;
        }

        .status-selesai {
            background-color: #95b04a;
        }

        .status-dikirim {
            background-color: #DA983C;
        }

        .status-diproses {
            background-color: #f4ddb8;
            color: #333;
        }

        .status-batal {
            background-color: #66371b;
        }

        .empty-orders {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container {
                padding: 30px 40px;
            }
            
            .main-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            header {
                flex-direction: column;
                gap: 15px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .card {
                padding: 25px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <header>
            <div class="profile-header">
                <div class="profile-pic">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="profile-info">
                    <h1>{{ $user->name ?? 'User' }}</h1>
                    <p>{{ $user->email ?? '' }}</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('homepage') }}" class="btn">← Kembali ke Beranda</a>
                <a href="{{ route('logout') }}" class="btn btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
                <a href="#" class="btn btn-secondary">Customer Service</a>
            </div>
        </header>

        <section class="main-content">
            <div class="card">
                <h2>Identitas Diri</h2>
                <div class="identity-info">
                    <p><strong>Username:</strong> {{ $user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
                    <p><strong>Nomor Telepon:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
                    <p><strong>Role:</strong> {{ ucfirst($user->role ?? 'Customer') }}</p>
                    <p><strong>Bergabung sejak:</strong> {{ $user->created_at ? $user->created_at->format('d F Y') : 'N/A' }}</p>
                    <p><strong>Update terakhir:</strong> {{ $user->updated_at ? $user->updated_at->format('d F Y') : 'N/A' }}</p>
                </div>
            </div>

            <div class="card">
                <div class="order-header">
                    <h2>Pesanan Saya</h2>
                    <div class="filter-container">
                        <button id="filter-btn" class="btn">Filter Status ▼</button>
                        <div id="filter-options" class="filter-dropdown">
                            <a href="#" data-status="semua" class="active">Semua</a>
                            <a href="#" data-status="diproses">Diproses</a>
                            <a href="#" data-status="dikirim">Dikirim</a>
                            <a href="#" data-status="selesai">Selesai</a>
                            <a href="#" data-status="batal">Batal</a>
                        </div>
                    </div>
                </div>

                <div class="order-list">
                    @if(isset($orders) && count($orders) > 0)
                        @foreach($orders as $order)
                            <div class="order-item" data-status="{{ $order->status ?? 'diproses' }}">
                                <div class="order-details">
                                    <p class="item-name">{{ $order->product_name ?? 'Produk' }}</p>
                                    <p class="order-status">Status: <span class="status status-{{ $order->status ?? 'diproses' }}">{{ ucfirst($order->status ?? 'Diproses') }}</span></p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Sample orders - remove these when you have real orders -->
                        <div class="order-item" data-status="selesai">
                            <div class="order-details">
                                <p class="item-name">Gudeg Jogja Authentic</p>
                                <p class="order-status">Status: <span class="status status-selesai">Selesai</span></p>
                            </div>
                        </div>
                        <div class="order-item" data-status="dikirim">
                            <div class="order-details">
                                <p class="item-name">Kopi Arabica Temanggung</p>
                                <p class="order-status">Status: <span class="status status-dikirim">Dikirim</span></p>
                            </div>
                        </div>
                        <div class="order-item" data-status="diproses">
                            <div class="order-details">
                                <p class="item-name">Batik Tulis Solo Premium</p>
                                <p class="order-status">Status: <span class="status status-diproses">Diproses</span></p>
                            </div>
                        </div>
                    @endif
                </div>

                @if((!isset($orders) || count($orders) == 0))
                    <div class="empty-orders" style="display: none;" id="no-orders-message">
                        <p>Tidak ada pesanan yang ditemukan untuk filter ini.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <script>
        // Filter functionality
        let currentFilter = 'semua';
        
        // Toggle dropdown
        document.getElementById('filter-btn').addEventListener('click', function() {
            const dropdown = document.getElementById('filter-options');
            dropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const filterContainer = document.querySelector('.filter-container');
            if (!filterContainer.contains(event.target)) {
                document.getElementById('filter-options').classList.remove('show');
            }
        });

        // Filter functionality
        document.querySelectorAll('[data-status]').forEach(function(filterOption) {
            if (filterOption.tagName === 'A') {
                filterOption.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Update active filter
                    document.querySelectorAll('.filter-dropdown a').forEach(a => a.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Get selected status
                    const selectedStatus = this.getAttribute('data-status');
                    currentFilter = selectedStatus;
                    
                    // Update button text
                    const statusText = selectedStatus === 'semua' ? 'Semua' : 
                                     selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1);
                    document.getElementById('filter-btn').innerHTML = `${statusText} ▼`;
                    
                    // Filter orders
                    filterOrders(selectedStatus);
                    
                    // Close dropdown
                    document.getElementById('filter-options').classList.remove('show');
                });
            }
        });

        function filterOrders(status) {
            const orderItems = document.querySelectorAll('.order-item');
            const noOrdersMessage = document.getElementById('no-orders-message');
            let visibleCount = 0;
            
            orderItems.forEach(function(item) {
                if (status === 'semua') {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    if (item.getAttribute('data-status') === status) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                }
            });

            // Show/hide no orders message
            if (noOrdersMessage) {
                if (visibleCount === 0) {
                    noOrdersMessage.style.display = 'block';
                } else {
                    noOrdersMessage.style.display = 'none';
                }
            }
        }

        // Initialize filter
        document.addEventListener('DOMContentLoaded', function() {
            filterOrders('semua');

            // Auto-hide success message after 5 seconds
            setTimeout(function() {
                const successAlert = document.querySelector('.alert-success');
                if (successAlert) {
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        if (successAlert.parentNode) {
                            successAlert.parentNode.removeChild(successAlert);
                        }
                    }, 500);
                }
            }, 5000);
        });
    </script>
</body>
</html>