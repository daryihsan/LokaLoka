<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Loka Loka</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-roboto-slab { font-family: 'Roboto Slab', serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-open-sans { font-family: 'Open Sans', sans-serif; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        
        /* Custom colors */
        .bg-primary { background-color: #5c6641; }
        .bg-primary-dark { background-color: #414833; }
        .bg-accent { background-color: #A6A604; }
        .text-green-darker { color: #333D29; }
        .text-green-dark { color: #656D4A; }
        .text-green-olive { color: #A4AC86; }
        .text-brown-dark { color: #5E0E0E; }
        .text-accent { color: #A6A604; }
        .border-green-light { border-color: #C2C5AA; }
        
        .stat-card {
            background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            width: 4px;
        }
        
        .scrollbar-hide::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .scrollbar-hide::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-diproses { background-color: #fef3c7; color: #92400e; }
        .status-dikirim { background-color: #dbeafe; color: #1e40af; }
        .status-selesai { background-color: #dcfce7; color: #166534; }
        .status-batal { background-color: #fee2e2; color: #dc2626; }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 64px;
        }
        
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar-item {
            transition: all 0.2s ease;
        }
        
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-right: 4px solid #A6A604;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }
        
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 font-open-sans">
    <!-- Header -->
    <header class="bg-primary text-white shadow-xl">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                            <span class="text-green-darker font-bold text-lg">L</span>
                        </div>
                        <div>
                            <h1 class="font-roboto-slab text-2xl font-bold">Loka Loka</h1>
                            <p class="text-sm opacity-80">Admin Dashboard</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-white hover:bg-opacity-20" onclick="toggleUserMenu()">
                            <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                                <span class="text-green-darker font-bold text-sm">{{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <span class="font-medium">{{ $user->name ?? 'Admin' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl p-2 w-48 border">
                            <div class="px-4 py-2 border-b">
                                <p class="font-semibold text-green-darker">{{ $user->name ?? 'Admin' }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($user->role ?? 'Admin') }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-green-darker hover:bg-gray-100 rounded-lg">Profile</a>
                            <hr class="my-2">
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-primary-dark text-white w-64 min-h-screen">
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-lg" data-section="overview">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="sidebar-text font-medium">Overview</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg" data-section="products">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="sidebar-text font-medium">Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg" data-section="orders">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="sidebar-text font-medium">Pesanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg" data-section="categories">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-1v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8m14 0V9a2 2 0 00-2-2M5 10V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="sidebar-text font-medium">Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg" data-section="users">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="sidebar-text font-medium">Users</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <!-- Overview Section -->
            <section id="overview-section" class="section-content">
                <div class="mb-8">
                    <h2 class="font-roboto-slab text-3xl font-bold text-green-darker mb-2">Dashboard Overview</h2>
                    <p class="text-gray-600">Selamat datang di panel admin Loka Loka</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card p-6 rounded-2xl shadow-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Total Produk</p>
                                <p class="text-3xl font-bold text-green-darker" id="total-products">{{ $totalProducts ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 rounded-2xl shadow-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Total Pesanan</p>
                                <p class="text-3xl font-bold text-green-darker" id="total-orders">{{ $totalOrders ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 rounded-2xl shadow-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Total Users</p>
                                <p class="text-3xl font-bold text-green-darker" id="total-users">{{ $totalUsers ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 rounded-2xl shadow-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Pending Orders</p>
                                <p class="text-3xl font-bold text-orange-600" id="pending-orders">{{ $pendingOrders ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="font-roboto text-xl font-semibold text-green-darker mb-4">Pesanan Terbaru</h3>
                    <div class="table-container scrollbar-hide">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="recent-orders-table" class="divide-y divide-gray-200">
                                <!-- Sample data - replace with real data -->
                                <tr>
                                    <td class="px-4 py-3 text-sm">#001</td>
                                    <td class="px-4 py-3 text-sm">John Doe</td>
                                    <td class="px-4 py-3 text-sm font-medium">Rp 150,000</td>
                                    <td class="px-4 py-3"><span class="status-badge status-diproses">diproses</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-500">2025-01-01</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Products Section -->
            <section id="products-section" class="section-content hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-roboto-slab text-3xl font-bold text-green-darker">Manajemen Produk</h2>
                        <p class="text-gray-600">Kelola produk Loka Loka</p>
                    </div>
                    <button onclick="openProductModal()" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Produk
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex gap-4">
                            <input type="text" placeholder="Cari produk..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" id="product-search">
                            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" id="category-filter">
                                <option value="">Semua Kategori</option>
                                <!-- Categories will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="table-container scrollbar-hide">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="categories-table" class="divide-y divide-gray-200">
                                <!-- Categories will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Users Section -->
            <section id="users-section" class="section-content hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-roboto-slab text-3xl font-bold text-green-darker">Manajemen Users</h2>
                        <p class="text-gray-600">Kelola pengguna sistem</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex gap-4">
                            <input type="text" placeholder="Cari user..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" id="user-search">
                            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" id="role-filter">
                                <option value="">Semua Role</option>
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-container scrollbar-hide">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="users-table" class="divide-y divide-gray-200">
                                <!-- Users will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Product Modal -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-green-darker" id="product-modal-title">Tambah Produk</h3>
                <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="product-form">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" id="product-name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="number" id="product-price" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                            <input type="number" id="product-stock" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select id="product-category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Berat (kg)</label>
                            <input type="number" step="0.01" id="product-weight" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="product-description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                        <input type="url" id="product-image" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Simpan
                    </button>
                    <button type="button" onclick="closeProductModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="category-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-green-darker" id="category-modal-title">Tambah Kategori</h3>
                <button onclick="closeCategoryModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="category-form">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" id="category-name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Simpan
                    </button>
                    <button type="button" onclick="closeCategoryModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Status Modal -->
    <div id="order-status-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-green-darker">Update Status Pesanan</h3>
                <button onclick="closeOrderStatusModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="order-status-form">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Order ID: <span id="order-id-display" class="font-medium"></span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan</label>
                    <select id="order-status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                        <option value="diproses">Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Resi (Opsional)</label>
                    <input type="text" id="tracking-number" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Masukkan nomor resi jika sudah dikirim">
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Update
                    </button>
                    <button type="button" onclick="closeOrderStatusModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global variables
        let currentOrderId = null;
        let currentProductId = null;
        let currentCategoryId = null;

        // Sample data - replace with actual API calls
        const sampleProducts = [
            {id: 1, name: "Gudeg Jogja Authentic", category: "Makanan", price: 25000, stock: 50, is_active: true, category_id: 1},
            {id: 2, name: "Kopi Arabica Temanggung", category: "Minuman", price: 45000, stock: 30, is_active: true, category_id: 2},
            {id: 3, name: "Batik Tulis Solo Premium", category: "Fashion", price: 150000, stock: 15, is_active: true, category_id: 3}
        ];

        const sampleOrders = [
            {id: 1, customer: "John Doe", total: 150000, status: "diproses", created_at: "2025-01-01", tracking_number: null},
            {id: 2, customer: "Jane Smith", total: 75000, status: "dikirim", created_at: "2025-01-02", tracking_number: "JNE123456"},
            {id: 3, customer: "Bob Johnson", total: 200000, status: "selesai", created_at: "2025-01-03", tracking_number: "JNT789012"}
        ];

        const sampleCategories = [
            {id: 1, name: "Makanan", product_count: 25},
            {id: 2, name: "Minuman", product_count: 15},
            {id: 3, name: "Fashion", product_count: 10}
        ];

        const sampleUsers = [
            {id: 1, name: "John Doe", email: "john@example.com", role: "customer", created_at: "2024-12-01"},
            {id: 2, name: "Admin User", email: "admin@lokaloka.com", role: "admin", created_at: "2024-11-15"}
        ];

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeSidebar();
            loadOverviewData();
            loadProductsData();
            loadOrdersData();
            loadCategoriesData();
            loadUsersData();
            setupEventListeners();
            
            // Auto-hide success message
            setTimeout(() => {
                const successAlert = document.querySelector('[role="alert"]');
                if (successAlert && successAlert.classList.contains('bg-green-100')) {
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500);
                }
            }, 5000);
        });

        // Sidebar functionality
        function initializeSidebar() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });

            // Sidebar navigation
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const section = e.currentTarget.dataset.section;
                    switchSection(section);
                    
                    // Update active state
                    document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
                    e.currentTarget.classList.add('active');
                });
            });
        }

        // Section switching
        function switchSection(section) {
            document.querySelectorAll('.section-content').forEach(s => s.classList.add('hidden'));
            document.getElementById(section + '-section').classList.remove('hidden');
        }

        // User dropdown
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            const userDropdown = document.getElementById('user-dropdown');
            if (!event.target.closest('#user-dropdown') && !event.target.closest('button[onclick="toggleUserMenu()"]')) {
                userDropdown.classList.add('hidden');
            }
        });

        // Load overview data
        function loadOverviewData() {
            // Update stats (replace with actual API calls)
            document.getElementById('total-products').textContent = sampleProducts.length;
            document.getElementById('total-orders').textContent = sampleOrders.length;
            document.getElementById('total-users').textContent = sampleUsers.length;
            document.getElementById('pending-orders').textContent = sampleOrders.filter(o => o.status === 'diproses').length;

            // Load recent orders
            loadRecentOrders();
        }

        function loadRecentOrders() {
            const tbody = document.getElementById('recent-orders-table');
            tbody.innerHTML = sampleOrders.slice(0, 5).map(order => `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium">#${String(order.id).padStart(3, '0')}</td>
                    <td class="px-4 py-3 text-sm">${order.customer}</td>
                    <td class="px-4 py-3 text-sm font-medium">Rp ${order.total.toLocaleString()}</td>
                    <td class="px-4 py-3"><span class="status-badge status-${order.status}">${order.status}</span></td>
                    <td class="px-4 py-3 text-sm text-gray-500">${order.created_at}</td>
                </tr>
            `).join('');
        }

        // Load products data
        function loadProductsData() {
            const tbody = document.getElementById('products-table');
            tbody.innerHTML = sampleProducts.map(product => `
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg mr-3"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">${product.category}</td>
                    <td class="px-4 py-3 text-sm font-medium">Rp ${product.price.toLocaleString()}</td>
                    <td class="px-4 py-3 text-sm ${product.stock < 10 ? 'text-red-600' : 'text-gray-900'}">${product.stock}</td>
                    <td class="px-4 py-3">
                        <span class="status-badge ${product.is_active ? 'status-selesai' : 'status-batal'}">
                            ${product.is_active ? 'Aktif' : 'Nonaktif'}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="editProduct(${product.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Load orders data
        function loadOrdersData() {
            const tbody = document.getElementById('orders-table');
            tbody.innerHTML = sampleOrders.map(order => `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium">#${String(order.id).padStart(3, '0')}</td>
                    <td class="px-4 py-3 text-sm">${order.customer}</td>
                    <td class="px-4 py-3 text-sm font-medium">Rp ${order.total.toLocaleString()}</td>
                    <td class="px-4 py-3"><span class="status-badge status-${order.status}">${order.status}</span></td>
                    <td class="px-4 py-3 text-sm text-gray-500">${order.created_at}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="updateOrderStatus(${order.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Update Status</button>
                            <button onclick="viewOrderDetails(${order.id})" class="text-green-600 hover:text-green-800 text-sm font-medium">Detail</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Load categories data
        function loadCategoriesData() {
            const tbody = document.getElementById('categories-table');
            tbody.innerHTML = sampleCategories.map(category => `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium">${category.id}</td>
                    <td class="px-4 py-3 text-sm">${category.name}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">${category.product_count} produk</td>
                    <td class="px-4 py-3 text-sm text-gray-500">2024-12-01</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="editCategory(${category.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            <button onclick="deleteCategory(${category.id})" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                        </div>
                    </td>
                </tr>
            `).join('');

            // Populate category dropdowns
            const categorySelect = document.getElementById('product-category');
            const categoryFilter = document.getElementById('category-filter');
            
            const categoryOptions = sampleCategories.map(cat => 
                `<option value="${cat.id}">${cat.name}</option>`
            ).join('');
            
            categorySelect.innerHTML = '<option value="">Pilih Kategori</option>' + categoryOptions;
            categoryFilter.innerHTML = '<option value="">Semua Kategori</option>' + categoryOptions;
        }

        // Load users data
        function loadUsersData() {
            const tbody = document.getElementById('users-table');
            tbody.innerHTML = sampleUsers.map(user => `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium">${user.id}</td>
                    <td class="px-4 py-3 text-sm">${user.name}</td>
                    <td class="px-4 py-3 text-sm">${user.email}</td>
                    <td class="px-4 py-3">
                        <span class="status-badge ${user.role === 'admin' ? 'status-dikirim' : 'status-selesai'}">
                            ${user.role}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">${user.created_at}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="viewUserDetails(${user.id})" class="text-green-600 hover:text-green-800 text-sm font-medium">Detail</button>
                            <button onclick="toggleUserRole(${user.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Change Role</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Modal functions
        function openProductModal(productId = null) {
            currentProductId = productId;
            const modal = document.getElementById('product-modal');
            const title = document.getElementById('product-modal-title');
            
            if (productId) {
                title.textContent = 'Edit Produk';
                // Load product data for editing
                const product = sampleProducts.find(p => p.id === productId);
                if (product) {
                    document.getElementById('product-name').value = product.name;
                    document.getElementById('product-price').value = product.price;
                    document.getElementById('product-stock').value = product.stock;
                    document.getElementById('product-category').value = product.category_id;
                }
            } else {
                title.textContent = 'Tambah Produk';
                document.getElementById('product-form').reset();
            }
            
            modal.classList.add('show');
        }

        function closeProductModal() {
            document.getElementById('product-modal').classList.remove('show');
            currentProductId = null;
        }

        function openCategoryModal(categoryId = null) {
            currentCategoryId = categoryId;
            const modal = document.getElementById('category-modal');
            const title = document.getElementById('category-modal-title');
            
            if (categoryId) {
                title.textContent = 'Edit Kategori';
                const category = sampleCategories.find(c => c.id === categoryId);
                if (category) {
                    document.getElementById('category-name').value = category.name;
                }
            } else {
                title.textContent = 'Tambah Kategori';
                document.getElementById('category-form').reset();
            }
            
            modal.classList.add('show');
        }

        function closeCategoryModal() {
            document.getElementById('category-modal').classList.remove('show');
            currentCategoryId = null;
        }

        function updateOrderStatus(orderId) {
            currentOrderId = orderId;
            const modal = document.getElementById('order-status-modal');
            const order = sampleOrders.find(o => o.id === orderId);
            
            if (order) {
                document.getElementById('order-id-display').textContent = '#' + String(orderId).padStart(3, '0');
                document.getElementById('order-status').value = order.status;
                document.getElementById('tracking-number').value = order.tracking_number || '';
            }
            
            modal.classList.add('show');
        }

        function closeOrderStatusModal() {
            document.getElementById('order-status-modal').classList.remove('show');
            currentOrderId = null;
        }

        // Event listeners
        function setupEventListeners() {
            // Product form
            document.getElementById('product-form').addEventListener('submit', (e) => {
                e.preventDefault();
                // Handle product save
                alert('Produk berhasil disimpan!');
                closeProductModal();
                loadProductsData();
            });

            // Category form
            document.getElementById('category-form').addEventListener('submit', (e) => {
                e.preventDefault();
                // Handle category save
                alert('Kategori berhasil disimpan!');
                closeCategoryModal();
                loadCategoriesData();
            });

            // Order status form
            document.getElementById('order-status-form').addEventListener('submit', (e) => {
                e.preventDefault();
                // Handle status update
                const newStatus = document.getElementById('order-status').value;
                alert(`Status pesanan berhasil diupdate ke: ${newStatus}`);
                closeOrderStatusModal();
                loadOrdersData();
                loadOverviewData();
            });

            // Search functionality
            document.getElementById('product-search')?.addEventListener('input', filterProducts);
            document.getElementById('order-search')?.addEventListener('input', filterOrders);
            document.getElementById('user-search')?.addEventListener('input', filterUsers);
            
            // Filter functionality
            document.getElementById('category-filter')?.addEventListener('change', filterProducts);
            document.getElementById('status-filter')?.addEventListener('change', filterOrders);
            document.getElementById('role-filter')?.addEventListener('change', filterUsers);
        }

        // Filter functions
        function filterProducts() {
            // Implementation for product filtering
            console.log('Filtering products...');
        }

        function filterOrders() {
            // Implementation for order filtering
            console.log('Filtering orders...');
        }

        function filterUsers() {
            // Implementation for user filtering
            console.log('Filtering users...');
        }

        // Action functions
        function editProduct(id) {
            openProductModal(id);
        }

        function deleteProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                alert('Produk berhasil dihapus!');
                loadProductsData();
                loadOverviewData();
            }
        }

        function editCategory(id) {
            openCategoryModal(id);
        }

        function deleteCategory(id) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                alert('Kategori berhasil dihapus!');
                loadCategoriesData();
            }
        }

        function viewOrderDetails(id) {
            alert(`Menampilkan detail pesanan #${id}`);
        }

        function viewUserDetails(id) {
            alert(`Menampilkan detail user #${id}`);
        }

        function toggleUserRole(id) {
            if (confirm('Apakah Anda yakin ingin mengubah role user ini?')) {
                alert('Role user berhasil diubah!');
                loadUsersData();
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        });
    </script>
</body>
</html>
                                </tr>
                            </thead>
                            <tbody id="products-table" class="divide-y divide-gray-200">
                                <!-- Products will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Orders Section -->
            <section id="orders-section" class="section-content hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-roboto-slab text-3xl font-bold text-green-darker">Manajemen Pesanan</h2>
                        <p class="text-gray-600">Kelola pesanan pelanggan</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex gap-4">
                            <input type="text" placeholder="Cari pesanan..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" id="order-search">
                            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="diproses">Diproses</option>
                                <option value="dikirim">Dikirim</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-container scrollbar-hide">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table" class="divide-y divide-gray-200">
                                <!-- Orders will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section id="categories-section" class="section-content hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-roboto-slab text-3xl font-bold text-green-darker">Manajemen Kategori</h2>
                        <p class="text-gray-600">Kelola kategori produk</p>
                    </div>
                    <button onclick="openCategoryModal()" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Kategori
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="table-container scrollbar-hide">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>