<header class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                <span class="text-green-darker font-bold text-lg">L</span>
            </div>
            <a href="{{ route('homepage') }}" class="font-roboto-slab text-3xl font-bold tracking-wide text-white hover:text-accent transition" aria-label="Loka Loka - ke Beranda">
                Loka Loka
            </a>
        </div>

        <!-- Search visibility -->
        @if (request()->routeIs('homepage') || request()->routeIs('searchfilter'))
            <form method="GET" action="{{ route('searchfilter') }}" class="flex-1 mx-8 max-w-2xl">
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari produk lokal terbaik..."
                        class="w-full rounded-2xl pl-12 pr-4 py-3 text-green-darker focus:outline-none focus:ring-2 focus:ring-accent border-0 shadow-lg"
                    />
                    <button type="submit" class="absolute left-3 top-3.5 h-5 w-5 text-green-dark" style="background: none; border: none; padding: 0;" aria-label="Cari">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        @endif

        <!-- Actions -->
        <div class="flex gap-3">
            {{-- Perbaikan: Menggunakan Session::has('user_id') sebagai indikator login yang lebih kuat --}}
            @if(session()->has('user_id'))
                {{-- Tombol Keranjang --}}
                <a href="{{ route('cart.show') }}" class="text-white hover:bg-white
                    hover:bg-opacity-20 rounded-xl p-3 flex items-center gap-2" title="Keranjang Belanja"
                    aria-label="Keranjang">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                    </svg>
                    <span class="hidden md:inline">Keranjang</span>
                </a>

                {{-- Profile dropdown, hanya tampil di homepage, search/filter, dan product detail --}}
                @if (request()->routeIs('homepage') || request()->routeIs('searchfilter') || request()->routeIs('product.show'))
                    <div class="relative">
                        <button
                            type="button"
                            class="text-white hover:bg-white hover:bg-opacity-20 rounded-xl p-3 flex items-center gap-2"
                            onclick="toggleUserMenu()"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="user-dropdown"
                            id="user-menu-button"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c7 0 7 7 0 7H5c0-7 7-7 7-7z"></path>
                            </svg>
                            {{-- Menggunakan Session untuk nama user --}}
                            <span class="hidden md:inline">{{ session('username') ?? 'User' }}</span>
                        </button>

                        <div id="user-dropdown" class="hidden absolute right-0 top-full
                            mt-2 bg-white rounded-xl shadow-xl p-2 w-48 border z-50">
                            <div class="px-4 py-2 border-b">
                                <p class="font-semibold text-green-darker">
                                    {{ session('username') ?? 'User' }}
                                </p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2
                                text-green-darker hover:bg-gray-100 rounded-lg">Profile
                            </a>
                            {{-- Jika role admin, tampilkan link ke dashboard --}}
                            @if (session('user_role') === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2
                                    text-blue-600 hover:bg-blue-50 rounded-lg">Admin Dashboard
                                </a>
                            @endif
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4
                                    py-2 text-red-600 hover:bg-red-50 rounded-lg">Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                {{-- Guest: Login dan Register --}}
                @unless (request()->routeIs('login') || request()->routeIs('register'))
                    <a href="{{ route('login') }}" class="text-white hover:bg-white
                        hover:bg-opacity-20 rounded-xl p-3" aria-label="Login">Login</a>
                    <a href="{{ route('register') }}" class="text-white hover:bg-white
                        hover:bg-opacity-20 rounded-xl p-3" aria-label="Daftar">Daftar</a>
                @endunless
            @endif
        </div>
    </div>
</header>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('user-dropdown');
    const btn = document.getElementById('user-menu-button');
    const willOpen = menu.classList.contains('hidden');
    menu.classList.toggle('hidden');
    btn?.setAttribute('aria-expanded', String(willOpen));
}

document.addEventListener('click', function (e) {
    const menu = document.getElementById('user-dropdown');
    const btn = document.getElementById('user-menu-button');
    if (!menu || !btn) return;
    if (!menu.classList.contains('hidden')) {
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const menu = document.getElementById('user-dropdown');
        const btn = document.getElementById('user-menu-button');
        if (menu && btn && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    }
});
</script>