<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loka Loka - Hasil Pencarian</title>
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

        /* Custom colors (match homepage) */
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
    <!-- Header (same style as homepage) -->
    <header class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold text-lg">L</span>
                </div>
                <h1 class="font-roboto-slab text-3xl font-bold tracking-wide">Loka Loka</h1>
            </div>

            <!-- Single GET form drives both search and filters -->
            <form id="searchForm" method="GET" action="{{ route('searchfilter') }}" class="flex-1 mx-8 max-w-2xl">
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari produk lokal terbaik..."
                        class="w-full rounded-2xl pl-12 pr-4 py-3 text-green-darker focus:outline-none focus:ring-2 focus:ring-accent border-0 shadow-lg"
                    >
                    <button type="submit" class="absolute left-3 top-2.5 h-8 w-8 text-green-dark flex items-center justify-center rounded-md hover:bg-white hover:bg-opacity-20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Inline hidden container for dropdown so inputs submit with the same form -->
                <div class="relative mt-3 md:mt-0 md:absolute md:right-0 md:-top-1.5">
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl px-4 py-2 flex items-center gap-2"
                            onclick="toggleFilter()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                        </svg>
                        <span class="hidden md:inline">Filter</span>
                    </button>
                    <div id="filter-dropdown" class="hidden absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl p-4 w-[20rem] border">
                        <div class="space-y-3 text-green-darker">
                            <div>
                                <label class="font-semibold text-sm">Kategori</label>
                                <select name="category" class="mt-1 p-2 border rounded-lg text-sm w-full bg-white">
                                    <option value="">Semua Kategori</option>
                                    @foreach(($categories ?? []) as $cat)
                                        <option value="{{ is_array($cat) ? ($cat['value'] ?? $cat['name']) : $cat }}"
                                            {{ request('category') == (is_array($cat) ? ($cat['value'] ?? $cat['name']) : $cat) ? 'selected' : '' }}>
                                            {{ is_array($cat) ? ($cat['label'] ?? $cat['name']) : $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="font-semibold text-sm">Lokasi</label>
                                <select name="location" class="mt-1 p-2 border rounded-lg text-sm w-full bg-white">
                                    <option value="">Semua Lokasi</option>
                                    @foreach(($locations ?? []) as $loc)
                                        <option value="{{ is_array($loc) ? ($loc['value'] ?? $loc['name']) : $loc }}"
                                            {{ request('location') == (is_array($loc) ? ($loc['value'] ?? $loc['name']) : $loc) ? 'selected' : '' }}>
                                            {{ is_array($loc) ? ($loc['label'] ?? $loc['name']) : $loc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="font-semibold text-sm">Harga</label>
                                <div class="flex gap-2 mt-1">
                                    <input name="min_price" inputmode="numeric" placeholder="Min"
                                           value="{{ request('min_price') }}"
                                           class="flex-1 p-2 border rounded-lg text-sm">
                                    <input name="max_price" inputmode="numeric" placeholder="Max"
                                           value="{{ request('max_price') }}"
                                           class="flex-1 p-2 border rounded-lg text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="font-semibold text-sm">Rating minimal</label>
                                <select name="min_rating" class="mt-1 p-2 border rounded-lg text-sm w-full bg-white">
                                    <option value="">Semua</option>
                                    @for($r=5; $r>=1; $r--)
                                        <option value="{{ $r }}" {{ (string)request('min_rating') === (string)$r ? 'selected' : '' }}>{{ $r }}+</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="font-semibold text-sm">Urutkan</label>
                                <select name="sort" class="mt-1 p-2 border rounded-lg text-sm w-full bg-white">
                                    <option value="">Paling relevan</option>
                                    <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Terbaru</option>
                                    <option value="price_asc" {{ request('sort')==='price_asc'?'selected':'' }}>Harga Terendah</option>
                                    <option value="price_desc" {{ request('sort')==='price_desc'?'selected':'' }}>Harga Tertinggi</option>
                                    <option value="rating_desc" {{ request('sort')==='rating_desc'?'selected':'' }}>Rating Tertinggi</option>
                                </select>
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button type="button" class="px-4 py-2 rounded-lg border text-green-darker hover:bg-gray-50"
                                        onclick="resetFiltersAndSubmit()">
                                    Reset
                                </button>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-white font-semibold hover:bg-primary-dark">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <button class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl p-3 flex items-center gap-2"
                            onclick="toggleUserMenu()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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

    <!-- Success Message (optional, same as homepage behavior) -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto p-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Search Summary + Active Filters -->
    <div class="max-w-7xl mx-auto px-4 md:px-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-2">
                <h2 class="font-roboto text-xl md:text-2xl font-bold text-green-darker">
                    Hasil untuk "{{ request('q') }}"
                </h2>
                @isset($total)
                    <span class="text-sm text-green-olive">• {{ number_format($total) }} produk</span>
                @endisset
            </div>

            <div class="flex items-center gap-2">
                <!-- Active filter chips -->
                <div class="flex flex-wrap gap-2">
                    @if(request('category'))
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">Kategori: {{ request('category') }}</span>
                    @endif
                    @if(request('location'))
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">Lokasi: {{ request('location') }}</span>
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">
                            Harga:
                            {{ request('min_price') ? 'Rp '.number_format((int)request('min_price'),0,',','.') : '0' }}
                            -
                            {{ request('max_price') ? 'Rp '.number_format((int)request('max_price'),0,',','.') : 'Tak terbatas' }}
                        </span>
                    @endif
                    @if(request('min_rating'))
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-sm border">Rating: {{ request('min_rating') }}+</span>
                    @endif
                </div>

                <!-- Sort (quick) -->
                <form method="GET" action="{{ route('searchfilter') }}" class="ml-auto">
                    @foreach(request()->except('sort') as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" class="p-2 border rounded-lg text-sm bg-white" onchange="this.form.submit()">
                        <option value="">Paling relevan</option>
                        <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort')==='price_asc'?'selected':'' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort')==='price_desc'?'selected':'' }}>Harga Tertinggi</option>
                        <option value="rating_desc" {{ request('sort')==='rating_desc'?'selected':'' }}>Rating Tertinggi</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Grid -->
    <main class="max-w-7xl mx-auto px-4 md:px-6 py-6">
        @php
            // Expecting $products to be a collection or paginator of items with keys:
            // name, price (int), rating (float), sold (string/int), discount_percent (int|null), image_url (string|null),
            // location (string|null), category (string|null), url or id/slug for detail link
        @endphp

        @if(isset($products) && count($products))
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $i => $product)
                    @php
                        $name = data_get($product, 'name', 'Produk Lokal');
                        $price = (int) data_get($product, 'price', 0);
                        $rating = number_format((float) data_get($product, 'rating', 0), 1);
                        $sold = data_get($product, 'sold', '');
                        $disc = data_get($product, 'discount_percent');
                        $image = data_get($product, 'image_url');
                        $location = data_get($product, 'location');
                        $category = data_get($product, 'category');
                        $oldPrice = $disc ? (int) round($price / (1 - ($disc/100))) : null;
                        $detailUrl = data_get($product, 'url', '#');
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border hover:-translate-y-1 opacity-0 animate-fade-in-up"
                         style="animation-delay: {{ $i * 50 }}ms">
                        <div class="p-0">
                            <div class="relative">
                                <div class="h-40 w-full rounded-t-2xl relative overflow-hidden bg-gray-100">
                                    @if($image)
                                        <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full" style="background: linear-gradient(135deg, #936639 0%, #7F4F24 50%, #5E0E0E 100%);"></div>
                                    @endif

                                    @if($disc)
                                        <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                            -{{ $disc }}%
                                        </div>
                                    @endif

                                    <button class="absolute top-2 right-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2" title="Favoritkan">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="p-4">
                                    <h3 class="font-open-sans font-semibold text-green-darker mb-2 line-clamp-2">{{ $name }}</h3>

                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                        <div class="flex items-center gap-1">
                                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span>{{ $rating }}</span>
                                        </div>
                                        @if($sold)
                                            <span class="text-gray-400">•</span>
                                            <span>Terjual {{ $sold }}</span>
                                        @endif
                                    </div>

                                    @if($category || $location)
                                        <div class="text-xs text-green-olive mb-2">
                                            @if($category) Kategori: {{ $category }} @endif
                                            @if($category && $location) • @endif
                                            @if($location) Lokasi: {{ $location }} @endif
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($oldPrice)
                                                <p class="text-xs text-gray-500 line-through">Rp {{ number_format($oldPrice, 0, ',', '.') }}</p>
                                            @endif
                                            <p class="font-bold text-green-800">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                        </div>
                                        <a href="{{ $detailUrl }}" class="bg-primary hover:bg-primary-dark text-white rounded-full p-2" title="Tambah ke keranjang">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(method_exists($products, 'links'))
                <div class="mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Empty state -->
            <div class="bg-white border border-green-light rounded-2xl p-10 text-center max-w-3xl mx-auto">
                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-green-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 10-14 0 7 7 0 0014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-roboto-slab text-xl font-bold mb-2">Tidak ada hasil</h3>
                <p class="text-green-olive mb-6">Coba kata kunci lain atau ubah filter pencarian.</p>
                <button onclick="resetFiltersKeepQuery()"
                        class="px-5 py-2 rounded-lg bg-accent text-white font-semibold hover:bg-primary-dark">
                    Hapus semua filter
                </button>
            </div>
        @endif
    </main>

    <!-- Footer (same as homepage) -->
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
        // Auto-hide success message after 5 seconds (same as homepage)
        document.addEventListener('DOMContentLoaded', function() {
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

        // Dropdown toggles
        function toggleFilter() {
            var dropdown = document.getElementById('filter-dropdown');
            dropdown.classList.toggle('hidden');
        }
        function toggleUserMenu() {
            var dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            var filterDropdown = document.getElementById('filter-dropdown');
            var userDropdown = document.getElementById('user-dropdown');

            // Filter
            if (!event.target.closest('#filter-dropdown') && !event.target.closest('button[onclick="toggleFilter()"]')) {
                if (filterDropdown) filterDropdown.classList.add('hidden');
            }
            // User
            if (!event.target.closest('#user-dropdown') && !event.target.closest('button[onclick="toggleUserMenu()"]')) {
                if (userDropdown) userDropdown.classList.add('hidden');
            }
        });

        // Reset helpers
        function resetFiltersAndSubmit() {
            var form = document.getElementById('searchForm');
            if (!form) return;

            ['category','location','min_price','max_price','min_rating','sort'].forEach(function(name){
                var el = form.querySelector('[name="'+name+'"]');
                if (el) el.value = '';
            });
            form.submit();
        }
        function resetFiltersKeepQuery() {
            var url = new URL(window.location.href);
            var q = url.searchParams.get('q') || '';
            url.searchParams.forEach((_, key) => {
                if (key !== 'q' && key !== 'page') url.searchParams.delete(key);
            });
            url.searchParams.set('q', q);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>