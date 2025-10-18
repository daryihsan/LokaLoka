<footer class="sticky top-0 z-50 p-4 text-white shadow-xl bg-primary">
  <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    <!-- Brand -->
    <div>
      <div class="mb-4">
            {{-- Menggunakan mix-blend-mode: multiply untuk memastikan transparansi pada latar belakang berwarna --}}
          <img src="{{ asset('images/logo.png') }}" alt="Logo Loka Loka" class="w-24 h-auto object-contain" style="background: none; mix-blend-mode: multiply;">
      </div>
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
        <li><a class="text-white hover:text-white" href="{{ route('profile') }}">Profile</a></li>
      </ul>
    </div>

    <!-- Bantuan -->
    <div>
      <h3 class="font-semibold text-green-darker mb-3">Bantuan</h3>
      <ul class="space-y-2 text-sm">
        <li><a class="text-white hover:text-white" href="mailto:support@lokaloka.com">Customer Service</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('faq') }}">FAQ</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
        <li><a class="text-white hover:text-white" href="{{ route('about') }}">Tentang Kami</a></li>
      </ul>
    </div>

    <!-- Kontak -->
    <div>
      <h3 class="font-semibold text-green-darker mb-3">Kontak</h3>
      <ul class="text-white hover:text-white">
        <li><a href="mailto:support@lokaloka.com">Email: support@lokaloka.com</a></li>
        <li><a href="tel:+6281234567890">Telp : +62-812-3456-7890</a></li>
        <li>Alamat: Semarang, Indonesia</li>
      </ul>
    </div>
  </div>

  <div class="border-t border-gray-200">
    <div class="text-white max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row justify-between items-center text-sm">
      <span>© {{ date('Y') }} LokaLoka. All rights reserved.</span>
      <span>“Cinta Lokal, Belanja Loka.”</span>
    </div>
  </div>
</footer>