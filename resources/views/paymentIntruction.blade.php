@extends('layouts.app')

@section('title', 'Instruksi Pembayaran | Loka Loka')

@push('head')
    <style>
        .instruction-card {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .status-badge {
            display: inline-flex;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #fef3c7;
            color: #d97706;
        }

        .product-list-compact {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')

    <div class="instruction-card bg-white rounded-2xl shadow-xl">
        <div class="text-center mb-6">
            <h1 class="font-roboto-slab text-3xl font-bold text-green-darker mb-2">Pesanan Berhasil Dibuat</h1>
            <p class="text-gray-600">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            <span class="status-badge">Menunggu Pembayaran</span>
        </div>

        <div class="border-t pt-6 mb-6">
            <h2 class="text-xl font-bold text-green-darker mb-4">Instruksi Pembayaran:
                {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
            </h2>

            @if($order->payment_method === 'transfer_bank')
                <div class="bg-gray-100 p-4 rounded-lg space-y-3">
                    <p class="font-semibold text-lg">Total yang Harus Dibayar:</p>
                    <p class="text-3xl font-bold text-red-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    <p>Transfer ke rekening di bawah ini:</p>
                    <div class="bg-white p-3 rounded-md border">
                        <p class="font-mono text-xl"><strong>123-456-7890 (Bank Lokal)</strong></p>
                        <p class="text-sm">Atas Nama: PT. Loka Loka Sejahtera</p>
                    </div>
                    <p class="text-sm text-red-500">Mohon transfer dalam waktu 24 jam. Pesanan akan diproses setelah pembayaran
                        terkonfirmasi.</p>
                </div>
            @elseif($order->payment_method === 'cod')
                <div class="bg-gray-100 p-4 rounded-lg space-y-3">
                    <p class="font-semibold text-lg">Total yang Harus Dibayar Tunai:</p>
                    <p class="text-3xl font-bold text-red-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    <p>Pembayaran akan dilakukan secara tunai saat barang diterima di alamat tujuan.</p>
                    <p class="text-sm text-blue-500">Pesanan akan segera diproses. Pastikan Anda menyiapkan uang tunai saat
                        kurir tiba.</p>
                </div>
            @else
                <p>Instruksi pembayaran untuk metode ini belum tersedia.</p>
            @endif
        </div>

        {{-- Ringkasan Pesanan --}}
        <div class="border-t pt-6">
            <h2 class="text-xl font-bold text-green-darker mb-4">Ringkasan Pesanan</h2>

            <div class="space-y-2 product-list-compact">
                @forelse($order->orderItems as $item)
                    <div class="flex justify-between items-center text-sm border-b pb-1">
                        <p class="font-medium">
                            {{ $item->qty }}x <a href="{{ route('product.show', $item->product->id) }}"
                                class="text-blue-600 hover:underline">{{ $item->product->name ?? 'Produk Dihapus' }}</a>
                        </p>
                        <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-center text-gray-500">Tidak ada item dalam pesanan ini.</p>
                @endforelse
            </div>

            <div class="border-t mt-4 pt-4 space-y-2">
                <div class="flex justify-between text-sm text-gray-700"><span>Subtotal Produk</span><span>Rp
                        {{ number_format($order->total - $order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm text-gray-700"><span>Ongkos Kirim</span><span>Rp
                        {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-bold text-lg text-red-600"><span>Total Pembayaran</span><span>Rp
                        {{ number_format($order->total, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('profile') }}"
                class="w-full block text-center bg-primary hover:bg-primary-dark text-white py-3 rounded-lg font-medium transition-colors">Lihat
                Status Pesanan Saya</a>
        </div>
    </div>

@endsection