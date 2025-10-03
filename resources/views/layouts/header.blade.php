<header class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-4">
        <!-- Logo -->
        <a href="{{ route('homepage') }}" class="font-roboto-slab text-2xl font-bold text-green-darker hover:text-accent transition" aria-label="Loka Loka - ke Beranda">
            <span class="text-green-darker">Loka</span><span class="text-accent">Loka</span>
        </a>

        <!-- Search visibility -->
        @php
            $showSearch = request()->routeIs('homepage', 'searchfilter');
        @endphp
        @if ($showSearch)
        <form method="GET" action="{{ route('searchfilter') }}" class="flex-1">
            <div class="relative max-w-2xl">
                <input
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari produk lokal terbaik..."
                    class="w-full rounded-2xl pl-11 pr-4 py-2.5 text-green-darker focus:ring-2 focus:ring-[var(--brand-accent)] border border-gray-200"
                />
                <button type="submit" class="absolute left-3 top-2.5 text-gray-500" aria-label="Cari">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 10-14 0 7 7 0 0014 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
        @endif

        <!-- Actions -->
        <nav class="flex items-center gap-2 ml-auto">
            <a href="{{ route('cart.show') }}" class="btn btn-secondary" aria-label="Keranjang">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 8L6 18h12M6 18a2 2 0 100 4 2 2 0 000-4zM16 18a2 2 0 100 4 2 2 0 000-4z"></path>
                </svg>
                <span class="hidden sm:inline">Keranjang</span>
            </a>
            @if(session()->has('user_id'))
                <a href="{{ route('profile') }}" class="btn btn-secondary">Profil</a>
                <a href="{{ route('logout') }}" class="btn btn-link">Logout</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Daftar</a>
            @endif
        </nav>
    </div>
</header>