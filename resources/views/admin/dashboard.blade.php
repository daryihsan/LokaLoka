<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | LokaLoka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* CSS Tambahan untuk konsistensi */
        .sidebar { background-color: #1a202c; } 
        .text-green-lime { color: #a3e635; } 
        .text-green-darker { color: #10b981; } 
        .status-badge {
            display: inline-flex; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
        }
        /* Status Warna */
        .status-selesai, .status-approved { background-color: #d1fae5; color: #059669; }
        .status-diproses { background-color: #fef3c7; color: #d97706; }
        .status-dikirim { background-color: #bfdbfe; color: #2563eb; }
        .status-dibatalkan, .status-batal, .status-pending { background-color: #fee2e2; color: #ef4444; }
        .modal { transition: opacity 0.25s ease; }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen">

    <aside class="sidebar w-64 p-6 flex flex-col fixed h-full shadow-2xl">
        <div class="mb-10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-3xl font-bold text-white hover:text-green-lime transition duration-150">
                <span class="text-green-lime">Loka</span>Loka
            </a>
            <p class="text-xs text-gray-500 mt-1">Admin Dashboard</p>
        </div>

        <nav class="flex-grow">
            <ul>
                @php
                    $navItems = [
                        'overview' => 'Dashboard Overview',
                        'products' => 'Manajemen Produk',
                        'categories' => 'Manajemen Kategori',
                        'orders' => 'Manajemen Pesanan',
                        'users' => 'Manajemen Pengguna'
                    ];
                @endphp
                @foreach ($navItems as $key => $label)
                <li class="mb-2">
                    <a href="{{ route('admin.dashboard', ['section' => $key]) }}" 
                       class="block px-4 py-2 rounded-lg text-sm transition duration-150 
                       @if($section == $key) bg-gray-700 text-white font-bold @else text-gray-400 hover:bg-gray-700 hover:text-white @endif">
                       {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>
        
        <div class="mt-auto">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-sm text-red-400 hover:bg-gray-700 hover:text-red-300 transition duration-150">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-8 ml-64 overflow-y-auto">
        <header class="flex justify-between items-center pb-6 border-b border-gray-200 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 capitalize">{{ str_replace('-', ' ', $section) }}</h1>
            <div class="text-sm text-gray-600">
                Selamat datang, Admin!
            </div>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">Terdapat kesalahan pada input form.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <section id="overview-section" class="space-y-6" @if($section !== 'overview') style="display: none;" @endif>
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Revenue</p>
                            <p class="text-2xl font-bold text-green-darker mt-1">
                                Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-4xl text-green-lime">💸</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">
                                {{ number_format($stats['total_orders'] ?? 0) }}
                            </p>
                        </div>
                        <span class="text-4xl text-blue-500">📦</span>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <div>
                        <p class="text-sm text-gray-500">Total Customer</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ number_format($stats['total_customers'] ?? 0) }}
                        </p>
                    </div>
                    <span class="text-4xl text-yellow-500">👥</span>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">
                                {{ number_format($stats['total_products'] ?? 0) }}
                            </p>
                        </div>
                        <span class="text-4xl text-purple-500">🛍️</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Users Belum Approve</p>
                            <p class="text-2xl font-bold text-red-500 mt-1">
                                {{ number_format($stats['unapproved_users'] ?? 0) }}
                            </p>
                        </div>
                        <span class="text-4xl text-red-500">🚨</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Penjualan Berdasarkan Kategori</h3>
                    <div class="h-80">
                        <canvas id="salesByCategoryChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Registrasi Customer Baru Bulanan</h3>
                    <div class="h-80">
                        <canvas id="newUsersChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">5 Pesanan Terbaru</h3>
                    <div class="space-y-3">
                        @forelse ($recentOrders as $order)
                            <div class="flex justify-between items-center border-b pb-2">
                                @php
                                    $itemCount = $order->orderItems->count();
                                    $firstItemName = $order->orderItems->first()->product->name ?? 'Produk Dihapus';
                                    $summaryText = $itemCount > 1 ? 
                                                $firstItemName . ' dkk. (' . $itemCount . ' item)' : 
                                                $firstItemName;
                                @endphp
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} - {{ $order->user->name ?? 'User Dihapus' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $summaryText }} | Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    {{-- Link ke detail pembayaran yang memuat item --}}
                                    <a href="{{ route('payment.other', $order->id) }}" target="_blank" 
                                    class="text-xs text-blue-500 hover:text-blue-700 transition">
                                    Detail
                                    </a>
                                    <span class="status-badge status-{{
                                        str_replace('','', strtolower($order->status)) }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 text-sm py-4">Tidak ada pesanan terbaru.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Pengguna Baru Belum Disetujui ({{ $stats['unapproved_users'] }})</h3>
                    <div class="space-y-3">
                        @php
                            // Ambil 5 user 'pending' terbaru untuk ditampilkan di sini
                            $pendingUsers = \App\Models\Users::where('approved', '0')->latest()->take(5)->get();
                        @endphp

                        @forelse ($pendingUsers as $user)
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded transition">
                                        Approve
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 text-sm py-4">Tidak ada pengguna yang perlu disetujui.</p>
                        @endforelse
                        
                        @if ($stats['unapproved_users'] > 5)
                            <div class="text-center pt-2">
                                <a href="{{ route('admin.dashboard', ['section' => 'users']) }}" class="text-sm text-blue-500 hover:text-blue-700 font-medium">Lihat Semua ({{ $stats['unapproved_users'] }} total) &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </section>

        <section id="products-section" @if($section !== 'products') style="display: none;" @endif>
            <div class="flex justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Daftar Produk</h2>
                <button onclick="openProductModal('add')" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition">
                    + Tambah Produk Baru
                </button>
            </div>
            
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200 overflow-x-auto scrollbar-hide">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="products-table" class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $product->image_url }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/40';" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded-lg mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $product->stock < 5 ? 'text-red-500 font-semibold' : 'text-gray-900' }}">{{ $product->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge status-{{ $product->is_active ? 'selesai' : 'batal' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editProduct({{ json_encode($product) }})" class="text-blue-600 hover:text-blue-900 transition mr-2">Edit</button>
                                    <button onclick="deleteAction('product', '{{ $product->id }}')" class="text-red-600 hover:text-red-900 transition">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada produk ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $products->appends(['section' => 'products'])->links() }}
                </div>
            </div>
        </section>

        <section id="categories-section" @if($section !== 'categories') style="display: none;" @endif>
            <div class="flex justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Daftar Kategori</h2>
                <button onclick="openCategoryModal('add')" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition">
                    + Tambah Kategori Baru
                </button>
            </div>
            
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200 overflow-x-auto scrollbar-hide">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="categories-table" class="bg-white divide-y divide-gray-200">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($category->products_count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editCategory({{ json_encode($category) }})" class="text-blue-600 hover:text-blue-900 transition mr-2">Edit</button>
                                    <button onclick="deleteAction('category', '{{ $category->id }}')" class="text-red-600 hover:text-red-900 transition">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada kategori ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $categories->appends(['section' => 'categories'])->links() }}
                </div>
            </div>
        </section>

        <section id="orders-section" @if($section !== 'orders') style="display: none;" @endif>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Daftar Pesanan</h2>
            
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200 overflow-x-auto scrollbar-hide">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table" class="bg-white divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? 'User Dihapus' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $itemCount = $order->orderItems->count();
                                        $totalItems = $order->orderItems->sum('qty');
                                        $firstItemName = $order->orderItems->first()->product->name ?? 'Produk Dihapus';
                                        $summary = $itemCount > 1 
                                                ? $firstItemName . ' dkk. (' . $itemCount . ' jenis, ' . $totalItems . ' total)' 
                                                : $firstItemName . ' (Total: Rp ' . number_format($order->total, 0, ',', '.') . ')';
                                    @endphp
                                    <div title="Total: Rp {{ number_format($order->total, 0, ',', '.') }}">
                                        {{ $summary }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge status-{{ str_replace(' ', '-', strtolower($order->status)) }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('payment.other', $order->id) }}" target="_blank" 
                                        class="text-green-600 hover:text-green-900 transition">
                                        Detail Pesanan
                                    </a>
                                    <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')" class="text-blue-600
                                        hover:text-blue-900 transition">Ubah Status</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pesanan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $orders->appends(['section' => 'orders'])->links() }}
                </div>
            </div>
        </section>

        <section id="users-section" @if($section !== 'users') style="display: none;" @endif>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Daftar Pengguna (Customer & Admin)</h2>
            
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200 overflow-x-auto scrollbar-hide">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Akun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="users-table" class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium capitalize">{{ $user->role }}</td>
                                
                                {{-- START PERBAIKAN STATUS AKUN --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->approved == 1)
                                        <span class="status-badge status-approved">
                                            Approved
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                {{-- END PERBAIKAN STATUS AKUN --}}
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($user->approved == 0)
                                        <button onclick="approveAction('{{ $user->id }}')" class="text-green-600 hover:text-green-900 transition mr-2">Setujui</button>
                                    @else
                                        <button onclick="editUser({{ json_encode($user) }})" class="text-blue-600 hover:text-blue-900 transition mr-2">Edit</button>
                                    @endif
                                    <button onclick="deleteAction('user', '{{ $user->id }}')" class="text-red-600 hover:text-red-900 transition">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $users->appends(['section' => 'users'])->links() }}
                </div>
            </div>
        </section>


        <form id="delete-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <form id="approve-form" method="POST" style="display: none;">
            @csrf
        </form>

    </main>
    
    <div id="product-modal" class="modal fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 transform transition-transform duration-300 scale-90">
            <h3 id="product-modal-title" class="text-2xl font-semibold mb-4 text-gray-800">Tambah Produk</h3>
            <form id="product-form" method="POST" action="{{ route('admin.products.store') }}">
                @csrf
                <input type="hidden" name="_method" id="product-method" value="POST">
                <input type="hidden" name="id" id="product-id">

                <div class="mb-4">
                    <label for="product-name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" id="product-name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="product-price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" id="product-price" name="price" required min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label for="product-stock" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" id="product-stock" name="stock" required min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="product-category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="product-category_id" name="category_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="mb-4">
                    <label for="product-image_url" class="block text-sm font-medium text-gray-700">URL Gambar (Online Link)</label>
                    <input type="url" id="product-image_url" name="image_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="mb-4">
                    <label for="product-description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="product-description" name="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                </div>
                <div id="product-active-container" class="mb-4 hidden">
                    <input type="checkbox" id="product-is_active" name="is_active" value="1" class="rounded border-gray-300 text-green-darker shadow-sm focus:border-green-darker focus:ring focus:ring-green-darker focus:ring-opacity-50">
                    <label for="product-is_active" class="text-sm font-medium text-gray-700">Aktif</label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('product-modal')" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <div id="category-modal" class="modal fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6 transform transition-transform duration-300 scale-90">
            <h3 id="category-modal-title" class="text-2xl font-semibold mb-4 text-gray-800">Tambah Kategori</h3>
            <form id="category-form" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="hidden" name="_method" id="category-method" value="POST">
                
                <div class="mb-4">
                    <label for="category-name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" id="category-name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('category-modal')" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="user-modal" class="modal fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6 transform transition-transform duration-300 scale-90">
            <h3 id="user-modal-title" class="text-2xl font-semibold mb-4 text-gray-800">Edit Pengguna</h3>
            <form id="user-form" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT"> 
                <input type="hidden" name="status" id="user-status-input">
                
                <div class="mb-4">
                    <label for="user-name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="user-name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="mb-4">
                    <label for="user-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="user-email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="mb-4">
                    <label for="user-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="user-role" name="role" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('user-modal')" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="status-modal" class="modal fixed inset-0 bg-gray-600
    bg-opacity-75 flex items-center justify-center opacity-0
    pointer-events-none z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6
        transform transition-transform duration-300 scale-90">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800" id="status-modal-title">
                Ubah Status Pesanan #<span id="order-id-display"></span>
            </h3>
            <form id="status-form" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="status-order-id">
                
                <div class="mb-4">
                    <label for="new_status" class="block text-sm font-medium text-gray-700">Pilih Status Baru</label>
                    <select id="new_status" name="status" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="diproses">Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('status-modal')" 
                            class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2
                            bg-blue-600 text-white rounded-md
                            hover:bg-blue-700">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // 1. Chart Penjualan Berdasarkan Kategori (Bar Chart)
        const salesData = @json($salesByCategory);
        const salesLabels = salesData.map(item => item.name);
        const salesTotals = salesData.map(item => item.total_sales);

        const salesCtx = document.getElementById('salesByCategoryChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: salesLabels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: salesTotals,
                        backgroundColor: '#10b981', // green-darker
                        borderColor: '#059669',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }
        
        // 2. Chart Registrasi Customer Baru (Line Chart)
        const userData = @json($newUsersMonthly);
        const userLabels = userData.map(item => {
            // Ubah format 'YYYY-MM' menjadi 'MMM YY' (e.g., '2025-09' -> 'Sep 25')
            const [year, month] = item.month_year.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
        });
        const userCounts = userData.map(item => item.total_users);
        
        const userCtx = document.getElementById('newUsersChart');
        if (userCtx) {
            new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: userLabels,
                    datasets: [{
                        label: 'Registrasi Baru',
                        data: userCounts,
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, suggestedMax: Math.max(...userCounts) + 2 } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // =========================================================
        // LOGIKA MODAL & CRUD UNIVERSAL
        // =========================================================
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('div').classList.remove('scale-90');
            modal.querySelector('div').classList.add('scale-100');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-90');
        }
        
        // ---- PRODUK MODAL ----
        function openProductModal(mode, product = {}) {
            const form = document.getElementById('product-form');
            const methodInput = document.getElementById('product-method');
            const title = document.getElementById('product-modal-title');
            const isActiveContainer = document.getElementById('product-active-container');
            const categorySelect = document.getElementById('product-category_id');

            if (mode === 'add') {
                title.textContent = 'Tambah Produk Baru';
                form.action = "{{ route('admin.products.store') }}";
                methodInput.value = 'POST';
                form.reset();
                isActiveContainer.classList.add('hidden');
                if(categorySelect.options.length > 0) {
                    categorySelect.value = categorySelect.options[0].value;
                }
            } else if (mode === 'edit') {
                title.textContent = 'Edit Produk: ' + product.name;
                form.action = `/admin/products/${product.id}/update`;
                methodInput.value = 'PUT';
                isActiveContainer.classList.remove('hidden');

                document.getElementById('product-name').value = product.name;
                document.getElementById('product-price').value = product.price;
                document.getElementById('product-stock').value = product.stock;
                document.getElementById('product-image_url').value = product.image_url || '';
                document.getElementById('product-description').value = product.description || '';
                document.getElementById('product-category_id').value = product.category_id;
                document.getElementById('product-is_active').checked = product.is_active;
            }

            openModal('product-modal');
        }

        function editProduct(product) {
            openProductModal('edit', product);
        }

        // ---- KATEGORI MODAL ----
        function openCategoryModal(mode, category = {}) {
            const form = document.getElementById('category-form');
            const methodInput = document.getElementById('category-method');
            const title = document.getElementById('category-modal-title');
            const nameInput = document.getElementById('category-name');

            if (mode === 'add') {
                title.textContent = 'Tambah Kategori Baru';
                form.action = "{{ route('admin.categories.store') }}";
                methodInput.value = 'POST';
                form.reset();
            } else if (mode === 'edit') {
                title.textContent = 'Edit Kategori: ' + category.name;
                form.action = `/admin/categories/${category.id}/update`;
                methodInput.value = 'PUT';
                nameInput.value = category.name;
            }

            openModal('category-modal');
        }

        function editCategory(category) {
            openCategoryModal('edit', category);
        }

        // ---- LOGIKA EDIT USER (DIPERBAIKI) ----
        function editUser(user) {
            const form = document.getElementById('user-form');
            const title = document.getElementById('user-modal-title');
            
            title.textContent = 'Edit Pengguna: ' + user.name;
            
            // Atur Action ke rute update (Rute ini diasumsikan ada di web.php: Route::put('/admin/users/{id}/update', ...) )
            form.action = `/admin/users/${user.id}/update`;
            form.querySelector('input[name="_method"]').value = 'PUT';

            // Isi nilai form
            document.getElementById('user-name').value = user.name;
            document.getElementById('user-email').value = user.email;
            document.getElementById('user-role').value = user.role;
            // Kirim status yang ada agar tidak hilang saat update
            document.getElementById('user-status-input').value = user.status || 'approved'; 

            openModal('user-modal');
        }


        // ---- LOGIKA DELETE UNIVERSAL ----
        function deleteAction(type, id) {
            let routeUrl;
            let message;
            
            if (type === 'product') {
                routeUrl = `/admin/products/${id}`;
                message = 'Yakin ingin menghapus Produk ID: ' + id + '?';
            } else if (type === 'category') {
                routeUrl = `/admin/categories/${id}`;
                message = 'Yakin ingin menghapus Kategori ID: ' + id + '? Semua produk mungkin terpengaruh.';
            } else if (type === 'user') {
                routeUrl = `/admin/users/${id}`;
                message = 'Yakin ingin menghapus Pengguna ID: ' + id + '?';
            } else {
                return;
            }

            if (confirm(message)) {
                const form = document.getElementById('delete-form');
                form.action = routeUrl; 
                form.submit();
            }
        }
        
        // ---- LOGIKA APPROVE USER ----
        function approveAction(id) {
            if (confirm('Yakin ingin MENYETUJUI Pengguna ID: ' + id + ' sebagai Customer? Pengguna ini akan bisa login.')) {
                const form = document.getElementById('approve-form');
                form.action = `/admin/users/${id}/approve`;
                form.submit();
            }
        }

        // ---- LOGIKA UPDATE STATUS ORDER ----
        // function openStatusModal(id, currentStatus) {
        //     const newStatus = prompt(`Ubah status pesanan #${id}. Status saat ini: ${currentStatus}. Masukkan status baru (diproses, dikirim, selesai, dibatalkan):`);
            
        //     if (newStatus && ['diproses', 'dikirim', 'selesai', 'dibatalkan'].includes(newStatus.toLowerCase())) {
        //         const form = document.createElement('form');
        //         form.method = 'POST';
        //         form.action = `/admin/orders/${id}/status`;

        //         const csrf = document.createElement('input');
        //         csrf.type = 'hidden';
        //         csrf.name = '_token';
        //         csrf.value = '{{ csrf_token() }}';
        //         form.appendChild(csrf);

        //         const method = document.createElement('input');
        //         method.type = 'hidden';
        //         method.name = '_method';
        //         method.value = 'PUT';
        //         form.appendChild(method);

        //         const statusInput = document.createElement('input');
        //         statusInput.type = 'hidden';
        //         statusInput.name = 'status';
        //         statusInput.value = newStatus.toLowerCase();
        //         form.appendChild(statusInput);

        //         document.body.appendChild(form);
        //         form.submit();
        //     } else if (newStatus !== null) {
        //         alert('Input status tidak valid. Status harus salah satu dari: diproses, dikirim, selesai, dibatalkan.');
        //     }
        // }
        function openStatusModal(orderId, currentStatus) {
            const paddedId = String(orderId).padStart(4, '0');
            
            // Setel elemen di modal
            document.getElementById('order-id-display').textContent = paddedId;
            document.getElementById('status-order-id').value = orderId;
            document.getElementById('new_status').value = currentStatus.toLowerCase();

            // Setel action form
            const form = document.getElementById('status-form');
            form.action = '/admin/orders/' + orderId + '/status';
            
            // Tampilkan modal
            openModal('status-modal');
        }
    </script>
</body>
</html>