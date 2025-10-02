<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loka Loka - Marketplace Produk Lokal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-roboto-slab { font-family: 'Roboto Slab', serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-open-sans { font-family: 'Open Sans', sans-serif; }
        @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-left { animation: fadeInLeft 0.6s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .bg-primary { background-color: #5c6641; }
        .bg-primary-dark { background-color: #414833; }
        .bg-accent { background-color: #A6A604; }
        .text-green-darker { color: #333D29; }
        .text-green-dark { color: #656D4A; }
        .text-green-olive { color: #A4AC86; }
        .text-brown-dark { color: #5E0E0E; }
        .text-accent { color: #A6A604; }
        .border-green-light { border-color: #C2C5AA; }
    </style>
</head>
<body class="min-h-screen text-green-darker font-open-sans bg-white">
    
    <header class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold text-lg">L</span>
                </div>
                <a href="{{ route('homepage') }}" class="font-roboto-slab text-3xl font-bold tracking-wide text-white hover:text-accent transition">
                    Loka Loka
                </a>
            </div>
            
            <form id="searchForm" method="GET" action="{{ route('searchfilter') }}" class="flex-1 mx-8 max-w-2xl">
                <div class="relative">
                    <input type="text" name="q" placeholder="Cari produk lokal terbaik..."
                        class="w-full rounded-2xl pl-12 pr-4 py-3 text-green-darker focus:outline-none focus:ring-2 focus:ring-accent border-0 shadow-lg"
                        required>
                    <button type="submit" class="absolute left-3 top-3.5 h-5 w-5 text-green-dark" style="background: none; border: none; padding: 0;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
            
            <div class="flex gap-3">
                <a href="{{ route('cart.show') }}" class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl p-3 flex items-center gap-2" title="Keranjang Belanja">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                    </svg>
                    <span class="hidden md:inline">Keranjang</span>
                </a>
                
                <div class="relative">
                    <button class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl p-3 flex items-center gap-2" onclick="toggleUserMenu()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="hidden md:inline">{{ $user->name ?? 'User' }}</span>
                    </button>
                    
                    <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl p-2 w-48 border z-50">
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
    
    @if (session('success'))
    <div class="max-w-7xl mx-auto p-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if (session('error'))
    <div class="max-w-7xl mx-auto p-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    </div>
    @endif
    
    <div class="max-w-7xl mx-auto">
        <section class="p-6">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="font-roboto text-2xl font-bold text-green-darker">Produk Terlaris</h2>
                <span class="text-2xl">🔥</span>
            </div>
            
            <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" id="hot-products">
                @forelse ($hotProducts as $i => $product)
                    <div class="min-w-[220px] bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border hover:-translate-y-1 opacity-0 animate-fade-in-left"
                         style="animation-delay: {{ $i * 100 }}ms">
                        <a href="{{ route('product.show', $product->id) }}" class="block">
                            <div class="p-0">
                                <div class="relative">
                                    <div class="h-32 w-full rounded-t-2xl relative overflow-hidden bg-gray-100">
                                        <img src="{{ $product->image_url }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text={{ urlencode($product->name) }}';" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @if($product->discount_percent ?? null)
                                        <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold">-{{ $product->discount_percent }}%</div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-open-sans font-semibold text-green-darker mb-2 line-clamp-2">{{ $product->name }}</h3>
                                        
                                        <div class="flex items-center gap-1 mb-2">
                                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <span class="text-xs text-gray-600">{{ number_format($product->rating ?? 4.5, 1) }}</span>
                                            <span class="text-xs text-gray-500">• Terjual {{ number_format($product->total_sold ?? 0) }}+</span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-bold text-green-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <button onclick="addToCart(event, {{ $product->id }})" class="bg-primary hover:bg-primary-dark text-white rounded-full p-2" title="Tambah ke keranjang">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600 py-4">Tidak ada produk terlaris saat ini.</p>
                @endforelse
            </div>
        </section>
        
        <section class="p-6">
            <h2 class="font-roboto text-2xl font-bold text-green-darker mb-6">Jelajahi Kategori</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4" id="categories">
                @forelse ($categories as $i => $category)
                <a href="{{ route('searchfilter', ['category' => $category->id]) }}" class="block">
                    <div class="flex flex-col items-center justify-center bg-white rounded-2xl p-6 border-2 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer group opacity-0 animate-fade-in-up"
                         style="animation-delay: {{ $i * 50 }}ms; border-color: #C2C5AA;">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200"
                             style="background-color: #C2C5AA20; border: 2px solid #A4AC86;">
                            <span class="text-2xl">🛍️</span>
                        </div>
                        <p class="font-open-sans text-sm font-semibold text-green-darker text-center">{{ $category->name }}</p>
                    </div>
                </a>
                @empty
                    <p class="text-gray-600">Tidak ada kategori ditemukan.</p>
                @endforelse
            </div>
        </section>
        
        <section class="p-6">
            <h2 class="font-roboto text-2xl font-bold text-green-darker mb-6">Rekomendasi Untukmu</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="recommended-products">
                @forelse ($recommendedProducts as $i => $product)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border hover:-translate-y-1 opacity-0 animate-fade-in-up"
                         style="animation-delay: {{ $i * 50 }}ms">
                        <a href="{{ route('product.show', $product->id) }}" class="block">
                            <div class="p-0">
                                <div class="relative">
                                    <div class="h-40 w-full rounded-t-2xl relative overflow-hidden bg-gray-100">
                                        <img src="{{ $product->image_url }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text={{ urlencode($product->name) }}';" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-open-sans font-semibold text-green-darker mb-2 line-clamp-2">{{ $product->name }}</h3>
                                        
                                        <div class="flex items-center gap-1 mb-2">
                                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <span class="text-xs text-gray-600">{{ number_format($product->rating ?? 4.0, 1) }}</span>
                                            <span class="text-xs text-gray-500">• Stok: {{ $product->stock }}</span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-bold text-green-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <button onclick="addToCart(event, {{ $product->id }})" class="bg-primary hover:bg-primary-dark text-white rounded-full p-2" title="Tambah ke keranjang">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600">Tidak ada produk rekomendasi saat ini.</p>
                @endforelse
            </div>
        </section>
    </div>
    
    <footer class="bg-primary text-white p-8 mt-12">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold">L</span>
                </div>
                <h3 class="font-roboto-slab text-xl font-bold">Loka Loka</h3>
            </div>
            <p class="font-open-sans text-sm opacity-80">
                Marketplace produk lokal terpercaya untuk mendukung UMKM Indonesia
            </p>
        </div>
    </footer>
    
    <div id="toast-notification" class="fixed top-5 right-5 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
        <div id="toast-message" class="font-medium text-green-darker"></div>
    </div>

    <script>
        // Fungsionalitas AJAX dan UI
        
        // 1. Tombol Add to Cart
        function addToCart(event, productId) {
            event.preventDefault(); // Mencegah pindah halaman karena tombol ada di dalam tag <a>
            
            // Cek apakah user sudah login (asumsi Session::has('user_id') di CartController)
            @guest
                showToast('error', 'Silakan login terlebih dahulu untuk menambah ke keranjang!');
                return;
            @endguest
            
            // Mengirim data menggunakan AJAX
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || 'Produk berhasil ditambahkan ke keranjang!');
                } else if (data.error) {
                    showToast('error', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan saat menghubungi server.');
            });
        }
        
        // 2. Tampilkan Toast (Pop-up)
        function showToast(type, message) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            if (type === 'success') {
                toast.style.backgroundColor = '#d1fae5';
                toastMessage.style.color = '#059669';
            } else if (type === 'error') {
                toast.style.backgroundColor = '#fee2e2';
                toastMessage.style.color = '#dc2626';
            }
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-x-full');
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 3000);
        }

        // 3. Toggle User Menu (Diambil dari file Anda)
        function toggleUserMenu() {
            var dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // 4. Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            var userDropdown = document.getElementById('user-dropdown');
            
            if (userDropdown && !event.target.closest('#user-dropdown') && !event.target.closest('button[onclick="toggleUserMenu()"]')) {
                userDropdown.classList.add('hidden');
            }
        });
        
        // 5. Auto-hide success message on page load (Sudah ada di Blade, ini untuk memastikan)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var successAlert = document.querySelector('.bg-green-100');
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