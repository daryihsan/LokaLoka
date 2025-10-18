@extends('layouts.app')

@section('title', 'Profil Saya - Loka Loka')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-roboto-slab text-3xl font-bold">Profil</h1>
    <div class="flex gap-2">
        <button type="button" class="btn btn-primary" onclick="openEditModal()">Edit Profil</button>
        <a href="mailto:support@lokaloka.com" class="btn btn-secondary">Customer Service</a>
        <a href="{{ route('logout') }}" class="btn btn-link" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
    </div>
</div>

<section class="main-content grid md:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="font-roboto text-xl font-bold mb-4">Identitas Diri</h2>
        <div class="flex flex-col items-center mb-4">
            {{-- Avatar Placeholder --}}
            @php
                $user_avatar = Session::get('user_avatar', $user->avatar_url ?? 'https://i.pravatar.cc/150?img=1');
            @endphp 
            <img src="{{ $user_avatar }}" id="user-avatar-display" alt="Avatar Pengguna" class="w-24 h-24 rounded-full object-cover mb-3 border-4 border-primary shadow-lg">
            <button onclick="openAvatarModal()" class="text-sm btn-link">Ganti Avatar</button>
        </div>
        <div class="identity-info space-y-2">
            <p><strong>Username:</strong> {{ $user->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
            <p><strong>Nomor Telepon:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role ?? 'Customer') }}</p>
            <p><strong>Bergabung sejak:</strong> {{ $user->created_at ? $user->created_at->format('d F Y') : 'N/A' }}</p>
            <p><strong>Update terakhir:</strong> {{ $user->updated_at ? $user->updated_at->format('d F Y') : 'N/A' }}</p>
        </div>
    </div>

    <div class="card p-6 lg:col-span-1">
        {{-- PERBAIKAN: Tombol Kembali dengan history.back() atau ke homepage --}}
        <button class="btn btn-secondary mb-4"
            onclick="window.location.href = '{{ route('homepage') }}'">
            Kembali
        </button>
        <h2 class="font-roboto text-xl font-bold mb-4">Daftar Pesanan</h2>
        <div class="filter-controls flex justify-between items-center mb-4 relative">
            {{-- Tombol Filter --}}
            <button id="filter-btn" class="btn btn-secondary">Filter Status ▼</button>
            
            {{-- Dropdown Filter --}}
            <div id="filter-options" class="hidden absolute top-full left-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-xl z-10">
                <a href="#" data-status="semua" class="block px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">Semua Status</a>
                <a href="#" data-status="diproses" class="block px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">Diproses</a>
                <a href="#" data-status="dikirim" class="block px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">Dikirim</a>
                <a href="#" data-status="selesai" class="block px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">Selesai</a>
                <a href="#" data-status="dibatalkan" class="block px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">Dibatalkan</a>
            </div>
        </div>
        <div class="order-list divide-y">
            <div id="no-orders-message" class="text-center text-gray-500 py-4 hidden">Tidak ada pesanan dengan status ini.</div>
            
            @if(isset($orders) && count($orders) > 0)
                {{-- PERBAIKAN: Loop menggunakan data $orders dari Controller (dengan relasi OrderItems & Product) --}}
                @foreach($orders as $order)
                    @php
                        $status = strtolower($order->status ?? 'diproses');
                        // Ambil nama produk pertama dari orderItems
                        $firstItemName = $order->orderItems->first()->product->name ?? 'Produk Dihapus';
                        
                        $badge = match ($status) {
                            'selesai' => 'bg-green-100 text-green-700',
                            'dikirim' => 'bg-blue-100 text-blue-700',
                            'diproses' => 'bg-yellow-100 text-yellow-700',
                            'dibatalkan' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    {{-- Tambahkan data-status untuk filter JS --}}
                    <div class="order-item flex items-center justify-between py-3" data-status="{{ $status }}">
                        <div class="order-details flex-1 min-w-0">
                            <p class="font-semibold text-green-darker">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} - {{ $firstItemName }}...</p>
                            <p class="order-status text-sm text-gray-600">Status:
                                <span class="status px-2 py-0.5 rounded {{ $badge }}">{{ ucfirst($status) }}</span>
                            </p>
                        </div>
                        <div class="text-right text-sm text-gray-600 ml-4">
                            <div>{{ $order->created_at->format('d M Y') }}</div>
                            <div class="font-semibold text-green-800">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <p id="no-orders-message" class="text-center text-gray-500 py-4">Anda belum memiliki pesanan.</p>
            @endif
        </div>
    </div>
</section>

