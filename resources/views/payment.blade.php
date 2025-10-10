@extends('layouts.app')

@section('title', 'Pembayaran - Loka Loka')

@push('head')
<style>
    .payment-page {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        min-height: 70vh;
    }

    .payment-container {
        background-color: #ffffff;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 600px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .detail-row.total {
        border-top: 1px solid #e5e7eb;
        font-size: 1.5rem;
        font-weight: bold;
        color: #ef4444;
        margin-top: 1rem;
        padding-top: 1rem;
    }

    .product-list-item {
        display: flex;
        gap: 1rem;
        padding: 0.5rem 0;
        align-items: center;
    }

    .instruksi-box {
        background: #f7f9f2;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .btn-copy {
        background: #f3f4f6;
        color: #333D29;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: background 0.2s;
    }

    .btn-copy:hover {
        background: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="payment-container">
        <h1 class="font-roboto-slab text-2xl font-bold text-green-darker mb-4">
            Pembayaran Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
        </h1>

        <p class="text-gray-600 mb-6">
            Metode Pembayaran: <strong class="capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</strong>
        </p>

        @if ($order->payment_method === 'transfer_bank')
            {{-- Instruksi Transfer Bank --}}
            <div class="instruksi-box">
                <h3 class="font-semibold text-xl text-green-darker mb-4">Instruksi Transfer Bank</h3>
                
                <div class="space-y-3">
                    <p class="text-sm">Silakan transfer sejumlah total pembayaran ke salah satu rekening di bawah:</p>
                    
                    <div class="bg-white p-3 rounded-lg border">
                        <p class="text-sm font-medium">Bank Mandiri (a.n. Loka Loka)</p>
                        <div class="flex justify-between items-center mt-1">
                            <strong id="rek-mandiri" class="text-lg text-red-600">137000-XX-7777</strong>
                            <button class="btn-copy" onclick="copyToClipboard('137000-XX-7777', 'Rekening Mandiri')">Salin</button>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-lg border">
                        <p class="text-sm font-medium">Bank BCA (a.n. Loka Loka)</p>
                        <div class="flex justify-between items-center mt-1">
                            <strong id="rek-bca" class="text-lg text-red-600">777-XX-9999</strong>
                            <button class="btn-copy" onclick="copyToClipboard('777-XX-9999', 'Rekening BCA')">Salin</button>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-4">Pesanan akan diproses setelah pembayaran Anda terverifikasi (maks 1x24 jam).</p>
            </div>
        @elseif ($order->payment_method === 'cod')
            {{-- Instruksi COD --}}
            <div class="instruksi-box bg-green-50">
                <h3 class="font-semibold text-xl text-green-darker mb-4">Bayar di Tempat (COD)</h3>
                <p class="text-gray-700">Pembayaran akan dilakukan secara tunai kepada kurir saat pesanan Anda tiba di alamat:</p>
                <div class="mt-3 p-3 bg-white rounded-lg border text-sm whitespace-pre-wrap">
                    <p class="font-medium">{{ $order->user->name ?? 'N/A' }} ({{ $order->user->phone_number ?? 'N/A' }})</p>
                    <p>{{ $order->address_text }}</p>
                </div>
            </div>
        @endif

        <div class="border-t pt-4 mt-8">
            <h3 class="font-semibold text-green-darker mb-3">Ringkasan Pembayaran</h3>
            
            <div class="detail-row">
                <span class="text-gray-700">Subtotal Produk</span>
                <span>Rp {{ number_format($order->total - $order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="text-gray-700">Ongkos Kirim</span>
                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row total">
                <span>Total Tagihan</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-8 space-y-3">
            <a href="{{ route('profile') }}" class="btn btn-primary w-full">Lihat Status Pesanan Saya</a>
            <a href="{{ route('homepage') }}" class="btn btn-secondary w-full">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showNotification(message, type = 'success') {
        let notification = document.getElementById('notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            notification.className = 'notification';
            document.body.appendChild(notification);
        }
        notification.textContent = message;
        notification.style.display = 'block';
        notification.style.background = type === 'success' ? '#28a745' : '#dc3545';
        setTimeout(() => notification.style.display = 'none', 3000);
    }

    function copyToClipboard(text, item) {
        // Karena ini berjalan di iframe, kita pakai cara yang lebih aman
        const tempInput = document.createElement('textarea');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand('copy');
            showNotification(`${item} berhasil disalin!`, 'success');
        } catch (err) {
            console.error('Failed to copy text: ', err);
            showNotification('Gagal menyalin. Silakan salin manual.', 'error');
        }
        document.body.removeChild(tempInput);
    }
</script>
@endpush
