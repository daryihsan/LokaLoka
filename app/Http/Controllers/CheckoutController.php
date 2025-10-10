<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\CartItems;
use App\Models\Products;
use App\Models\Orders;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CheckoutController extends Controller
{
    private function checkAuth()
    {
        return Session::has('logged_in') && Session::get('logged_in') === true &&
               Session::has('user_id');
    }

    private function getUserCart()
    {
        $userId = Session::get('user_id');
        return Carts::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function showCheckout()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        $userId = Session::get('user_id');
        $user = Users::find($userId);

        $cart = Carts::with(['cartItems.product'])->where('user_id', $userId)->first();

        // Jika keranjang kosong
        if (!$cart || $cart->cartItems->isEmpty()) {
            return Redirect::route('cart.show')->with('error', 'Keranjang Anda kosong atau belum memilih produk.');
        }

        // Kita hanya mengambil item yang ada di keranjang, meskipun data final akan datang dari JS (sessionStorage)
        // ketika tombol checkout diproses.
        $cartItems = $cart->cartItems;
        $totalHargaProduk = $cartItems->sum(function ($item) {
            return $item->product->price * $item->qty;
        });

        return view('checkout', compact('user', 'cartItems', 'totalHargaProduk'));
    }

    /**
     * Memproses checkout dan membuat order.
     * PERBAIKAN: Logika ini memastikan transaksi berjalan atomik.
     */
    public function processCheckout(Request $request)
    {
        if (!$this->checkAuth()) {
            return Redirect::route('login')->withErrors(['access' => 'Unauthorized access.']);
        }

        // 1. Validasi Input
        $validated = $request->validate([
            'cart_data' => 'required|json', // Data item yang dipilih untuk checkout
            'payment_method' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'address_text' => 'required|string',
        ]);

        $selectedItemsData = json_decode($validated['cart_data'], true);

        if (empty($selectedItemsData)) {
            return back()->with('error', 'Tidak ada produk yang dipilih untuk checkout.')->withInput();
        }

        $userId = Session::get('user_id');
        $cart = $this->getUserCart();
        $total = 0;

        // 2. Cek Stok dan Hitung Total (KRUSIAL)
        $productStockMap = Products::whereIn('id',
            collect($selectedItemsData)->pluck('product_id'))->pluck('stock', 'id');

        foreach ($selectedItemsData as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            if ($quantity > ($productStockMap[$productId] ?? 0)) {
                return back()->with('error', 'Stok produk ' . $item['name'] . ' tidak mencukupi (Tersedia: ' . ($productStockMap[$productId] ?? 0) . ')')->withInput();
            }
            $total += $item['price'] * $quantity;
        }

        $finalTotal = $total + $validated['shipping_cost'];

        // 3. Transaksi Database
        $order = DB::transaction(function () use ($userId, $finalTotal, $validated, $selectedItemsData, $cart) {

            // A. Buat Order
            $newOrder = Orders::create([
                'user_id' => $userId,
                'total' => $finalTotal,
                'status' => 'diproses',
                'payment_method' => $validated['payment_method'],
                'shipping_cost' => $validated['shipping_cost'],
                'address_text' => $validated['address_text'],
            ]);

            // B. Isi Order Items, Update Stock, dan Hapus dari Cart
            foreach ($selectedItemsData as $item) {
                // Tambahkan item ke order
                $newOrder->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update stock (decrement)
                Products::where('id', $item['product_id'])->decrement('stock', $item['quantity']);

                // Hapus Item dari keranjang (menggunakan cart_item id)
                CartItems::where('id', $item['id'])->delete();
            }

            // C. Cek apakah keranjang kosong setelah penghapusan
            if (CartItems::where('cart_id', $cart->id)->count() === 0) {
                // PENTING: Jangan hapus cart jika kita ingin user_id tetap punya record cart.
                // $cart->delete(); 
            }

            return $newOrder;
        });

        // 4. Pengalihan berdasarkan Metode Pembayaran
        $successMessage = 'Pesanan berhasil dibuat! Silakan lanjutkan ke pembayaran.';

        if ($order->payment_method === 'qris') {
            return Redirect::route('payment.qris', $order->id)->with('success', $successMessage);
        }

        // Untuk Transfer Bank / COD, alihkan ke halaman instruksi/konfirmasi
        return Redirect::route('payment.other', $order->id)->with('success', $successMessage);
    }
}
