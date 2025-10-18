<header class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ route('homepage') }}" class="flex items-center gap-2 font-roboto-slab
            text-3xl font-bold tracking-wide text-white hover:text-accent
            transition" aria-label="Loka Loka ke Beranda">
                {{-- Perbaikan: Set background none pada gambar dan pakai ukuran yang lebih kecil --}}
                <img src="{{ asset('images/logo.png') }}" alt="Logo Loka Loka" class="w-8 h-auto object-contain flex-shrink-0" style="background: none; mix-blend-mode: multiply;">
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


{{-- 1. Wadah Toast Notif (Selalu di atas) --}}
{{-- Tambahkan 'pointer-events-none' agar Toast tidak menghalangi klik saat tersembunyi --}}
<div id="toast-notification-global" class="fixed top-5 right-5 bg-white border
border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full
transition-transform duration-300 z-50 pointer-events-none">
  <div id="toast-message-global" class="font-medium"></div>
</div>

{{-- 2. Wadah Global Error (Tampil di dalam container-page) --}}
{{-- Ambil error yang di-pass dari Controller, tampilkan di sini. --}}
@if ($errors->any())
<div class="container-page pt-4 md:pt-8" id="global-error-container">
    <div class="alert alert-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif

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

{{-- 3. Tambahkan fungsi showToast dan inisialisasi untuk menangkap session sukses --}}
<script>
    // Fungsi universal untuk menampilkan Toast
    function showToast(type, message) {
        const toast = document.getElementById('toast-notification-global');
        const toastMessage = document.getElementById('toast-message-global');
        if (!toast || !toastMessage || message.trim() === '') return;

        if (type === 'success') {
            toast.style.backgroundColor = '#d1fae5';
            toastMessage.style.color = '#059669';
        } else {
            toast.style.backgroundColor = '#fee2e2';
            toastMessage.style.color = '#dc2626';
        }
        toastMessage.textContent = message;
        
        // 1. Tampilkan dan aktifkan interaksi
        toast.classList.remove('translate-x-full', 'pointer-events-none');
        toast.classList.add('pointer-events-auto');

        // 2. Sembunyikan setelah 3 detik
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            
            // 3. Matikan interaksi HANYA setelah transisi KELUAR (300ms) selesai.
            // Kita gunakan total 300ms (transisi) + 50ms (buffer) = 350ms
            // Note: total waktu ini dihitung sejak class 'translate-x-full' ditambahkan.
            setTimeout(() => toast.classList.add('pointer-events-none'), 350); 
            
        }, 3000); // Tampil selama 3.0 detik
    }
    
    // Tangkap Session Success setelah DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil session success
        const successMessage = '{{ session('success') }}';
        if (successMessage && successMessage.trim().length > 0) {
            showToast('success', successMessage.trim());
        }
    });
</script>