<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            
        // Logika untuk menampilkan instruksi transfer bank atau konfirmasi COD
        // Kita gunakan view yang sama, tapi logic di view akan berbeda
        return view('payment', compact('order'));
    }
}
