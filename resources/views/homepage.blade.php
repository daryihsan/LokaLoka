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
        
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-left { animation: fadeInLeft 0.6s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

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
    </style>
</head>
<body class="min-h-screen text-green-darker font-open-sans bg-white">
    <!-- Header -->
    <header class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold text-lg">L</span>
                </div>
                <h1 class="font-roboto-slab text-3xl font-bold tracking-wide">Loka Loka</h1>
            </div>
            
            <div class="flex-1 mx-8 max-w-2xl">
                <div class="relative">
                    <input type="text" placeholder="Cari produk lokal terbaik..." 
                        class="w-full rounded-2xl pl-12 pr-4 py-3 text-green-darker focus:outline-none focus:ring-2 focus:ring-accent border-0 shadow-lg">
                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-green-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <button class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl px-4 py-2 flex items-center gap-2" onclick="toggleFilter()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                        </svg>
                        <span class="hidden md:inline">Filter</span>
                    </button>
                    <div id="filter-dropdown" class="hidden absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl p-4 w-64 border">
                        <div class="space-y-3">
                            <div>
                                <label class="font-semibold text-green-darker text-sm">Harga</label>
                                <div class="flex gap-2 mt-1">
                                    <input placeholder="Min" class="flex-1 p-2 border rounded-lg text-sm">
                                    <input placeholder="Max" class="flex-1 p-2 border rounded-lg text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto p-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto">
        <!-- Hot Items -->
        <section class="p-6">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="font-roboto text-2xl font-bold text-green-darker">Produk Terlaris</h2>
                <span class="text-2xl">🔥</span>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" id="hot-products">
                <!-- Hot products will be generated by JavaScript -->
            </div>
        </section>

        <!-- Categories -->
        <section class="p-6">
            <h2 class="font-roboto text-2xl font-bold text-green-darker mb-6">Jelajahi Kategori</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4" id="categories">
                <!-- Categories will be generated by JavaScript -->
            </div>
        </section>

        <!-- Recommended Products -->
        <section class="p-6">
            <h2 class="font-roboto text-2xl font-bold text-green-darker mb-6">Rekomendasi Untukmu</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="recommended-products">
                <!-- Recommended products will be generated by JavaScript -->
            </div>
        </section>
    </div>

    <!-- Footer -->
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

    <script>
        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Data arrays
            var categories = [
                {name: "Makanan", icon: "🍜", color: "#A6A604"},
                {name: "Minuman", icon: "🥤", color: "#7F4F24"},
                {name: "Fashion", icon: "👕", color: "#936639"},
                {name: "Kerajinan", icon: "🧺", color: "#C2C5AA"},
                {name: "Elektronik", icon: "📱", color: "#656D4A"},
                {name: "Buku", icon: "📚", color: "#A4AC86"},
                {name: "Kesehatan", icon: "💊", color: "#5E0E0E"},
                {name: "Olahraga", icon: "⚽", color: "#414833"}
            ];

            var hotProducts = [
                {name: "Gudeg Jogja Authentic", price: "25000", rating: 4.8, sold: "500+", discount: "20%"},
                {name: "Kopi Arabica Temanggung", price: "45000", rating: 4.9, sold: "300+", discount: null},
                {name: "Batik Tulis Solo Premium", price: "150000", rating: 4.7, sold: "150+", discount: "15%"},
                {name: "Tas Anyaman Pandan", price: "75000", rating: 4.6, sold: "200+", discount: null}
            ];

            // Generate recommended products
            var recommendedProducts = [];
            for (var i = 0; i < 12; i++) {
                recommendedProducts.push({
                    name: 'Produk Lokal ' + (i + 1),
                    price: String((i + 1) * 15000),
                    rating: (4.0 + Math.random() * 1).toFixed(1),
                    sold: String(Math.floor(Math.random() * 500) + 50) + '+',
                    discount: Math.random() > 0.7 ? String(Math.floor(Math.random() * 30) + 10) + '%' : null
                });
            }

            // Create product card
            function createProductCard(product, isHot) {
                var discountPrice = product.discount ? Math.floor(parseInt(product.price) * 1.2) : null;
                var widthClass = isHot ? 'min-w-[220px]' : '';
                var heightClass = isHot ? 'h-32' : 'h-40';
                
                var html = '<div class="' + widthClass + ' bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border hover:-translate-y-1 opacity-0">';
                html += '<div class="p-0">';
                html += '<div class="relative">';
                html += '<div class="' + heightClass + ' w-full rounded-t-2xl relative overflow-hidden" style="background: linear-gradient(135deg, #936639 0%, #7F4F24 50%, #5E0E0E 100%);">';
                
                if (product.discount) {
                    html += '<div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold">-' + product.discount + '</div>';
                }
                
                html += '<button class="absolute top-2 right-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2">';
                html += '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
                html += '</svg></button></div>';
                
                html += '<div class="p-4">';
                html += '<h3 class="font-open-sans font-semibold text-green-darker mb-2 line-clamp-2">' + product.name + '</h3>';
                html += '<div class="flex items-center gap-1 mb-2">';
                html += '<svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">';
                html += '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>';
                html += '</svg>';
                html += '<span class="text-xs text-gray-600">' + product.rating + '</span>';
                html += '<span class="text-xs text-gray-500">(' + product.sold + ')</span>';
                html += '</div>';
                
                html += '<div class="flex items-center justify-between">';
                html += '<div>';
                if (discountPrice) {
                    html += '<p class="text-xs text-gray-500 line-through">Rp ' + discountPrice.toLocaleString() + '</p>';
                }
                html += '<p class="font-bold text-green-800">Rp ' + parseInt(product.price).toLocaleString() + '</p>';
                html += '</div>';
                html += '<button class="bg-primary hover:bg-primary-dark text-white rounded-full p-2">';
                html += '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>';
                html += '</svg></button></div></div></div></div>';
                
                return html;
            }

            // Create category card
            function createCategoryCard(category, index) {
                var html = '<div class="flex flex-col items-center justify-center bg-white rounded-2xl p-6 border-2 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer group opacity-0" ';
                html += 'style="border-color: ' + category.color + '; animation-delay: ' + (index * 50) + 'ms">';
                html += '<div class="w-12 h-12 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200" ';
                html += 'style="background-color: ' + category.color + '20; border: 2px solid ' + category.color + '">';
                html += '<span class="text-2xl">' + category.icon + '</span>';
                html += '</div>';
                html += '<p class="font-open-sans text-sm font-semibold text-green-darker text-center">' + category.name + '</p>';
                html += '</div>';
                return html;
            }

            // Hot Products
            var hotProductsContainer = document.getElementById('hot-products');
            for (var i = 0; i < hotProducts.length; i++) {
                var productElement = document.createElement('div');
                productElement.innerHTML = createProductCard(hotProducts[i], true);
                productElement.style.animationDelay = (i * 100) + 'ms';
                if (productElement.firstElementChild) {
                    productElement.firstElementChild.classList.add('animate-fade-in-left');
                }
                hotProductsContainer.appendChild(productElement);
            }

            // Categories
            var categoriesContainer = document.getElementById('categories');
            for (var i = 0; i < categories.length; i++) {
                var categoryElement = document.createElement('div');
                categoryElement.innerHTML = createCategoryCard(categories[i], i);
                if (categoryElement.firstElementChild) {
                    categoryElement.firstElementChild.classList.add('animate-fade-in-up');
                }
                categoriesContainer.appendChild(categoryElement);
            }

            // Recommended Products
            var recommendedContainer = document.getElementById('recommended-products');
            for (var i = 0; i < recommendedProducts.length; i++) {
                var productElement = document.createElement('div');
                productElement.innerHTML = createProductCard(recommendedProducts[i], false);
                productElement.style.animationDelay = (i * 50) + 'ms';
                if (productElement.firstElementChild) {
                    productElement.firstElementChild.classList.add('animate-fade-in-up');
                }
                recommendedContainer.appendChild(productElement);
            }

            // Auto-hide success message after 5 seconds
            setTimeout(function() {
                var successAlert = document.querySelector('[role="alert"]');
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

        // Filter and user menu functions
        function toggleFilter() {
            var dropdown = document.getElementById('filter-dropdown');
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        function toggleUserMenu() {
            var dropdown = document.getElementById('user-dropdown');
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            var filterDropdown = document.getElementById('filter-dropdown');
            var userDropdown = document.getElementById('user-dropdown');
            
            if (!event.target.closest('#filter-dropdown') && !event.target.closest('button[onclick="toggleFilter()"]')) {
                filterDropdown.classList.add('hidden');
            }
            
            if (!event.target.closest('#user-dropdown') && !event.target.closest('button[onclick="toggleUserMenu()"]')) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>