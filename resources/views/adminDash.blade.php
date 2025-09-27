<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Loka Loka</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
        
        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
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

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
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
                                <span class="text-green-darker font-bold text-sm">A</span>
                            </div>
                            <span class="font-medium">Admin</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl p-2 w-48 border">
                            <div class="px-4 py-2 border-b">
                                <p class="font-semibold text-green-darker">Admin</p>
                                <p class="text-sm text-gray-500">Administrator</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-green-darker hover:bg-gray-100 rounded-lg">Profile</a>
                            <hr class="my-2">
                            <a href="#" class="block px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">Logout</a>
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
                                <p class="text-3xl font-bold text-green-darker" id="total-products">156</p>
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
                                <p class="text-3xl font-bold text-green-darker" id="total-orders">1,250</p>
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
                                <p class="text-3xl font-bold text-green-darker" id="total-users">456</p>
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
                                <p class="text-3xl font-bold text-orange-600" id="pending-orders">24</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Charts -->
                <div class="stats-grid mb-8">
                    <!-- User Statistics Chart -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-roboto text-xl font-semibold text-green-darker">User Statistics</h3>
                            <div class="flex gap-2">
                                <button class="text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600" onclick="updateUserChart('today')">Today</button>
                                <button class="text-xs px-3 py-1 bg-primary text-white rounded-full" onclick="updateUserChart('15days')">15 Days</button>
                                <button class="text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600" onclick="updateUserChart('6months')">6 Months</button>
                                <button class="text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600" onclick="updateUserChart('year')">This Year</button>
                            </div>
                        </div>
                        <div class="mb-4 text-center">
                            <p class="text-3xl font-bold text-green-darker">4,000</p>
                            <p class="text-sm text-gray-500">Total Users</p>
                        </div>
                        <canvas id="userChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Sales Statistics Chart -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-roboto text-xl font-semibold text-green-darker">Sales Statistics</h3>
                            <div class="flex gap-2">
                                <button class="text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600" onclick="updateSalesChart('today')">Today</button>
                                <button class="text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600" onclick="updateSalesChart('15days')">15 Days</button>
                                <button class="text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600" onclick="updateSalesChart('6months')">6 Months</button>
                                <button class="text-xs px-3 py-1 bg-primary text-white rounded-full" onclick="updateSalesChart('year')">This Year</button>
                            </div>
                        </div>
                        <canvas id="salesChart" width="400" height="250"></canvas>
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
                                <!-- Sample data -->
                                <tr>
                                    <td class="px-4 py-3 text-sm">#001</td>
                                    <td class="px-4 py-3 text-sm">John Doe</td>
                                    <td class="px-4 py-3 text-sm font-medium">Rp 150,000</td>
                                    <td class="px-4 py-3"><span class="status-badge status-diproses">diproses</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-500">2025-01-01</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm">#002</td>
                                    <td class="px-4 py-3 text-sm">Jane Smith</td>
                                    <td class="px-4 py-3 text-sm font-medium">Rp 275,000</td>
                                    <td class="px-4 py-3"><span class="status-badge status-dikirim">dikirim</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-500">2025-01-01</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm">#003</td>
                                    <td class="px-4 py-3 text-sm">Bob Wilson</td>
                                    <td class="px-4 py-3 text-sm font-medium">Rp 325,000</td>
                                    <td class="px-4 py-3"><span class="status-badge status-selesai">selesai</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-500">2024-12-31</td>
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
                                <option value="1">Makanan</option>
                                <option value="2">Minuman</option>
                                <option value="3">Fashion</option>
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
                                <option value="1">Makanan</option>
                                <option value="2">Minuman</option>
                                <option value="3">Fashion</option>
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
        let userChart = null;
        let salesChart = null;

        // Sample data
        const sampleProducts = [
            {id: 1, name: "Gudeg Jogja Authentic", category: "Makanan", price: 25000, stock: 50, is_active: true, category_id: 1},
            {id: 2, name: "Kopi Arabica Temanggung", category: "Minuman", price: 45000, stock: 30, is_active: true, category_id: 2},
            {id: 3, name: "Batik Tulis Solo Premium", category: "Fashion", price: 150000, stock: 15, is_active: true, category_id: 3},
            {id: 4, name: "Teh Poci Tegal", category: "Minuman", price: 15000, stock: 75, is_active: true, category_id: 2},
            {id: 5, name: "Keripik Tempe Malang", category: "Makanan", price: 12000, stock: 8, is_active: true, category_id: 1}
        ];

        const sampleOrders = [
            {id: 1, customer: "John Doe", total: 150000, status: "diproses", created_at: "2025-01-01", tracking_number: null},
            {id: 2, customer: "Jane Smith", total: 275000, status: "dikirim", created_at: "2025-01-02", tracking_number: "JNE123456"},
            {id: 3, customer: "Bob Johnson", total: 325000, status: "selesai", created_at: "2025-01-03", tracking_number: "JNT789012"},
            {id: 4, customer: "Alice Wilson", total: 87500, status: "diproses", created_at: "2025-01-04", tracking_number: null},
            {id: 5, customer: "Mike Brown", total: 195000, status: "batal", created_at: "2025-01-05", tracking_number: null}
        ];

        const sampleCategories = [
            {id: 1, name: "Makanan", product_count: 25},
            {id: 2, name: "Minuman", product_count: 15},
            {id: 3, name: "Fashion", product_count: 10}
        ];

        const sampleUsers = [
            {id: 1, name: "John Doe", email: "john@example.com", role: "customer", created_at: "2024-12-01"},
            {id: 2, name: "Jane Smith", email: "jane@example.com", role: "customer", created_at: "2024-12-15"},
            {id: 3, name: "Admin User", email: "admin@lokaloka.com", role: "admin", created_at: "2024-11-15"},
            {id: 4, name: "Bob Wilson", email: "bob@example.com", role: "customer", created_at: "2024-12-20"},
            {id: 5, name: "Alice Brown", email: "alice@example.com", role: "customer", created_at: "2025-01-01"}
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
            initializeCharts();
        });

        // Initialize charts
        function initializeCharts() {
            initUserChart();
            initSalesChart();
        }

        // User Statistics Chart
        function initUserChart() {
            const ctx = document.getElementById('userChart').getContext('2d');
            const userChartData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Users',
                    data: [200, 250, 280, 320, 380, 450, 500, 580, 650, 720, 800, 950],
                    borderColor: '#5c6641',
                    backgroundColor: 'rgba(92, 102, 65, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#5c6641',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1,
                    pointRadius: 3
                }]
            };

            userChart = new Chart(ctx, {
                type: 'line',
                data: userChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.5,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Sales Statistics Chart
        function initSalesChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChartData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales',
                    data: [45000, 52000, 48000, 61000, 55000, 67000, 73000, 69000, 76000, 82000, 89000, 95000],
                    backgroundColor: [
                        '#e0e7ff', '#ddd6fe', '#fef3c7', '#d1fae5', '#fed7d7', '#fde68a',
                        '#5c6641', '#a78bfa', '#34d399', '#f87171', '#60a5fa', '#fbbf24'
                    ],
                    borderColor: '#5c6641',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            };

            salesChart = new Chart(ctx, {
                type: 'bar',
                data: salesChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.2,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'K';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update chart functions
        function updateUserChart(period) {
            // Update active button
            document.querySelectorAll('[onclick*="updateUserChart"]').forEach(btn => {
                btn.className = 'text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600';
            });
            event.target.className = 'text-xs px-3 py-1 bg-primary text-white rounded-full';

            // Update chart data based on period
            let newData, newLabels;
            switch(period) {
                case 'today':
                    newLabels = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'];
                    newData = [12, 19, 25, 32, 28, 35, 42];
                    break;
                case '15days':
                    newLabels = Array.from({length: 15}, (_, i) => `Day ${i + 1}`);
                    newData = [45, 52, 48, 61, 55, 67, 73, 69, 76, 82, 89, 95, 87, 91, 98];
                    break;
                case '6months':
                    newLabels = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    newData = [450, 580, 650, 720, 800, 950];
                    break;
                case 'year':
                default:
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    newData = [200, 250, 280, 320, 380, 450, 500, 580, 650, 720, 800, 950];
            }

            userChart.data.labels = newLabels;
            userChart.data.datasets[0].data = newData;
            userChart.update();
        }

        function updateSalesChart(period) {
            // Update active button
            document.querySelectorAll('[onclick*="updateSalesChart"]').forEach(btn => {
                btn.className = 'text-xs px-3 py-1 bg-gray-100 rounded-full text-gray-600';
            });
            event.target.className = 'text-xs px-3 py-1 bg-primary text-white rounded-full';

            // Update chart data based on period
            let newData, newLabels, newColors;
            switch(period) {
                case 'today':
                    newLabels = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'];
                    newData = [5000, 8000, 12000, 18000, 15000, 22000, 25000];
                    newColors = ['#e0e7ff', '#ddd6fe', '#fef3c7', '#5c6641', '#fed7d7', '#fde68a', '#d1fae5'];
                    break;
                case '15days':
                    newLabels = Array.from({length: 15}, (_, i) => `Day ${i + 1}`);
                    newData = [35000, 42000, 38000, 51000, 45000, 57000, 63000, 59000, 66000, 72000, 79000, 85000, 77000, 81000, 88000];
                    newColors = Array(15).fill().map((_, i) => i === 6 ? '#5c6641' : '#e0e7ff');
                    break;
                case '6months':
                    newLabels = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    newData = [73000, 69000, 76000, 82000, 89000, 95000];
                    newColors = ['#e0e7ff', '#ddd6fe', '#fef3c7', '#d1fae5', '#fed7d7', '#5c6641'];
                    break;
                case 'year':
                default:
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    newData = [45000, 52000, 48000, 61000, 55000, 67000, 73000, 69000, 76000, 82000, 89000, 95000];
                    newColors = [
                        '#e0e7ff', '#ddd6fe', '#fef3c7', '#d1fae5', '#fed7d7', '#fde68a',
                        '#5c6641', '#a78bfa', '#34d399', '#f87171', '#60a5fa', '#fbbf24'
                    ];
            }

            salesChart.data.labels = newLabels;
            salesChart.data.datasets[0].data = newData;
            salesChart.data.datasets[0].backgroundColor = newColors;
            salesChart.update();
        }

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
            // Update stats
            document.getElementById('total-products').textContent = sampleProducts.length;
            document.getElementById('total-orders').textContent = sampleOrders.length;
            document.getElementById('total-users').textContent = sampleUsers.length;
            document.getElementById('pending-orders').textContent = sampleOrders.filter(o => o.status === 'diproses').length;
        }

        // Load products data
        function loadProductsData() {
            const tbody = document.getElementById('products-table');
            tbody.innerHTML = sampleProducts.map(product => `
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">${product.category}</td>
                    <td class="px-4 py-3 text-sm font-medium">Rp ${product.price.toLocaleString()}</td>
                    <td class="px-4 py-3 text-sm ${product.stock < 10 ? 'text-red-600 font-semibold' : 'text-gray-900'}">${product.stock}</td>
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
        }

        // Load users data
        function loadUsersData() {
            const tbody = document.getElementById('users-table');
            tbody.innerHTML = sampleUsers.map(user => `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium">${user.id}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary rounded-full mr-3 flex items-center justify-center">
                                <span class="text-white text-xs font-bold">${user.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <span class="text-sm">${user.name}</span>
                        </div>
                    </td>
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
                const formData = new FormData(e.target);
                
                if (currentProductId) {
                    // Update existing product
                    const productIndex = sampleProducts.findIndex(p => p.id === currentProductId);
                    if (productIndex !== -1) {
                        sampleProducts[productIndex].name = document.getElementById('product-name').value;
                        sampleProducts[productIndex].price = parseInt(document.getElementById('product-price').value);
                        sampleProducts[productIndex].stock = parseInt(document.getElementById('product-stock').value);
                        sampleProducts[productIndex].category_id = parseInt(document.getElementById('product-category').value);
                    }
                    alert('Produk berhasil diupdate!');
                } else {
                    // Add new product
                    const newProduct = {
                        id: sampleProducts.length + 1,
                        name: document.getElementById('product-name').value,
                        category: sampleCategories.find(c => c.id == document.getElementById('product-category').value)?.name || 'Unknown',
                        price: parseInt(document.getElementById('product-price').value),
                        stock: parseInt(document.getElementById('product-stock').value),
                        is_active: true,
                        category_id: parseInt(document.getElementById('product-category').value)
                    };
                    sampleProducts.push(newProduct);
                    alert('Produk berhasil ditambahkan!');
                }
                
                closeProductModal();
                loadProductsData();
                loadOverviewData();
            });

            // Category form
            document.getElementById('category-form').addEventListener('submit', (e) => {
                e.preventDefault();
                
                if (currentCategoryId) {
                    // Update existing category
                    const categoryIndex = sampleCategories.findIndex(c => c.id === currentCategoryId);
                    if (categoryIndex !== -1) {
                        sampleCategories[categoryIndex].name = document.getElementById('category-name').value;
                    }
                    alert('Kategori berhasil diupdate!');
                } else {
                    // Add new category
                    const newCategory = {
                        id: sampleCategories.length + 1,
                        name: document.getElementById('category-name').value,
                        product_count: 0
                    };
                    sampleCategories.push(newCategory);
                    alert('Kategori berhasil ditambahkan!');
                }
                
                closeCategoryModal();
                loadCategoriesData();
            });

            // Order status form
            document.getElementById('order-status-form').addEventListener('submit', (e) => {
                e.preventDefault();
                
                const orderIndex = sampleOrders.findIndex(o => o.id === currentOrderId);
                if (orderIndex !== -1) {
                    sampleOrders[orderIndex].status = document.getElementById('order-status').value;
                    sampleOrders[orderIndex].tracking_number = document.getElementById('tracking-number').value;
                }
                
                const newStatus = document.getElementById('order-status').value;
                alert(`Status pesanan berhasil diupdate ke: ${newStatus}`);
                closeOrderStatusModal();
                loadOrdersData();
                loadOverviewData();
            });

            // Search functionality
            const productSearch = document.getElementById('product-search');
            if (productSearch) {
                productSearch.addEventListener('input', filterProducts);
            }
            
            const orderSearch = document.getElementById('order-search');
            if (orderSearch) {
                orderSearch.addEventListener('input', filterOrders);
            }
            
            const userSearch = document.getElementById('user-search');
            if (userSearch) {
                userSearch.addEventListener('input', filterUsers);
            }
            
            // Filter functionality
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', filterProducts);
            }
            
            const statusFilter = document.getElementById('status-filter');
            if (statusFilter) {
                statusFilter.addEventListener('change', filterOrders);
            }
            
            const roleFilter = document.getElementById('role-filter');
            if (roleFilter) {
                roleFilter.addEventListener('change', filterUsers);
            }
        }

        // Filter functions
        function filterProducts() {
            const searchTerm = document.getElementById('product-search').value.toLowerCase();
            const categoryFilter = document.getElementById('category-filter').value;
            
            let filteredProducts = sampleProducts.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(searchTerm);
                const matchesCategory = !categoryFilter || product.category_id == categoryFilter;
                return matchesSearch && matchesCategory;
            });

            const tbody = document.getElementById('products-table');
            tbody.innerHTML = filteredProducts.map(product => `
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">${product.category}</td>
                    <td class="px-4 py-3 text-sm font-medium">Rp ${product.price.toLocaleString()}</td>
                    <td class="px-4 py-3 text-sm ${product.stock < 10 ? 'text-red-600 font-semibold' : 'text-gray-900'}">${product.stock}</td>
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

        function filterOrders() {
            const searchTerm = document.getElementById('order-search').value.toLowerCase();
            const statusFilter = document.getElementById('status-filter').value;
            
            let filteredOrders = sampleOrders.filter(order => {
                const matchesSearch = order.customer.toLowerCase().includes(searchTerm) || 
                                    String(order.id).padStart(3, '0').includes(searchTerm);
                const matchesStatus = !statusFilter || order.status === statusFilter;
                return matchesSearch && matchesStatus;
            });

            const tbody = document.getElementById('orders-table');
            tbody.innerHTML = filteredOrders.map(order => `
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

        function filterUsers() {
            const searchTerm = document.getElementById('user-search').value.toLowerCase();
            const roleFilter = document.getElementById('role-filter').value;
            
            let filteredUsers = sampleUsers.filter(user => {
                const matchesSearch = user.name.toLowerCase().includes(searchTerm) || 
                                    user.email.toLowerCase().includes(searchTerm);
                const matchesRole = !roleFilter || user.role === roleFilter;
                return matchesSearch && matchesRole;
            });

            const tbody = document.getElementById('users-table');
            tbody.innerHTML = filteredUsers.map(user => `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium">${user.id}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary rounded-full mr-3 flex items-center justify-center">
                                <span class="text-white text-xs font-bold">${user.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <span class="text-sm">${user.name}</span>
                        </div>
                    </td>
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

        // Action functions
        function editProduct(id) {
            openProductModal(id);
        }

        function deleteProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                const productIndex = sampleProducts.findIndex(p => p.id === id);
                if (productIndex !== -1) {
                    sampleProducts.splice(productIndex, 1);
                }
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
                const categoryIndex = sampleCategories.findIndex(c => c.id === id);
                if (categoryIndex !== -1) {
                    sampleCategories.splice(categoryIndex, 1);
                }
                alert('Kategori berhasil dihapus!');
                loadCategoriesData();
            }
        }

        function viewOrderDetails(id) {
            const order = sampleOrders.find(o => o.id === id);
            if (order) {
                alert(`Detail Pesanan #${String(id).padStart(3, '0')}:\nCustomer: ${order.customer}\nTotal: Rp ${order.total.toLocaleString()}\nStatus: ${order.status}\nTanggal: ${order.created_at}`);
            }
        }

        function viewUserDetails(id) {
            const user = sampleUsers.find(u => u.id === id);
            if (user) {
                alert(`Detail User #${id}:\nNama: ${user.name}\nEmail: ${user.email}\nRole: ${user.role}\nBergabung: ${user.created_at}`);
            }
        }

        function toggleUserRole(id) {
            if (confirm('Apakah Anda yakin ingin mengubah role user ini?')) {
                const userIndex = sampleUsers.findIndex(u => u.id === id);
                if (userIndex !== -1) {
                    sampleUsers[userIndex].role = sampleUsers[userIndex].role === 'admin' ? 'customer' : 'admin';
                }
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