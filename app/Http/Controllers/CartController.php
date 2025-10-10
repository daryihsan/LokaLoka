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
        // 1. Otorisasi
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Sesi login tidak terdeteksi. Silakan login kembali.'], 401);
        }

        // 2. Validasi Input
        // Gunakan exists untuk is_active=1
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id,is_active,1', 
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Products::findOrFail($validated['product_id']); 
        $cart = $this->getUserCart();
        $quantityToAdd = (int)$validated['quantity'];

        // 3. Logika Penambahan/Pembaruan dengan Cek Stok
        try {
            DB::transaction(function () use ($product, $cart, $quantityToAdd) {
                
                $existingItem = CartItems::where('cart_id', $cart->id)
                    ->where('product_id', $product->id)
                    ->first();
                    
                $currentQty = $existingItem ? $existingItem->qty : 0;
                $newTotalQty = $currentQty + $quantityToAdd;
                
                // Cek Stok KRUSIAL: Menggunakan kolom 'stock' di tabel products
                if ($newTotalQty > $product->stock) {
                    // Gunakan Exception untuk memaksa rollback transaksi dan menangkap pesan error yang spesifik.
                    throw new \Exception('Stok tidak mencukupi. Tersedia: ' . $product->stock . ', Diminta: ' . $newTotalQty);
                }

                if ($existingItem) {
                    // Update item yang sudah ada
                    $existingItem->update(['qty' => $newTotalQty]);
                } else {
                    // Buat item baru
                    CartItems::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'qty' => $newTotalQty 
                    ]);
                }
            });

            // 4. Respon Sukses
            $cartItemCount = CartItems::where('cart_id', $cart->id)->sum('qty');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'count' => $cartItemCount
            ]);

        } catch (\Exception $e) {
            // Menangkap pesan exception yang spesifik (misal: "Stok tidak mencukupi...")
            // Menggunakan status 400 (Bad Request) agar frontend bisa menampilkan pesan spesifik.
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400); 
        }
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
                'description' => $item->product->description, // Tambah deskripsi untuk modal/view deskripsi
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

        $quantity = (int)$request->quantity;
        // Cek Stok (menggunakan products.stock)
        if ($cartItem->product->stock < $quantity) {
            return response()->json(['error' => 'Stok tidak mencukupi, stok tersedia: ' . $cartItem->product->stock], 400);
        }
        
        // Periksa apakah kuantitas benar-benar berubah sebelum update
        if ($cartItem->qty === $quantity) {
             return response()->json([
                'success' => 'Kuantitas tidak berubah.',
                'no_change' => true,
                'subtotal' => (float)$cartItem->product->price * $quantity, // tetap kirim subtotal saat ini
             ], 200);
        }
        
        // Update kolom 'qty'
        $cartItem->update(['qty' => $quantity]);
        
        // Hitung total keranjang yang baru
        $newTotal = CartItems::where('cart_id', $cart->id)
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            // Menghitung SUM(cart_items.qty * products.price)
            ->sum(DB::raw('cart_items.qty * products.price')); 

        return response()->json([
            'success' => 'Kuantitas berhasil diupdate.',
            'subtotal' => (float)$cartItem->product->price * $quantity,
            'new_total' => $newTotal,
            'new_item_qty' => $quantity,
            'no_change' => false
        ]);
    }

    // Remove item from cart
    public function removeFromCart($id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $cart = $this->getUserCart();
        
        // Melakukan penghapusan dan menyimpan jumlah baris yang terhapus
        $deleted = CartItems::where('cart_id', $cart->id)
                    ->where('id', $id)
                    ->delete();
        
        // Jika $deleted > 0, maka berhasil
        if ($deleted) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Item tidak ditemukan'], 404);
    }
}