<footer class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
  <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    <!-- Brand -->
    <div>
      <a href="{{ route('homepage') }}" class="font-roboto-slab text-2xl font-bold text-green-darker hover:text-[var(--brand-accent)]">
        <span class="text-green-darker">Loka</span><span class="text-[var(--brand-accent)]">Loka</span>
      </a>
      <p class="text-white hover:text-white">
        Marketplace produk lokal untuk mendukung UMKM Indonesia.
      </p>
    </div>

    <!-- Navigasi -->
    <div>
      <h3 class="font-semibold text-green-darker mb-3">Navigasi</h3>
      <ul class="space-y-2 text-sm">
        <li><a class="text-white hover:text-white" href="{{ route('homepage') }}">Beranda</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('searchfilter') }}">Katalog</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('cart.show') }}">Keranjang</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('profile') }}">Profil</a></li>
      </ul>
    </div>

    <!-- Bantuan -->
    <div>
      <h3 class="font-semibold text-green-darker mb-3">Bantuan</h3>
      <ul class="space-y-2 text-sm">
        <li><a class="text-white hover:text-white" href="mailto:support@lokaloka.com">Customer Service</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('about') }}">FAQ</a></li>
        <li><a class="text-white hover:text-white" href="#">Kebijakan Privasi</a></li>
        <li><a class="text-white hover:text-white" href="#">Syarat & Ketentuan</a></li>
      </ul>
    </div>

    <!-- Kontak -->
    <div>
      <h3 class="font-semibold text-green-darker mb-3">Kontak</h3>
      <ul class="text-white hover:text-white">
        <li>Email: support@lokaloka.com</li>
        <li>Telp : +62-812-3456-7890</li>
        <li>Alamat: Semarang, Indonesia</li>
      </ul>
    </div>
  </div>

  <div class="border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs text-gray-500 flex items-center justify-between">
      <span>© {{ date('Y') }} LokaLoka. All rights reserved.</span>
      <span>“Cinta Lokal, Belanja Loka.”</span>
    </div>
  </div>
</footer>