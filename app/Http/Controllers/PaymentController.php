<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    public function showQris($orderId)
    {
        $order = Orders::findOrFail($orderId);

        // Generate random unique payment code
        $paymentCode = 'LOKALOKA_' . uniqid() . '_' . $order->id;

        $qrCode = QrCode::size(300)
            ->generate("ID.CO.QRIS.WWW.LOKALOKA.COM.PAYMENT/{$paymentCode}");

        return view('qr', compact('order', 'qrCode', 'paymentCode'));
    }
}