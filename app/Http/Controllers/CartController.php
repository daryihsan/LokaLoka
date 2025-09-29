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
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    private function checkAuth()
    {
        // PERBAIKAN: Memastikan user ID ada di sesi. Ini indikator login yang paling akurat.
        return Session::has('logged_in') && Session::get('logged_in') === true && Session::has('user_id');
    }

    private function getUserCart()
    {
        $userId = Session::get('user_id');
        // Get or create cart for user
        return Carts::firstOrCreate(['user_id' => $userId]);
    }

    // Add item to cart
    public function addToCart(Request $request)
    {
        // Periksa Auth. Jika gagal, kirim error 401 ke AJAX.
        if (!$this->checkAuth()) {
            // Mengembalikan 401: UNAUTHORIZED, yang akan ditangkap JavaScript
            return response()->json(['error' => 'Sesi login tidak terdeteksi. Silakan login kembali.'], 401); 
        }
        
        $product = Products::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 400);
        }

        $cart = $this->getUserCart();

        // Menggunakan DB::raw untuk operasi penambahan
        CartItems::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id
            ],
            [
                'qty' => DB::raw('qty + ' . (int)$request->quantity)
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'count' => CartItems::where('cart_id', $cart->id)->sum('qty')
        ]);
    }

    // Show cart view
    public function showCart()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }
        
        $userId = Session::get('user_id');
        $user = Users::find($userId);
        
        // Mengambil keranjang dengan item dan produk
        $cart = Carts::with(['cartItems.product'])->where('user_id', $userId)->first();
        
        if (!$cart) {
             // Jika keranjang kosong, kirim data keranjang kosong
             $cart = (object)['cartItems' => collect([]), 'total' => 0];
        }

        return view('cart', compact('cart', 'user'));
    }

    // API endpoint to get cart items (Digunakan oleh JS/AJAX di cart.blade.php)
    public function getCartItems()
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = $this->getUserCart();
        $cartItems = CartItems::where('cart_id', $cart->id)
            ->with('product.category')
            ->get();

        $items = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float)$item->product->price,
                'quantity' => $item->qty,
                'subtotal' => (float)$item->product->price * $item->qty,
                'image_url' => $item->product->image_url,
                'stock' => $item->product->stock,
                'category' => $item->product->category->name ?? null
            ];
        });

        $total = $items->sum('subtotal');
        $totalQuantity = $items->sum('quantity');

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => $totalQuantity,
            'success' => true
        ]);
    }
    
    // Update cart item quantity
    public function updateCartItem(Request $request, $id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }
        
        $cart = $this->getUserCart();
        $cartItem = CartItems::where('cart_id', $cart->id)->where('id', $id)->with('product')->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item tidak ditemukan'], 404);
        }

        $quantity = $request->quantity;
        if ($cartItem->product->stock < $quantity) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 400);
        }

        $cartItem->update(['qty' => $quantity]);
        
        // Hitung total keranjang yang baru
        $newTotal = CartItems::where('cart_id', $cart->id)
                            ->join('products', 'cart_items.product_id', '=', 'products.id')
                            ->sum(DB::raw('cart_items.qty * products.price'));

        return response()->json([
            'success' => 'Kuantitas berhasil diupdate.',
            'subtotal' => (float)$cartItem->product->price * $quantity,
            'new_total' => $newTotal
        ]);
    }

    // Remove item from cart
    public function removeFromCart($id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $cart = $this->getUserCart();
        $deleted = CartItems::where('cart_id', $cart->id)->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Item tidak ditemukan'], 404);
    }
    
    // Show checkout page
    public function showCheckout()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }
        
        // ... (sisanya tetap sama)
        $userId = Session::get('user_id');
        $user = Users::find($userId);
        $cart = Carts::with(['cartItems.product'])->where('user_id', $userId)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Keranjang Anda kosong atau belum memilih produk.');
        }

        $cartItems = $cart->cartItems;
        
        return view('checkout', compact('user', 'cartItems'));
    }

    // Process checkout
    public function checkout(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login')->withErrors(['access' => 'Unauthorized access.']);
        }

        // ... (sisanya tetap sama)
        $validated = $request->validate([
            'cart_data' => 'required|json', // Data item yang dipilih untuk checkout
            'payment_method' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'address_text' => 'required|string',
        ]);
        
        $selectedItemsData = json_decode($validated['cart_data'], true);
        
        if (empty($selectedItemsData)) {
            return back()->with('error', 'Tidak ada produk yang dipilih untuk checkout.');
        }
        
        $userId = Session::get('user_id');
        $cart = $this->getUserCart();
        $total = 0;
        
        // Cek stok dan hitung total
        $productStockMap = Products::whereIn('id', collect($selectedItemsData)->pluck('product_id'))->pluck('stock', 'id');
        
        foreach ($selectedItemsData as $item) {
            if ($item['quantity'] > $productStockMap[$item['product_id']]) {
                return back()->with('error', 'Stok produk ' . $item['name'] . ' tidak mencukupi.')->withInput();
            }
            $total += $item['price'] * $item['quantity'];
        }
        
        $finalTotal = $total + $validated['shipping_cost'];

        $order = DB::transaction(function () use ($userId, $finalTotal, $validated, $selectedItemsData, $cart) {
            // 1. Buat Order
            $newOrder = Orders::create([
                'user_id' => $userId,
                'total' => $finalTotal,
                'status' => 'diproses',
                'payment_method' => $validated['payment_method'],
                'shipping_cost' => $validated['shipping_cost'],
                'address_text' => $validated['address_text'],
            ]);

            // 2. Isi Order Items, Update Stock, dan Hapus dari Cart
            foreach ($selectedItemsData as $item) {
                // Tambahkan item ke order
                $newOrder->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update stock
                Products::where('id', $item['product_id'])->decrement('stock', $item['quantity']);

                // Hapus item dari keranjang (menggunakan cart_item_id)
                CartItems::where('id', $item['id'])->delete();
            }
            
            // Cek apakah keranjang kosong setelah penghapusan
            if (CartItems::where('cart_id', $cart->id)->count() == 0) {
                 $cart->delete(); // Hapus keranjang jika sudah kosong
            }

            return $newOrder;
        });

        if ($order->payment_method === 'qris') {
            return redirect()->route('payment.qris', $order->id);
        }
        
        return redirect()->route('orders')->with('success', 'Pesanan berhasil dibuat! Segera lakukan pembayaran.');
    }
}