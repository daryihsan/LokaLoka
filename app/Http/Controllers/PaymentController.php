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
        
        // Generate unique payment code
        $paymentCode = 'LOKALOKA' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->generate("ID.CO.QRIS.WWW.LOKALOKA.COM.PAYMENT/{$paymentCode}");
            
        return view('qr', compact('order', 'qrCode', 'paymentCode'));
    }
}