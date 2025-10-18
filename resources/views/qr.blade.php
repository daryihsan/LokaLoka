@extends('layouts.app')

@section('title', 'Pembayaran QRIS - Loka Loka')

@push('head')
<style>
    .font-roboto { font-family: 'Roboto', sans-serif; }
    .text-green-darker { color: #333D29; }
    .timer-ring { stroke-dasharray: 251; stroke-dashoffset: 251; animation: timer-countdown 300s linear; }
    @keyframes timer-countdown { from { stroke-dashoffset: 251; } to { stroke-dashoffset: 0; } }
    .pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-4 text-sm text-gray-600">
        Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <div class="mb-6">
                <h2 class="font-roboto text-2xl font-bold text-green-darker mb-2">Scan QR Code</h2>
                <p class="text-gray-600">Gunakan aplikasi e-wallet atau m-banking untuk scan QR code di bawah ini</p>
            </div>

            <div class="bg-gray-100 p-8 rounded-xl mb-6 inline-block">
                <div class="qr-container">
                    {!! $qrCode !!}
                    <p class="text-xs text-gray-500 mt-2">Kode Pembayaran: {{ $paymentCode }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-6">
                <div class="text-sm text-gray-600 mb-1">Total Pembayaran</div>
                <div class="text-3xl font-bold text-green-800">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </div>
            </div>

            <div class="flex items-center justify-center mb-6">
                <div class="relative">
                    <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="36" stroke="#e5e7eb" stroke-width="8" fill="none"></circle>
                        <circle cx="40" cy="40" r="36" stroke="#3b82f6" stroke-width="8" fill="none" class="timer-ring"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span id="timer" class="text-lg font-bold text-blue-600">05:00</span>
                    </div>
                </div>
            </div>

            <div id="payment-status" class="text-center">
                <div class="pulse inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-yellow-600 mr-2"></div>
                    Menunggu Pembayaran...
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="font-roboto text-xl font-bold text-green-darker mb-6">Ringkasan Pesanan ({{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }})</h3>

            <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center relative overflow-hidden">
                        {{-- TAMBAHAN: Link ke Detail Produk dan Gambar --}}
                            <a href="{{ route('product.show', $item->product->id) }}" target="_blank" title="Lihat Detail Produk">
                                <img src="{{ $item->product->image_url ?? 'https://placehold.co/50x50/e3d8c2/5c6641?text=P' }}" 
                                    alt="{{ $item->product->name ?? 'Produk Dihapus' }}" 
                                    style="object-fit: contain;"
                                    class="w-full h-full"
                                >
                            </a>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-green-darker">
                                <a href="{{ route('product.show', $item->product->id) }}" class="hover:underline">{{ $item->product->name ?? 'Produk Dihapus' }}</a>
                            <p class="text-sm text-gray-600">{{ $item->qty }}x @php echo number_format($item->price, 0, ',', '.') @endphp</p>
                        </div>
                        <div class="text-right text-sm">
                            <p class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t pt-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal Produk</span>
                    <span>Rp {{ number_format($order->total - $order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span class="text-green-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <a href="{{ route('profile') }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-medium transition-colors">Lihat Pesanan Saya</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let timeRemaining = 300;
    let timerInterval;

    function startTimer() {
        timerInterval = setInterval(() => {
            timeRemaining--;
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            const el = document.getElementById('timer');
            if (el) el.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
            if (timeRemaining <= 0) { clearInterval(timerInterval); }
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', startTimer);
</script>
@endpush