<!-- Modal Edit Profil -->
<div id="editProfileModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeEditModal()" aria-hidden="true"></div>

    <!-- Dialog -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="font-roboto text-lg font-bold text-green-darker">Edit Profil</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeEditModal()" aria-label="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm">Nama</label>
                        <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm">Email</label>
                        <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm">Nomor Telepon</label>
                        <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                        @error('phone_number')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="border-t pt-4">
                        <p class="font-semibold text-sm mb-2">Ganti Password (Opsional)</p>
                        <div>
                            <label class="block text-sm">Password Baru</label>
                            <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="password" name="password" minlength="6">
                        </div>
                        <div>
                            <label class="block text-sm">Konfirmasi Password</label>
                            <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="password" name="password_confirmation" minlength="6">
                            @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" class="btn btn-secondary"
                        onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="avatarModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"
    onclick="closeAvatarModal()" aria-hidden="true"></div>
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6">
            <h3 class="font-roboto text-lg font-bold text-green-darker mb-4 border-b pb-3">Pilih Avatar</h3>
            <form id="avatarForm" method="POST" action="{{ route('profile.avatar.update') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-4 gap-4">
                    @foreach($avatars as $avatar)
                    <label class="cursor-pointer">
                        {{-- Tambahkan input hidden untuk memastikan data yang disimpan adalah URL yang dipilih --}}
                        <input type="radio" name="avatar_url" value="{{ $avatar }}" class="hidden" {{ $user_avatar === $avatar ? 'checked' : '' }}>
                        <img src="{{ $avatar }}" alt="Avatar" class="w-full h-auto rounded-full object-cover border-4 border-transparent hover:border-primary transition-colors duration-200">
                    </label>
                    @endforeach
                </div>
                {{-- Hapus input hidden yang tidak diperlukan (name, email, dll.) karena tidak ada update DB --}}
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" class="btn btn-secondary" onclick="closeAvatarModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Avatar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ----- Modal Edit Profil -----
function openEditModal() {
    const m = document.getElementById('editProfileModal');
    if (!m) return;
    m.classList.remove('hidden');
    setTimeout(() => m.querySelector('input[name="name"]')?.focus(), 50);
}
function closeEditModal() {
    document.getElementById('editProfileModal')?.classList.add('hidden');
}
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeEditModal(); });
@if ($errors->any())
document.addEventListener('DOMContentLoaded', openEditModal);
@endif

function openAvatarModal() {
        document.getElementById('avatarModal')?.classList.remove('hidden');
    }
function closeAvatarModal() {
    document.getElementById('avatarModal')?.classList.add('hidden');
}

// Update avatar preview saat memilih
document.querySelectorAll('#avatarModal input[name="avatar_url"]').forEach(radio => {
    radio.addEventListener('change', () => {
        if (radio.checked) {
            document.getElementById('user-avatar-display').src = radio.value;
        }
    });
});
// ----- Filter Pesanan -----
const filterBtn = document.getElementById('filter-btn');
const filterDropdown = document.getElementById('filter-options');
const noOrdersMsg = document.getElementById('no-orders-message');

function toggleFilterDropdown() {
    filterDropdown?.classList.toggle('hidden');
}

function setActiveFilter(anchorEl) {
    filterDropdown?.querySelectorAll('a').forEach(a => a.classList.remove('bg-gray-100', 'font-semibold'));
    anchorEl.classList.add('bg-gray-100', 'font-semibold');
}

function filterOrders(status) {
    const items = document.querySelectorAll('.order-item');
    let visible = 0;
    items.forEach(item => {
        const s = item.getAttribute('data-status') || '';
        if (status === 'semua' || s === status) {
            item.classList.remove('hidden');
            visible++;
        } else {
            item.classList.add('hidden');
        }
    });
    if (noOrdersMsg) noOrdersMsg.classList.toggle('hidden', visible !== 0);
    // Update button label
    const label = status === 'semua' ? 'Filter Status ▼' : `Status: ${status.charAt(0).toUpperCase() + status.slice(1)} ▼`;
    if (filterBtn) filterBtn.textContent = label;
}

// Event bindings
filterBtn?.addEventListener('click', toggleFilterDropdown);
filterDropdown?.addEventListener('click', (e) => {
    const target = e.target.closest('a[data-status]');
    if (!target) return;
    e.preventDefault();
    const status = target.getAttribute('data-status');
    setActiveFilter(target);
    filterOrders(status);
    filterDropdown.classList.add('hidden');
});

// Close dropdown on outside click
document.addEventListener('click', (e) => {
    if (!filterDropdown) return;
    if (!e.target.closest('#filter-options') && !e.target.closest('#filter-btn')) {
        filterDropdown.classList.add('hidden');
    }
});

// Init
document.addEventListener('DOMContentLoaded', () => {
        // Set default filter ke 'semua' dan highlight item pertama
        const defaultFilter = document.querySelector('#filter-options a[data-status="semua"]');
        if (defaultFilter) setActiveFilter(defaultFilter);
        filterOrders('semua');
    });
</script>
@endpush