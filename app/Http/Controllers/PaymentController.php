<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran QRIS
     */
    public function showQris($orderId)
    {
        // Ambil order beserta detail itemnya
        $order = Orders::with('orderItems.product', 'user')->findOrFail($orderId);

        // Generate random unique payment code
        $paymentCode = 'LOKALOKA' . uniqid() . $order->id;

        // PERBAIKAN: Gunakan SVG QR Code untuk kompatibilitas yang lebih baik
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate("ID.CO.QRIS.WWW.LOKALOKA.COM.PAYMENT/($paymentCode)");
            
        return view('qr', compact('order', 'qrCode', 'paymentCode'));
    }

    /**
     * Menampilkan halaman pembayaran non-QRIS (Transfer Bank, COD)
     */
    public function showOtherPayment($orderId)
    {
        // Ambil order beserta detail itemnya
        $order = Orders::with('orderItems.product', 'user')->findOrFail($orderId);

        // Tambahkan Kode Unik untuk transfer bank (stabil berdasarkan order id)
        $uniqueCode = null;
        $payableTotal = $order->total;

        if (($order->payment_method ?? null) === 'transfer_bank') {
            // Kode unik 3 digit berbasis id agar konsisten setiap kali dibuka
            $uniqueCode = str_pad((string)(($order->id % 999)), 3, '0', STR_PAD_LEFT);
            $payableTotal = (int)$order->total + (int)$uniqueCode;
        }

        // COD: jika status order sudah selesai/diterima, tampilkan indikator
        $isReceived = false;
        if (($order->payment_method ?? null) === 'cod') {
            $status = strtolower((string)($order->status ?? ''));
            $isReceived = in_array($status, ['delivered', 'completed', 'received', 'done']);
        }

        return view('payment', compact('order', 'uniqueCode', 'payableTotal', 'isReceived'));
    }
}