@extends('layouts.app')

@section('title', 'Profil Saya - Loka Loka')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-roboto-slab text-3xl font-bold">Profil</h1>
    <div class="flex gap-2">
        <a href="mailto:support@lokaloka.com" class="btn btn-secondary">Customer Service</a>
        <a href="{{ route('logout') }}" class="btn btn-link" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="font-roboto text-xl font-bold mb-4">Identitas Diri</h2>
        <div class="space-y-2">
            <p><strong>Username:</strong> {{ $user->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
            <p><strong>Nomor Telepon:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
            {{-- Role disembunyikan sesuai permintaan --}}
            {{-- <p><strong>Role:</strong> {{ ucfirst($user->role ?? 'Customer') }}</p> --}}
            <p><strong>Bergabung sejak:</strong> {{ $user->created_at ? $user->created_at->format('d F Y') : 'N/A' }}</p>
        </div>
    </div>

    <div class="card p-6">
        <h2 class="font-roboto text-xl font-bold mb-4">Edit Profil</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">Nama</label>
                <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label class="block text-sm">Email</label>
                <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div>
                <label class="block text-sm">Nomor Telepon</label>
                <input class="mt-1 w-full border border-gray-300 rounded-lg p-2" type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
            </div>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<div class="card p-6 mt-6">
    <div class="flex items-center justify-between">
        <h2 class="font-roboto text-xl font-bold">Pesanan Saya</h2>
        <a class="btn btn-secondary" href="{{ route('orders') }}">Lihat Daftar Pesanan</a>
    </div>
</div>
@endsection